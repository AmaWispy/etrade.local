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
            \Log::warning('Telegram bot token not configured. Skipping notification.');
            return;
        }

        // Настройки для Guzzle клиента
        $clientOptions = [
            'timeout' => 30,
            'verify' => false, // Отключаем проверку SSL сертификатов для локальной разработки
        ];

        // В продакшене можно включить проверку SSL
        if (config('app.env') === 'production') {
            $clientOptions['verify'] = true;
        }

        $client = new Client($clientOptions);

        try {
            $target = 'https://api.telegram.org/bot' . config('app.tg_api_token') . '/sendMessage';
            $chatIDs = config('app.tg_chat_ids');

            if (empty($chatIDs)) {
                \Log::warning('Telegram chat IDs not configured. Skipping notification.');
                return;
            }

            foreach($chatIDs as $id){
                $response = $client->post($target, [
                    'form_params' => [
                        'chat_id' => $id,
                        'parse_mode' => 'html',
                        'text' => $notification
                    ]
                ]);
                
                // Логируем успешную отправку
                if ($response->getStatusCode() === 200) {
                    \Log::info("Telegram notification sent successfully to chat_id: {$id}");
                } else {
                    \Log::error("Telegram notification failed with status: " . $response->getStatusCode());
                }
            }
        } catch (\Exception $e) {
            \Log::error("Telegram notification failed: " . $e->getMessage());
            // Не бросаем исключение, чтобы не прерывать процесс оформления заказа
        }
    }

    public function buildTgCustomNotification($order) 
    {
        $currency = json_decode($order->currency);
        $currencySign = $currency->iso_alpha ?? 'MDL';
        
        // Красивые разделители с эмодзи
        $header = "🎉═══════════════════════════════🎉\n";
        $separator = "═══════════════════════════════\n";
        $smallSeparator = "─────────────────────────────\n";
        
        // Начало сообщения
        $message = $header;
        $message .= "🛒 <b>НОВЫЙ ЗАКАЗ #{$order->id}</b> 🛒\n";
        $message .= $header;
        
        // Информация о заказе
        $message .= "📋 <b>Информация о заказе:</b>\n";
        $message .= "🆔 ID: <code>{$order->id}</code>\n";
        $message .= "🔑 GUID: <code>{$order->guid}</code>\n";
        $message .= "📅 Дата: <code>{$order->created_at->format('d.m.Y H:i')}</code>\n";
        $message .= "🚦 Статус: <b>" . $this->getStatusEmoji($order->status) . " " . strtoupper($order->status) . "</b>\n";
        $message .= $separator;
        
        // Информация о клиенте
        $message .= "👤 <b>Клиент:</b>\n";
        if ($order->client) {
            $message .= "📛 Имя: <b>{$order->client->name}</b>\n";
            $message .= "📧 Email: <code>{$order->client->email}</code>\n";
            if ($order->client->phone) {
                $message .= "📞 Телефон: <code>{$order->client->phone}</code>\n";
            }
            $message .= "🆔 ID клиента: <code>#{$order->client->id}</code>\n";
        } else {
            $message .= "👻 <b>Гостевой заказ</b>\n";
            $message .= "ℹ️ <i>Заказ оформлен без регистрации</i>\n";
        }
        $message .= $separator;
        
        // Товары в заказе
        $message .= "🛍️ <b>Товары в заказе:</b>\n";
        $totalItems = 0;
        foreach($order->cart->items as $key => $item) {
            $message .= "📦 <b>{$item->product->name}</b>\n";
            if ($item->product->sku) {
                $message .= "🏷️ SKU: <code>{$item->product->sku}</code>\n";
            }
            $message .= "📊 Количество: <b>{$item->qty} шт.</b>\n";
            $message .= "💰 Цена за единицу: <b>" . number_format($item->unit_price, 2) . " {$currencySign}</b>\n";
            
            $subtotal = round($item->qty * $item->unit_price, 2);
            $message .= "💵 Подитог: <b>" . number_format($subtotal, 2) . " {$currencySign}</b>\n";
            
            $totalItems += $item->qty;
            
            // Добавляем разделитель между товарами (кроме последнего)
            if ($key !== $order->cart->items->keys()->last()) {
                $message .= $smallSeparator;
            }
        }
        $message .= $separator;
        
        // Сводка заказа
        $message .= "📊 <b>Сводка заказа:</b>\n";
        $message .= "📦 Всего товаров: <b>{$totalItems} шт.</b>\n";
        $message .= "💎 Общая сумма: <b>" . number_format($order->total, 2) . " {$currencySign}</b>\n";
        
        // Курс валюты (если не MDL)
        if ($currencySign !== 'MDL' && isset($currency->exchange_rate)) {
            $mdlTotal = $order->total * $currency->exchange_rate;
            $message .= "💱 Курс: <code>1 {$currencySign} = {$currency->exchange_rate} MDL</code>\n";
            $message .= "💰 В MDL: <b>" . number_format($mdlTotal, 2) . " MDL</b>\n";
        }
        $message .= $separator;
        
        // Комментарии к заказу
        if ($order->comments) {
            $message .= "💬 <b>Комментарии:</b>\n";
            $message .= "📝 <i>{$order->comments}</i>\n";
            $message .= $separator;
        }
        
        // Административная информация
        $message .= "⚙️ <b>Управление:</b>\n";
        $message .= "🔗 <a href='" . config('app.url') . "/admin/order-customs/{$order->id}/edit'>Открыть в админ-панели</a>\n";
        $message .= "🌐 <a href='" . config('app.url') . "'>Перейти на сайт</a>\n";
        
        $message .= $header;
        $message .= "🤖 <i>Автоматическое уведомление от " . config('app.name') . "</i>\n";
        $message .= "⏰ <i>" . now()->format('d.m.Y H:i:s') . "</i>";
        
        return $message;
    }
    
    /**
     * Получить эмодзи для статуса заказа
     */
    private function getStatusEmoji($status) 
    {
        return match($status) {
            'new' => '🆕',
            'processing' => '⚙️',
            'completed' => '✅',
            'error' => '❌',
            'pending' => '⏳',
            'verification' => '🔍',
            default => '📋'
        };
    }
}