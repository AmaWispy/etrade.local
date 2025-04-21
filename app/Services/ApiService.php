<?php

namespace App\Services;

use App\Models\Client;
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
}