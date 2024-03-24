<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\Shifts\ShiftController;
use App\Http\Controllers\Api\Shifts\OrderController;
use Illuminate\Support\Facades\Route;

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


Route::group(['middleware' => ['guest:sanctum']], function () {
    Route::post('login', [AuthController::class, 'login']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::delete('delete', [AuthController::class, 'deleteAccount']);

    //shifts

    Route::get('shifts/latest',[ShiftController::class,'latest']);
    Route::get('shifts/current', [ShiftController::class,'current']);
    Route::patch('shifts',[ShiftController::class,'endShift']);
    Route::apiResource('shifts',ShiftController::class)->except(['delete']);

    //orders
    Route::apiResource('orders',OrderController::class)->only(['index','show']);
    Route::post('orders/{order}/attach',[OrderController::class,'attach']);
    Route::post('orders/{order}/attach-from-provider',[OrderController::class,'attachFromProvider']);
    Route::post('orders/{order}/cancel',[OrderController::class,'cancel']);
    Route::post('orders/{order}/complete',[OrderController::class,'complete']);

    //notifications

    Route::apiResource('notifications',NotificationController::class)->only(['index','show']);


});
