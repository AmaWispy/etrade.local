<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
//use App\Services\HttpClientService;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ContactController extends Controller
{
    /**
     * Update the application's locale.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $client = new Client();

        $post = $request->post();

        if(isset($post['locale'])){
            \Illuminate\Support\Facades\App::setLocale($post['locale']);
            unset($post['locale']);
        }

        $target = '//api.telegram.org/bot' . config('app.tg_api_token') . '/sendMessage';
        $chatIDs = config('app.tg_chat_ids');

        $message = $this->buildMessage($post);

        $query = [
            'chat_id' => null,
            'parse_mode' => 'html',
            'text' => $message
        ];

        foreach($chatIDs as $id){
            $query['chat_id'] = $id;
            try {
                $client->post($target, ['query' => $query]);
            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    $statusCode = $e->getResponse()->getStatusCode();
                    $errorBody = $e->getResponse()->getBody()->getContents();
                } else {
                    $statusCode = 500;
                    $errorBody = "Request failed: " . $e->getMessage();
                }

                return [
                    'status' => $statusCode,
                    'error' => $errorBody
                ];
            }
        }

        return [
            'status' => 200,
            'message' => trans('template.message_sent')
        ];
    }

    protected function buildMessage($data)
    {
        // Set message title depending on form
        $forms = [
            'contact' => trans('template.new_message'),
            'callback' => trans('template.callback_request')
        ];

        $messageTitle = $forms[$data['form']];
        unset($data['form']);

        $separator = "================================\n";

        // Convert form keys into params names
        $params = [
            'name' => trans('template.name'),
            'phone' => trans('template.phone'),
            'email' => trans('template.email'),
            'message' => trans('template.message'),
            'purpose' => trans('template.purpose')
        ];

        $message = "<b>$messageTitle</b>\n";
        $message .= $separator;

        // Set the rest of form data as params
        foreach($data as $key => $value){
            $message .= "<b>" . $params[$key] . "</b>: " . $value . "\n";
        }

        return $message;
    }
}
