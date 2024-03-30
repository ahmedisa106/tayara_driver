<?php

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

//Route::get('send', function () {
//    \App\Services\Fcm\Fcm::sendToTokens(
//        [
//            'fVneagexTIalFqWlpSBy_5:APA91bE_jsDxwkAak-MHAp6g5ah3x_e3kYtTXrGWfhEIosGjPLxn0IgmQbOy9txRdymmVpNEsKBjwY5r8fU4qBZW5mkP7Vzjsai31QSjSY7tnZwiHCK-GalYtrFyrQWZ0EJnNZSO0nUT'
//        ],
//        'welcome',
//        'welcome'
//    );
//});
