<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

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
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'access_code' => ['required', 'string'],
            'email' => ['required', 'string'],
        ]);

        // Find client by access_code
        $client = Client::where('access_code', $credentials['access_code'])->first();

        if ($client) {
            // Create session for the client
            $request->session()->put('client_id', $client->id);
            $request->session()->put('client_code', $client->access_code);
            $request->session()->regenerate();
            
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'access_code' => __('template.invalid_access_code'),
        ])->onlyInput('access_code');
    }

    /**
     * Log the client out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->session()->forget('client_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

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
        $clientId = $request->session()->get('client_id');
        $client = $clientId ? Client::find($clientId) : null;

        return response()->json([
            'client' => $client
        ]);
    }
}
