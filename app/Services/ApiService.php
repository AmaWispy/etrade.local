<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Shop\Product;
use App\Models\Shop\Currency;
use Illuminate\Support\Facades\Log;

class ApiService
{
    public static function fetchAndStoreClients()
    {
        $maxRetries = 2;
        $retryCount = 0;
        $success = false;


        while ($retryCount < $maxRetries && !$success) {
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, config('services.api.url') . 'CustomersList');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, config('services.api.username') . ":" . config('services.api.password'));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200) {
                    $data = json_decode($response, true);
                    
                    if (is_array($data)) {
                        // Очищаем таблицу перед добавлением новых данных
                        Client::truncate();
                        
                        foreach ($data as $clientData) {
                            Client::create([
                                'access_code' => $clientData['AccessCode'] ?? null,
                                'id' => $clientData['Nr'] ?? null,
                                'code' => $clientData['Code'] ?? null,
                                'status' => $clientData['Status'] ?? null,
                                'name' => $clientData['Name'] ?? null,
                                'email' => $clientData['Login'] ?? null,
                                'registration_number' => $clientData['RegistrationNr'] ?? null
                            ]);
                        }
                        
                        $success = true;
                        Log::info('Clients data fetched and stored successfully at ' . now()->format('Y-m-d H:i:s'));
                    } else {
                        throw new \Exception('Invalid response format from API');
                    }
                } else {
                    throw new \Exception('API request failed with status: ' . $httpCode);
                }
            } catch (\Exception $e) {
                $retryCount++;
                if ($retryCount >= $maxRetries) {
                    Log::error('Unable to get clients: ' . $e->getMessage() . ' at ' . now()->format('Y-m-d H:i:s'));
                } else {
                    Log::warning('Attempt ' . $retryCount . ' failed. Retrying...');
                    sleep(1); // Wait 1 second before retrying
                }
            }
        }
        /* 
            {
                "Nr": "1",
                "Code": "2309",
                "Status": "DISTRIBUTIE",
                "Name": "Servcomputer Plus SRL",
                "RegistrationNr": "1017603006879",
                "AccessCode": "Servcomp888"
            },
         */
    }

    public static function fetchAndStoreProducts()
    {
        $maxRetries = 2;
        $retryCount = 0;
        $success = false;

        while ($retryCount < $maxRetries && !$success) {
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, config('services.api.url') . 'ProductsList');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, config('services.api.username') . ":" . config('services.api.password'));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200) {
                    $data = json_decode($response, true);
                    
                    if (is_array($data)) {
                        foreach ($data as $productData) {
                            $price = $productData['Price'] ?? 0;
                            $currency = 'MDL';
                            // Переводим цену в основную валюту
                            if ($price > 0) {
                                $convertedPrice = \App\Models\Shop\Currency::exchangeUSD($price);
                            } else {
                                $convertedPrice = 0;
                            }

                            $product = Product::where('id', $productData['ID'])->first();
                            $productData = [
                                'id' => $productData['ID'] ?? null,
                                'code' => $productData['Code'] ?? null,
                                'name' => $productData['Name'] ?? null,
                                'name_ru' => $productData['NameRu'] ?? null,
                                'name_en' => $productData['NameEn'] ?? null,
                                'name_full' => $productData['NameFull'] ?? null,
                                'sku' => $productData['Sku'] ?? null,
                                'articul' => $productData['Articul'] ?? null,
                                'brand' => $productData['Brand'] ?? null,
                                'brand_code' => $productData['BrandCode'] ?? null,
                                'category' => $productData['Category'] ?? null,
                                'category_code' => $productData['CategoryCode'] ?? null,
                                'additional_cat' => $productData['AdditionalCat'] ?? null,
                                'additional_cat_code' => $productData['AdditionalCatCode'] ?? null,
                                'description' => $productData['Description'] ?? null,
                                'unit_type' => $productData['UnitType'] ?? null,
                                'stock_quantity' => $productData['StockQuantity'] ?? 0,
                                'reserved' => $productData['Reserved'] ?? 0,
                                'price' => $convertedPrice,
                                'currency' => $currency,
                                'default_price' => $productData['DefaultPrice'] ?? 0,
                                'default_currency' => $productData['DefaultCurrency'] ?? 'MDL'
                            ];

                            if ($product) {
                                // Обновляем существующий продукт, сохраняя текущие изображения
                                $product->update($productData);
                                $product->save();
                            } else {
                                // Создаем новый продукт
                                Product::create($productData);
                            }
                        }
                        
                        $success = true;
                        Log::info('Products data fetched and stored successfully at ' . now()->format('Y-m-d H:i:s'));
                    } else {
                        throw new \Exception('Invalid response format from API');
                    }
                } else {
                    throw new \Exception('API request failed with status: ' . $httpCode);
                }
            } catch (\Exception $e) {
                $retryCount++;
                if ($retryCount >= $maxRetries) {
                    Log::error('Unable to get products: ' . $e->getTraceAsString() . ' at ' . now()->format('Y-m-d H:i:s'));
                } else {
                    Log::warning('Attempt ' . $retryCount . ' failed. Retrying...');
                    sleep(1); // Ждем 1 секунду перед повторной попыткой
                }
            }
        }
    }

    public static function fetchAndStoreCurrencies()
    {
        $maxRetries = 2;
        $retryCount = 0;
        $success = false;

        while ($retryCount < $maxRetries && !$success) {
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, config('services.api.url') . 'GetCurrencies');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, config('services.api.username') . ":" . config('services.api.password'));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200) {
                    $data = json_decode($response, true);
                    
                    if (is_array($data)) {
                        foreach ($data as $currencyData) {
                            // We only need EUR (ID 2) and USD (ID 3)
                            if ($currencyData['Id'] == '4') { // EUR
                                $currency = Currency::find(2);
                                if ($currency) {
                                    $currency->rate = $currencyData['Value'];
                                    $currency->save();
                                }
                            } elseif ($currencyData['Id'] == '2') { // USD
                                $currency = Currency::find(3);
                                if ($currency) {
                                    $currency->rate = $currencyData['Value'];
                                    $currency->save();
                                }
                            }
                        }
                        
                        $success = true;
                        Log::info('Currency rates fetched and stored successfully at ' . now()->format('Y-m-d H:i:s'));
                    } else {
                        throw new \Exception('Invalid response format from API');
                    }
                } else {
                    throw new \Exception('API request failed with status: ' . $httpCode);
                }
            } catch (\Exception $e) {
                $retryCount++;
                if ($retryCount >= $maxRetries) {
                    Log::error('Unable to get currency rates: ' . $e->getTraceAsString() . ' at ' . now()->format('Y-m-d H:i:s'));
                } else {
                    Log::warning('Attempt ' . $retryCount . ' failed. Retrying...');
                    sleep(1);
                }
            }
        }
    }

    public static function putOrder($order)
    {
        $maxRetries = 2;
        $retryCount = 0;
        $success = false;

        while ($retryCount < $maxRetries && !$success) {
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, config('services.api.url') . 'PutOrder');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, config('services.api.username') . ":" . config('services.api.password'));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json'
                ]);

                // Подготовка данных для отправки
                $requestData = [
                    'Id' => $order->guid,
                    'CustomerId' => $order->client->code,
                    'CreatedOn' => $order->created_at->format('Y-m-d H:i:s'),
                    'CustomerAccessCode' => $order->client->access_code,
                    'OrderItems' => []
                ];

                // Добавляем товары в заказ
                foreach ($order->items as $index => $item) {
                    $requestData['OrderItems'][] = [
                        'Id' => (string)($index + 1),
                        'ProductCode' => $item->product->code,
                        'Quantity' => $item->qty
                    ];
                }

                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200) {
                    // Обновляем количество товаров в базе данных
                    foreach ($order->items as $item) {
                        $product = $item->product;
                        $newQuantity = $product->stock_quantity - $item->qty;
                        $product->update([
                            'stock_quantity' => max(0, $newQuantity) // Не даем уйти в минус
                        ]);
                    }
                    
                    $success = true;
                    Log::info('Order successfully sent to API and stock updated at ' . now()->format('Y-m-d H:i:s'));
                } else {
                    throw new \Exception('API request failed with status: ' . $httpCode);
                }
            } catch (\Exception $e) {
                $retryCount++;
                if ($retryCount >= $maxRetries) {
                    Log::error('Unable to send order: ' . $e->getMessage() . ' at ' . now()->format('Y-m-d H:i:s'));
                } else {
                    Log::warning('Attempt ' . $retryCount . ' failed. Retrying...');
                    sleep(1); // Ждем 1 секунду перед повторной попыткой
                }
            }
        }

        return $success;
    }

    public static function fetchAndStoreCategories()
    {
        $maxRetries = 2;
        $retryCount = 0;
        $success = false;

        while ($retryCount < $maxRetries && !$success) {
            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, config('services.api.url') . 'GetCategories');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($ch, CURLOPT_USERPWD, config('services.api.username') . ":" . config('services.api.password'));
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200) {
                    $data = json_decode($response, true);
                    
                    if (is_array($data)) {
                        // Очищаем таблицу перед добавлением новых данных
                        \App\Models\CategoryCustom::truncate();
                        
                        foreach ($data as $categoryData) {
                            \App\Models\CategoryCustom::create([
                                'id' => ($categoryData['Nr'] ?? null) === '' ? null : $categoryData['Nr'] ?? null,
                                'code' => ($categoryData['Code'] ?? null) === '' ? null : $categoryData['Code'] ?? null,
                                'name' => ($categoryData['Name'] ?? null) === '' ? null : $categoryData['Name'] ?? null,
                                'name_ru' => ($categoryData['NameRu'] ?? null) === '' ? null : $categoryData['NameRu'] ?? null,
                                'name_en' => ($categoryData['NameEn'] ?? null) === '' ? null : $categoryData['NameEn'] ?? null,
                                'some_order' => ($categoryData['Order'] ?? null) === '' ? null : $categoryData['Order'] ?? null,
                                'parent_code' => ($categoryData['ParentCode'] ?? null) === '' ? null : $categoryData['ParentCode'] ?? null,
                                'parent' => ($categoryData['Parent'] ?? null) === '' ? null : $categoryData['Parent'] ?? null
                            ]);
                        }
                        
                        $success = true;
                        Log::info('Categories data fetched and stored successfully at ' . now()->format('Y-m-d H:i:s'));
                    } else {
                        throw new \Exception('Invalid response format from API');
                    }
                } else {
                    throw new \Exception('API request failed with status: ' . $httpCode);
                }
            } catch (\Exception $e) {
                $retryCount++;
                if ($retryCount >= $maxRetries) {
                    Log::error('Unable to get categories: ' . $e->getMessage() . ' at ' . now()->format('Y-m-d H:i:s'));
                } else {
                    Log::warning('Attempt ' . $retryCount . ' failed. Retrying...');
                    sleep(1); // Wait 1 second before retrying
                }
            }
        }

        return $success;
    }
}