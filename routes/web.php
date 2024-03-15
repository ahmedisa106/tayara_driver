<?php

use App\Services\Fcm\Fcm;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('fcm', function ($id) {

    $res =  Fcm::sendToTokens(
        ["fmmUO22JRqGjY8_cucvZj2:APA91bF_CeCBRlMJwSMmioklCjZTKRvhUWpkwekPzg7-nixjnFng7u2W3akdgJYe0kZIomnaAZqpHZbbdh97IdXzxGkn9jU0d4jatvjN8wqgqo0_ivF7L8YMw32V27G2aVEymCSP_Y_H"],
        env('APP_NAME'),
        "test message body"
    );
    dd($res);
});
