<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

Route::get('/', function () {
    return view('welcome');
});


Route::middleware('auth')->prefix('/dashboard')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // Customers
    Route::get('/customers', [\App\Http\Controllers\CustomerController::class, 'index'])
        ->name('customers');
    Route::post('/customers', [\App\Http\Controllers\CustomerController::class, 'indexData']);

    Route::get('/customers/create', [\App\Http\Controllers\CustomerController::class, 'create'])
        ->name('customers.create');
    Route::post('/customers/create', [\App\Http\Controllers\CustomerController::class, 'store']);

    Route::get('/customers/{customer}', [\App\Http\Controllers\CustomerController::class, 'show'])
        ->name('customers.show');

    Route::get('/customers/{customer}/edit', [\App\Http\Controllers\CustomerController::class, 'edit'])
        ->name('customers.edit');
    Route::post('/customers/{customer}/edit', [\App\Http\Controllers\CustomerController::class, 'update']);

    Route::post('/customers/{customer}/changeStatus', [\App\Http\Controllers\CustomerController::class, 'changeStatus'])
        ->name('customers.changeStatus');

    Route::post('/customers/{customer}/makePayment', [\App\Http\Controllers\CustomerController::class, 'makePayment'])
        ->name('customers.makePayment');

    Route::delete('/customers/{customer}/delete', [\App\Http\Controllers\CustomerController::class, 'destroy'])
        ->name('customers.delete');

    // Packages
    Route::get('/packages', [\App\Http\Controllers\PackageController::class, 'index'])
        ->name('packages');
    Route::post('/packages', [\App\Http\Controllers\PackageController::class, 'indexData']);
    Route::get('/packages/create', [\App\Http\Controllers\PackageController::class, 'create'])
        ->name('packages.create');
    Route::post('/packages/create', [\App\Http\Controllers\PackageController::class, 'store']);

    Route::get('/packages/{package}', [\App\Http\Controllers\PackageController::class, 'show'])
        ->name('packages.show');

    Route::get('/packages/{package}/edit', [\App\Http\Controllers\PackageController::class, 'edit'])
        ->name('packages.edit');
    Route::post('/packages/{package}/edit', [\App\Http\Controllers\PackageController::class, 'update']);
    Route::delete('/packages/{package}/delete', [\App\Http\Controllers\PackageController::class, 'destroy'])
        ->name('packages.delete');

    // Area
    Route::get('/areas', [\App\Http\Controllers\AreaController::class, 'index'])
        ->name('areas');
    Route::post('/areas', [\App\Http\Controllers\AreaController::class, 'indexData']);
    Route::get('/areas/create', [\App\Http\Controllers\AreaController::class, 'create'])
        ->name('areas.create');
    Route::post('/areas/create', [\App\Http\Controllers\AreaController::class, 'store']);

    Route::get('/areas/{area}/edit', [\App\Http\Controllers\AreaController::class, 'edit'])
        ->name('areas.edit');
    Route::post('/areas/{area}/edit', [\App\Http\Controllers\AreaController::class, 'update']);
    Route::delete('/areas/{area}/delete', [\App\Http\Controllers\AreaController::class, 'destroy'])
        ->name('areas.delete');

    // Connection Points
    Route::get('/connection_points', [\App\Http\Controllers\ConnectionPointController::class, 'index'])
        ->name('connection_points');
    Route::post('/connection_points', [\App\Http\Controllers\ConnectionPointController::class, 'indexData']);
    Route::get('/connection_points/create', [\App\Http\Controllers\ConnectionPointController::class, 'create'])
        ->name('connection_points.create');
    Route::post('/connection_points/create', [\App\Http\Controllers\ConnectionPointController::class, 'store']);

    Route::get('/connection_points/{connection_point}/edit', [\App\Http\Controllers\ConnectionPointController::class, 'edit'])
        ->name('connection_points.edit');
    Route::post('/connection_points/{connection_point}/edit', [\App\Http\Controllers\ConnectionPointController::class, 'update']);
    Route::delete('/connection_points/{connection_point}/delete', [\App\Http\Controllers\ConnectionPointController::class, 'destroy'])
        ->name('connection_points.delete');

    // Invoices
    Route::get('/invoices', [\App\Http\Controllers\InvoiceController::class, 'index'])
        ->name('invoices');
    Route::post('/invoices', [\App\Http\Controllers\InvoiceController::class, 'indexData']);
    Route::get('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'show'])
        ->name('invoices.show');
    Route::post('/invoices/{invoice}/pay', [\App\Http\Controllers\InvoiceController::class, 'pay'])
        ->name('invoices.pay');
    Route::delete('/invoices/{invoice}/cancel', [\App\Http\Controllers\InvoiceController::class, 'destroy'])
        ->name('invoices.cancel');

    // Payments
    Route::get('/payments', [\App\Http\Controllers\PaymentController::class, 'index'])
        ->name('payments');
    Route::post('/payments', [\App\Http\Controllers\PaymentController::class, 'indexData']);
    Route::get('/payments/{payment}', [\App\Http\Controllers\PaymentController::class, 'show'])
        ->name('payments.show');
    Route::get('/payments/{payment}/print', [\App\Http\Controllers\PaymentController::class, 'printOut'])
        ->name('payments.print');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

