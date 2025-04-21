<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\AuthCustomController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/contact', [ContactController::class, 'index'])
        ->name('contact.default');

Route::get('/ping', function (Request $request){
    return response()->json(['message' => 'Pong']);
});

// Authentication routes
Route::post('/login', [AuthCustomController::class, 'login']);
Route::post('/logout', [AuthCustomController::class, 'logout']);
Route::get('/client', [AuthCustomController::class, 'client']);