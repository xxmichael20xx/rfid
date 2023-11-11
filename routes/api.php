<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\ApiHomeOwnerController;
use App\Http\Controllers\ApiLoginController;
use App\Http\Controllers\QRController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/** API for Home Owner Login */
Route::post('user/login', [ApiLoginController::class, 'login']);

/** API for generating and downloading a QR */
Route::get('download-qr', [QRController::class, 'downloadQr'])->name('download.qr');

/** API for adding and generator QR code */
Route::middleware('auth:sanctum')
    ->group(function() {
        Route::post('app/download-qr', [QRController::class, 'downloadAppQr'])->name('download.qr');
    });

Route::middleware('auth:sanctum')
    ->group(function() {
        /** API for Activities */
        Route::prefix('activities')
            ->group(function() {
                Route::get('all', [ActivitiesController::class, 'all']);
                Route::get('today', [ActivitiesController::class, 'today']);
                Route::get('search/{s}', [ActivitiesController::class, 'search']);
            });

        /** API for Homw Owner - Visitor */
        Route::prefix('qr')
            ->group(function() {
                // List the visitors
                Route::get('visitor/list', [ApiHomeOwnerController::class, 'visitorList']);

                // Create new visitor
                Route::post('visitor/add', [ApiHomeOwnerController::class, 'visitorAdd']);

                // Download the QR for the vistor
                Route::post('download', [ApiHomeOwnerController::class, 'downloadQr']);
            });
    });
