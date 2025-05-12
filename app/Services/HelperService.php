<?php

namespace App\Services;

class HelperService
{
    /**
     * Check if client_id exists in session
     *
     * @return bool
     */
    public static function isClientAuth(): bool
    {
        if (session()->has('client_id')) {
            return session()->get('client_id');
        }
        return false;
    }
} 