<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Http;
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
                $response = Http::withBasicAuth(
                    config('services.api.username'),
                    config('services.api.password')
                )->get(config('services.api.url'));

                if ($response->successful()) {
                    $data = $response->json();
                    
                    Client::create([
                        'data' => $data
                    ]);
                    
                    $success = true;
                    Log::info('Clients data fetched and stored successfully at ' . now()->format('Y-m-d H:i:s'));
                } else {
                    throw new \Exception('API request failed with status: ' . $response->status());
                }
            } catch (\Exception $e) {
                $retryCount++;
                if ($retryCount >= $maxRetries) {
                    Log::error('Unable to get clients: ' . now()->format('Y-m-d H:i:s'));
                } else {
                    Log::warning('Attempt ' . $retryCount . ' failed. Retrying...');
                    sleep(1); // Wait 1 second before retrying
                }
            }
        }
    }
}