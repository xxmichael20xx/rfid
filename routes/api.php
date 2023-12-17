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

/** API for forgot password */
Route::post('user/reset', [ApiLoginController::class, 'resetPassword']);

/** API for generating and downloading a QR */
Route::get('download-qr', [QRController::class, 'downloadQr'])->name('download.qr');

/** API for adding and generator QR code */
Route::middleware('auth:sanctum')
    ->group(function() {
        Route::post('app/download-qr', [QRController::class, 'downloadAppQr'])->name('app.download.qr');
    });

Route::middleware('auth:sanctum')
    ->group(function() {
        /** API for Account */
        Route::prefix('account')
            ->group(function() {
                // Get current user
                Route::get('get', [ApiHomeOwnerController::class, 'getCurrentUser']);

                // Update user
                Route::post('update', [ApiHomeOwnerController::class, 'updateCurrentUser']);

                // Logout current user
                Route::post('logout', [ApiHomeOwnerController::class, 'logoutUser']);

                // Get payments
                Route::get('payments', [ApiHomeOwnerController::class, 'payments']);
            });

        /** API for Activities */
        Route::prefix('activities')
            ->group(function() {
                Route::get('grouped', [ActivitiesController::class, 'grouped']);
                Route::get('all', [ActivitiesController::class, 'all']);
                Route::get('today', [ActivitiesController::class, 'today']);
                Route::get('search/{s}', [ActivitiesController::class, 'search']);
                Route::get('get/{id}', [ActivitiesController::class, 'get']);
            });

        /** API for HomeOwner - Visitor */
        Route::prefix('qr')
            ->group(function() {
                // List the visitors
                Route::get('visitor/list', [ApiHomeOwnerController::class, 'visitorList']);

                // Create new visitor
                Route::post('visitor/add', [ApiHomeOwnerController::class, 'visitorAdd']);

                // Download the QR for the visitor
                Route::post('download', [ApiHomeOwnerController::class, 'downloadQr']);
            });

        /** API for HomeOwner - Notifications */
        Route::prefix('notifications')
            ->group(function() {
                // List all
                Route::get('all', [ApiHomeOwnerController::class, 'notificationsAll']);

                // List unread notifications
                Route::get('unread', [ApiHomeOwnerController::class, 'notificationsUnread']);
            });

        /** API for Officers */
        Route::prefix('officers')
            ->group(function() {
                // List all
                Route::get('all', [ApiHomeOwnerController::class, 'officersAll']);
            });
    });
