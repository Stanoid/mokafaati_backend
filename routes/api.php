<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Middleware\Admin;
use App\Http\Controllers\BillController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\TestController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//get offer by id
// get



Route::get('user/login', [UserController::class, 'index'])->name("login");

Route::post('test/index', [TestController::class, 'index']);


Route::post('user/login', [UserController::class, 'auth']);
Route::post('user/register', [UserController::class, 'store']);
Route::get('wallet/reset', [WalletController::class, 'resetDB']);
Route::get('wallet/reset/', [WalletController::class, 'resetDB']);
Route::post('user/login', [UserController::class, 'auth']);
Route::post('user/register', [UserController::class, 'store']);
Route::get('offer/list', [OfferController::class, 'index']);
Route::get('offer/{id}', [OfferController::class, 'show']);




Route::middleware('auth:sanctum')->group(function () {

    Route::middleware([Admin::class])->group(function () {
        Route::get('wallet/deposit/{id}', [WalletController::class, 'deposit']);
        Route::get('wallet/refresh/{id}', [WalletController::class, 'refresh']);
        Route::post('wallet/reset/', [WalletController::class, 'resetDB']);
        Route::post('wallet/decrypt/', [WalletController::class, 'decrypt']);
Route::post('send-fcm-notification', [FcmController::class, 'sendFcmNotification']);




    });

Route::get('wallet/balance', [WalletController::class, 'balance']);

Route::get('wallet/balanceOnly', [WalletController::class, 'balanceOnly']);
Route::post('user/update-device-token', [FcmController::class, 'updateDeviceToken']);
Route::get('wallet/transfer/{amount}/{to}',[WalletController::class, 'transfer']);
Route::post('bill/scan', [BillController::class, 'store']);
Route::get('bill/list', [BillController::class, 'list']);
Route::post('bill/get', [BillController::class, 'get']);
Route::post('bill/devide', [BillController::class, 'devide']);



});


