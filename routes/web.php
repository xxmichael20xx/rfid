<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeOwnerController;
use App\Http\Livewire\Activity\ActivityCreate;
use App\Http\Livewire\Activity\ActivityUpdate;
use App\Http\Livewire\BlockManagement\BlockManagementList;
use App\Http\Livewire\BlockManagement\BlockManagementCreate;
use App\Http\Livewire\Guard\Rfid\GuardRfidMonitoring;
use App\Http\Livewire\Homeowner\HomeownerCreate;
use App\Http\Livewire\Homeowner\HomeownerUpdate;
use App\Http\Livewire\Homeowner\HomeownerView;
use App\Http\Livewire\Payments\PaymentsExpenses;
use App\Http\Livewire\Payments\PaymentsList;
use App\Http\Livewire\Payments\PaymentsOverview;
use App\Http\Livewire\Payments\PaymentsRecurring;
use App\Http\Livewire\Payments\PaymentsTypes;
use App\Http\Livewire\Profile\ProfileList;
use App\Http\Livewire\Rfid\RfidList;
use App\Http\Livewire\Rfid\RfidMonitoring;
use App\Http\Livewire\UserManagement\UserManagement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Notes:
// added `name` and `prefix` to organize the routes

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

/** Define template (for testing) */
Route::get('template', [TestController::class, 'template']);

/** Define login page */
Route::get('/login', [AuthController::class, 'login'])->name('login');

/** Define dashboard page */
Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

/** Define homeowners pages */
Route::name('homeowners.')
    ->prefix('homeowners')
    ->group(function() {
        Route::get('/', [HomeOwnerController::class, 'list'])->name('list');
        Route::get('new', HomeownerCreate::class)->name('create');
        Route::get('update/{id}', HomeownerUpdate::class)->name('update');
        Route::get('view/{id}', HomeownerView::class)->name('view');
    });


/** Define profiles pages */
Route::name('profiles.')
    ->prefix('profiles')
    ->group(function() {
        Route::get('/', ProfileList::class)->name('list');
    });


/** Define activities pages */
Route::name('activities.')
    ->prefix('activities')
    ->group(function() {
        Route::get('/', [ActivitiesController::class, 'list'])->name('list');
        Route::get('new', ActivityCreate::class)->name('create');
        Route::get('update/{id}', ActivityUpdate::class)->name('update');
    });

/** Define block management pages */
Route::name('block-management.')
    ->prefix('block-management')
    ->group(function() {
        Route::get('/', BlockManagementList::class)->name('list');
        Route::get('create', BlockManagementCreate::class)->name('create');
    });

/** Define RFID pages */
Route::name('rfid.')
    ->prefix('rfid')
    ->group(function() {
        Route::get('/', RfidList::class)->name('list');
        Route::get('/monitoring', RfidMonitoring::class)->name('monitoring');
    });

/** Define Payments pages */
Route::name('payments.')
    ->prefix('payments')
    ->group(function() {
        Route::get('overview', PaymentsOverview::class)->name('overview');
        Route::get('expenses', PaymentsExpenses::class)->name('expenses');
        Route::get('list', PaymentsList::class)->name('list');
        Route::get('types', PaymentsTypes::class)->name('types');
    });

/** Define User Management pages */
Route::name('user-management.')
    ->prefix('user-management')
    ->group(function() {
        Route::get('/', UserManagement::class)->name('index');
    });

/** Define Guard route */
Route::name('guard.')
    ->prefix('guard')
    ->group(function() {
        Route::name('rfid-monitoring.')
            ->group(function() {
                Route::get('/rfid-monitoring', GuardRfidMonitoring::class)->name('index');
            });
    });