<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop\PaymentMethod;
use App\Models\Shop\Order;
use App\Models\Shop\Payment;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use App\Services\Order as OrderUtils;

class PaynetController extends Controller
{
    protected $utils;

    public function __construct(OrderUtils $utils)
    {
        $this->utils = $utils;
    }

    public function callback(Request $request)
    {
        if(!empty($request->post())){
            $post = $request->post();
            // Log incoming data
            Log::channel('paynet')->info([
                'desc' => 'Payment notification',
                'post' => $post
            ]);

            // Check if payload is payment notification
            if(isset($post['Payment'])){
                // Verify order and change order status
                $paymentMethod = PaymentMethod::where('code', 'paynet')->first();
                $config = $paymentMethod->config;

                if(filter_var($config['test_mode'], FILTER_VALIDATE_BOOLEAN)){
                    // Remove prefix in case if test mode enabled
                    $orderId = substr($post['Payment']['ExternalId'], 4);
                } else {
                    $orderId = $post['Payment']['ExternalId'];
                }

                $paymentId = $post['Payment']['Id']; //payment id on paynet

                $order = Order::find($orderId);

                if($order->status === Order::PROCESSING){
                    return response('OK', 200);
                }

                $payment = Payment::create([
                    'order_id' => $order->id,
                    'reference' => $paymentId,
                    'provider' => $paymentMethod->code,
                    'amount' => $post['Payment']['Amount'] / 100,
                    'currency' => 'MDL'
                ]);

                if ($post['EventType'] === 'PAID'){
                    /**
                     * Register transaction
                     */
                    $payment->update([
                        'status' => Payment::REGISTERED,
                        'info' => json_encode($post)
                    ]);
                    $order->update(['status' => Order::VERIFICATION]);
                    
                    /**
                     * Verify transaction status
                     */
                    $token = $this->getApiToken($config);
                    if($token){
                        $status = $this->getPaymentStatus($config, $token, $paymentId);
                        if($status && $status === 4){
                            $payment->update(['status' => Payment::SUCCEED]);
                            $order->update(['status' => Order::PROCESSING]);
                            $notification = $this->utils->buildTgNotification($order);
                            $this->utils->sendTgNotification($notification); 
                        } else {
                            $payment->update(['status' => Payment::DECLINED]);
                            // Restore PENDING status to alow one more try to pay
                            $order->update(['status' => Order::PENDING]);
                        }
                    }
                } else {
                    $payment->update(['status' => Payment::FAILED]);
                    // Restore PENDING status to alow one more try to pay
                    $order->update(['status' => Order::PENDING]);
                }
            }
        } else {
            Log::channel('paynet')->info([
                'desc' => 'Callback accessed',
                'body' => 'Empty post'
            ]);
        }

        return response('OK', 200);
    }

    protected function getApiToken($config)
    {   
        $ch = curl_init($this->getApiUrl(filter_var($config['test_mode'], FILTER_VALIDATE_BOOLEAN)) . '/auth'); 
        header('Content-Type: application/json');
        $data = 'grant_type=password&username=' . $config['api_login'] . '&password=' . $config['api_pass']; 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        $responseRaw = curl_exec($ch);

        if (curl_errno($ch)) {
            $errorMsg = curl_error($ch);
        }

        curl_close($ch);

        if(isset($errorMsg)){
            Log::channel('paynet')->info([
                'desc' => 'Error while obtaining api token',
                'body' => $errorMsg
            ]);
        }
       
        // Log response
        Log::channel('paynet')->info([
            'desc' => 'Api token obtained',
            'body' => $responseRaw
        ]);

        $response = json_decode($responseRaw, true);

        if(isset($response['access_token'])){
            return $response['access_token'];
        } else {
            return false;
        }
    }

    protected function getPaymentStatus($config, $token, $payment)
    {
        

        $ch = curl_init($this->getApiUrl(filter_var($config['test_mode'], FILTER_VALIDATE_BOOLEAN)) . '/api/Payments/' . $payment);
        header('Content-Type: application/json');
        $authorization = "Authorization: Bearer ".$token; // **Prepare Autorisation Token**
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // **Inject Token into Header**
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
        $responseRaw = curl_exec($ch);
        curl_close($ch);
        
        // Log response
        Log::channel('paynet')->info([
            'desc' => 'Transaction status verification',
            'body' => $responseRaw
        ]);

        $response = json_decode($responseRaw, true);

        if(isset($response['Status'])){
            return $response['Status'] ;
        } else {
            return false;
        }
    }

    protected function getApiUrl($testMode)
    {
        if($testMode){
            return 'https://api-merchant.test.paynet.md';
        } else {
            return 'https://api-merchant.paynet.md';
        }
    }
}
