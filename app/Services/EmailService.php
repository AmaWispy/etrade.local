<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function sendEmail($to, $subject, $content)
    {
        try {
            Mail::send([], [], function ($message) use ($to, $subject, $content) {
                $message->to($to)
                        ->subject($subject)
                        ->text($content);
            });

            return true; // Email sent successfully
        } catch (\Exception $e) {
            // Handle exceptions, log errors, etc.
            return false; // Email sending failed
        }
    }
}