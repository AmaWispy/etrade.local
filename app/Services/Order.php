<?php

namespace App\Services;

use App\Helpers\Check;
use GuzzleHttp\Client;

class Order
{
    use Check;
    /**
     * Build notification
     *
     * @param array $order
     * @return mixed
     */
    public function buildTgNotification($order) 
    {
        $separator = "================================\n";
        $separatorV2 = "--------------------------------\n";

        $message = $separator;
        $message .= "<b>NEW ORDER #".$order->id."</b>\n";
        $message .= $separator;
        $message .= "<b>Customer:</b>\n";
        $message .= $order->customer->name . "\n";
        $message .= $order->customer->phone . "\n";
        $message .= $order->customer->email . "\n";
        $message .= $separator;
        $message .= "<b>Address:</b>\n";
        if ($order->address && !empty($order->address->getCountryName()) && !empty($order->address->getLocalityName())) {
            $message .= $order->address->getCountryName() . "\n";
            $message .= $order->address->getLocalityName() . "\n";
        }

        if($order->address && $this->notEmpty([$order->address->street, $order->address->house_number, $order->address->phone_number, $order->address->entrance, $order->address->floor, $order->address->intercom, $order->address->district_city, $order->address->district])){
            // $message .= 'District:' . $order->address->district . "\n";
            // $message .= 'District City:' . $order->address->district_city . "\n";
            $message .= 'Street:' . $order->address->street . "\n";
            $message .= 'House Nr:' . $order->address->house_number . "\n";
            $message .= 'Phone Nr:' . $order->address->phone_number . "\n";
            $message .= 'Entrance:' . $order->address->entrance . "\n";
            $message .= 'Floor:' . $order->address->floor . "\n";
            $message .= 'Intercom:' . $order->address->intercom . "\n";

        }


        $message .= $separator;
        $message .= "<b>Order:</b>\n";
        foreach($order->cart->items as $key => $item){
            if($item->product->type === \App\Models\Shop\Product::VARIABLE){
                $message .= $item->variation->getTranslation('name', 'en') . "\n";
            } else {
                $message .= $item->product->getTranslation('name', 'en') . "\n";
            }
            $message .= $item->qty . " x " . $item->unit_price . " MDL\n";
            $subtotal = round($item->qty * $item->unit_price, 2);
            $message .= "<b>Subtotal: " . $subtotal . " MDL</b>\n";
            if ($key !== $order->cart->items->keys()->last()) {
                $message .= $separatorV2;
            }
        }
        $message .= $separator;
        $message .= "<b>Shipping:</b>\n";
        $message .= "<b>" . $order->shippingMethod->getTranslation('name', 'en') . ": " . $order->shipping . " MDL</b>\n";
        $message .= $separator;
        $message .= "<b>Payment:</b>\n";
        $message .= "<b>" . $order->paymentMethod->getTranslation('name', 'en') . ": " . $order->total . " MDL</b>\n";

        return $message;
    }

    /**
     * Send notification
     *
     * @param string $notification
     * @return void
     */
    public function sendTgNotification($notification)
    {
        /**
         * Prevent sending notifications in case api token is not specified
         */
        if(empty(config('app.tg_api_token'))){
            return;
        }

        $client = new Client();

        try {
            $target = '//api.telegram.org/bot' . config('app.tg_api_token') . '/sendMessage';
            $chatIDs = config('app.tg_chat_ids');

            $query = [
                'chat_id' => null,
                'parse_mode' => 'html',
                'text' => $notification
            ];
                                            
            foreach($chatIDs as $id){
                $query['chat_id'] = $id;
                $client->post($target, ['query' => $query]);
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}