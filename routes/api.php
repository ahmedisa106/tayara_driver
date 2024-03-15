<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\OrderController;
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
    Route::apiResource('shifts',ShiftController::class)->except(['delete','show']);
    Route::get('shifts/current', [ShiftController::class,'current']);
    Route::patch('shifts/{shift}',[ShiftController::class,'endShift']);

    //orders
    Route::apiResource('orders',OrderController::class)->only(['index','show','update']);
    Route::post('orders/{order}/attach',[OrderController::class,'attach']);
    Route::post('orders/{order}/attach-from-provider',[OrderController::class,'attachFromProvider']);
    Route::post('orders/{order}/cancel',[OrderController::class,'cancel']);
    Route::post('orders/{order}/complete',[OrderController::class,'complete']);


});
