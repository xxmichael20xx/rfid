<?php

use App\Http\Controllers\ActivitiesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeOwnerController;
use App\Http\Livewire\Activity\ActivityCreate;
use App\Http\Livewire\Activity\ActivityUpdate;
use App\Http\Livewire\Admin\LoginActivity;
use App\Http\Livewire\Admin\Report\AdminReportActivity;
use App\Http\Livewire\Admin\Report\AdminReportExpenses;
use App\Http\Livewire\Admin\Report\AdminReportPayment;
use App\Http\Livewire\Admin\Report\AdminReportRfid;
use App\Http\Livewire\Admin\Report\AdminReportVisitor;
use App\Http\Livewire\BlockManagement\BlockManagementList;
use App\Http\Livewire\BlockManagement\BlockManagementCreate;
use App\Http\Livewire\Guard\GuardDashboard;
use App\Http\Livewire\Guard\Rfid\GuardRfidMonitoring;
use App\Http\Livewire\Guard\Visitor\GuardVisitorList;
use App\Http\Livewire\Guard\Visitor\GuardVisitorMonitoring;
use App\Http\Livewire\Homeowner\HomeownerCreate;
use App\Http\Livewire\Homeowner\HomeownerUpdate;
use App\Http\Livewire\Homeowner\HomeownerView;
use App\Http\Livewire\Payments\PaymentsExpenses;
use App\Http\Livewire\Payments\PaymentsList;
use App\Http\Livewire\Payments\PaymentsOverview;
use App\Http\Livewire\Payments\PaymentsTypes;
use App\Http\Livewire\Profile\ProfileList;
use App\Http\Livewire\Rfid\RfidList;
use App\Http\Livewire\Rfid\RfidMonitoring;
use App\Http\Livewire\RfidPanel;
use App\Http\Livewire\UserManagement\UserManagement;
use App\Http\Livewire\Visitor\VisitorMonitoring;
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

/** Route for testing */
Route::get('test', [TestController::class, 'template']);

/** Define login page */
Route::get('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth.admin')->group(function() {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /** Define login activities */
    Route::get('/login-activities', LoginActivity::class)->name('login.activities');

    /** Define dashboard page */
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    /** Define homeowners pages */
    Route::name('homeowners.')->prefix('homeowners')->group(function() {
        Route::get('/', [HomeOwnerController::class, 'list'])->name('list');
        Route::get('new', HomeownerCreate::class)->name('create');
        Route::get('update/{id}', HomeownerUpdate::class)->name('update');
        Route::get('view/{id}', HomeownerView::class)->name('view');
    });


    /** Define profiles pages */
    Route::name('profiles.')->prefix('profiles')->group(function() {
        Route::get('/', ProfileList::class)->name('list');
    });


    /** Define activities pages */
    Route::name('activities.')->prefix('activities')->group(function() {
        Route::get('/', [ActivitiesController::class, 'list'])->name('list');
        Route::get('new', ActivityCreate::class)->name('create');
        Route::get('update/{id}', ActivityUpdate::class)->name('update');
    });

    /** Define block management pages */
    Route::name('block-management.')->prefix('block-management')->group(function() {
        Route::get('/', BlockManagementList::class)->name('list');
        Route::get('create', BlockManagementCreate::class)->name('create');
    });

    /** Define RFID pages */
    Route::name('rfid.')->prefix('rfid')->group(function() {
        Route::get('/', RfidList::class)->name('list');
        Route::get('/monitoring', RfidMonitoring::class)->name('monitoring');
    });

    /** Define User Management pages */
    Route::name('user-management.')->prefix('user-management')->group(function() {
        Route::get('{type}', UserManagement::class)->name('index');
    });

    /** Define User Management pages */
    Route::name('visitor-monitoring.')->prefix('visitor-monitoring')->group(function() {
        Route::get('/', VisitorMonitoring::class)->name('index');
    });
});

Route::middleware('auth.admin_or_treasurer')->group(function() {
    /** Define Payments pages */
    Route::name('payments.')->prefix('payments')->group(function() {
        Route::get('overview', PaymentsOverview::class)->name('overview');
        Route::get('expenses', PaymentsExpenses::class)->name('expenses');
        Route::get('list', PaymentsList::class)->name('list');
        Route::get('types', PaymentsTypes::class)->name('types');
    });
});

Route::middleware('auth.guard')->group(function() {
    /** Define Guard route */
    Route::name('guard.')->prefix('guard')->group(function() {
        Route::get('/dashboard', GuardDashboard::class)->name('dashboard');

        /** Define RFID monitoring routes */
        Route::name('rfid-monitoring.')->group(function() {
            Route::get('/rfid-monitoring', GuardRfidMonitoring::class)->name('index');
        });

        /** Define Visitor monitoring routes */
        Route::name('visitors.')->prefix('visitors')->group(function() {
            Route::get('monitoring', GuardVisitorMonitoring::class)->name('monitoring');
            Route::get('list', GuardVisitorList::class)->name('list');
        });
    });
});

/** Define Reports */
Route::middleware('auth.reports')->name('reports.')->prefix('reports')->group(function() {
    /** Activities */
    Route::get('activity', AdminReportActivity::class)->name('activity');

    /** Activities Print */
    Route::get('print/activities', [AdminReportController::class, 'activities'])->name('print.activities');

    /** Expenses */
    Route::get('expenses', AdminReportExpenses::class)->name('expenses');

    /** Expenses Print */
    Route::get('print/expenses', [AdminReportController::class, 'expenses'])->name('print.expenses');

    /** Payments */
    Route::get('payments', AdminReportPayment::class)->name('payments');

    /** Payments Print */
    Route::get('print/payments', [AdminReportController::class, 'payments'])->name('print.payments');

    /** Visitors */
    Route::get('visitors', AdminReportVisitor::class)->name('visitors');

    /** Visitors Print */
    Route::get('print/visitors', [AdminReportController::class, 'visitors'])->name('print.visitors');

    /** RFID Monitoring */
    Route::get('rfid-monitorings', AdminReportRfid::class)->name('rfid-monitorings');

    /** RFID Print */
    Route::get('print/rfids', [AdminReportController::class, 'rfids'])->name('print.rfids');
});

/** RFID Panel - Guests */
Route::get('rfid-panel', RfidPanel::class)->name('rfid.panel');
