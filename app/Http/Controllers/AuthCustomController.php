<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ClientLoginRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class AuthCustomController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.custom-auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(ClientLoginRequest $request)
    {
        $request->ensureIsNotRateLimited();

        $client = Client::where('email', $request->email)->first();

        Log::info('Login attempt', [
            'email' => $request->email,
            'client_found' => !is_null($client),
            'access_code_length' => strlen($request->access_code),
        ]);

        if (!$client) {
            Log::warning('Client not found', ['email' => $request->email]);
            RateLimiter::hit($request->throttleKey());
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $isValid = $client->verifyAccessCode($request->access_code);
        Log::info('Access code verification', [
            'is_valid' => $isValid,
            'client_id' => $client->id,
        ]);

        if (!$isValid) {
            RateLimiter::hit($request->throttleKey());
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        Auth::guard('client')->login($client);

        RateLimiter::clear($request->throttleKey());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Login successful',
                'client' => $client
            ]);
        }

        return redirect()->intended('/');
    }

    /**
     * Log the client out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::guard('client')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logged out successfully']);
        }

        return redirect('/');
    }

    /**
     * Get the authenticated client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function client(Request $request)
    {
        $client = Auth::guard('client')->user();

        if (!$client) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($client);
    }
}
