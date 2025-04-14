<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function view(){
        return view('pages.account');
    }

    public function updatePassword(Request $request){
        $user = auth()->user();

        if(!session()->has('AccountDetailsPage')){
            session()->put('AccountDetailsPage', true);
        }

        if($request->name !== null && $request->password !== null){
            $request->validate([
                'name' => ['string', 'max:255', 'regex:/^[\p{L} ]+$/u'],
                'password' => ['string','min:8', 'max:64'],
                'new_password' => ['string', 'confirmed', 'min:8', 'max:64'],
            ]);
            if ( $request->password !== null && !Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => 'Текущий пароль введён неверно.']);
            }

            $user->update([
                'name' => $request->name,
                'password' => Hash::make($request->new_password),
            ]);

            #TODO:: СДЕЛАТЬ ЧЕРЕЗ TOASTRJS ВЫПЛЫВАЮЩИЕ УВЕДОМЛЕНИЯ 

        } else {
            $request->validate([
                'name' => ['string', 'max:255', 'min:6', 'regex:/^[\p{L} ]+$/u'],
            ]);

            $user->update([
                'name' => $request->name,
            ]);
        }
        
        return back()->with('success', __('template.data_updated_successfully'));
    }
}