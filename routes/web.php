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
    return redirect(route('login'));
});


Route::middleware('auth')->prefix('/dashboard')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

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

    Route::post('/customers/{customer}/makeInvoice', [\App\Http\Controllers\CustomerController::class, 'makeInvoice'])
        ->name('customers.makeInvoice');

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
    Route::post('/payments/{payment}/changeStatus', [\App\Http\Controllers\PaymentController::class, 'changeStatus'])
        ->name('payments.changeStatus');

    // Employees
    Route::get('/employees', [\App\Http\Controllers\EmployeeController::class, 'index'])
        ->name('employees');
    Route::post('/employees', [\App\Http\Controllers\EmployeeController::class, 'indexData']);

    Route::get('/employees/create', [\App\Http\Controllers\EmployeeController::class, 'create'])
        ->name('employees.create');
    Route::post('/employees/create', [\App\Http\Controllers\EmployeeController::class, 'store']);

    Route::get('/employees/{employee}', [\App\Http\Controllers\EmployeeController::class, 'show'])
        ->name('employees.show');

    Route::get('/employees/{employee}/edit', [\App\Http\Controllers\EmployeeController::class, 'edit'])
        ->name('employees.edit');
    Route::post('/employees/{employee}/edit', [\App\Http\Controllers\EmployeeController::class, 'update']);

    Route::post('/employees/{employee}/changeStatus', [\App\Http\Controllers\EmployeeController::class, 'changeStatus'])
        ->name('employees.changeStatus');

    Route::get('/employees/{employee}/changePassword', [\App\Http\Controllers\EmployeeController::class, 'changePasswordForm'])
        ->name('employees.changePassword');
    Route::post('/employees/{employee}/changePassword', [\App\Http\Controllers\EmployeeController::class, 'changePassword']);

    Route::delete('/employees/{employee}/delete', [\App\Http\Controllers\EmployeeController::class, 'destroy'])
        ->name('employees.delete');

    // Jobs
    Route::get('/jobs', [\App\Http\Controllers\JobController::class, 'index'])
        ->name('jobs');
    Route::post('/jobs', [\App\Http\Controllers\JobController::class, 'indexData']);
    Route::get('/jobs/create', [\App\Http\Controllers\JobController::class, 'create'])
        ->name('jobs.create');
    Route::post('/jobs/create', [\App\Http\Controllers\JobController::class, 'store']);
    Route::get('/jobs/{job}', [\App\Http\Controllers\JobController::class, 'show'])
        ->name('jobs.show');
    Route::get('/jobs/{job}/edit', [\App\Http\Controllers\JobController::class, 'edit'])
        ->name('jobs.edit');
    Route::post('/jobs/{job}/edit', [\App\Http\Controllers\JobController::class, 'update']);
    Route::delete('/jobs/{job}/delete', [\App\Http\Controllers\JobController::class, 'destroy'])
        ->name('jobs.delete');

    // SMS
    Route::get('/sms/create', [\App\Http\Controllers\SMSController::class, 'create'])
        ->name('sms.create');
    Route::post('/sms/create', [\App\Http\Controllers\SMSController::class, 'store']);

    // Profile
    Route::get('/profile', [\App\Http\Controllers\DashboardController::class, 'userProfile'])
        ->name('profile');
    Route::post('/profile', [\App\Http\Controllers\DashboardController::class, 'userProfileStore']);

    Route::get('/profile/changePassword', [\App\Http\Controllers\DashboardController::class, 'changePasswordForm'])
        ->name('profile.changePassword');
    Route::post('/profile/changePassword', [\App\Http\Controllers\DashboardController::class, 'changePassword']);

    Route::get('/profile/changePin', [\App\Http\Controllers\DashboardController::class, 'changePinForm'])
        ->name('profile.changePin');
    Route::post('/profile/changePin', [\App\Http\Controllers\DashboardController::class, 'changePin']);

    // Statistics
    Route::get('/statistics', [\App\Http\Controllers\StatisticsController::class, 'index'])
        ->name('statistics');
    Route::post('/statistics', [\App\Http\Controllers\StatisticsController::class, 'indexData']);
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


Route::middleware('employee.guest')->group(function () {
    Route::get('/login_v2', [\App\Http\Controllers\EmployeeController::class, 'loginForm'])->name('employee_login');
    Route::post('/login_v2', [\App\Http\Controllers\EmployeeController::class, 'login']);
    Route::get('/logout_v2', [\App\Http\Controllers\EmployeeController::class, 'logout'])->name('employee_logout');
});

Route::middleware('employee.auth')->prefix('/dashboard_v2')->group(function () {
    Route::get('/', [\App\Http\Controllers\DashboardV2Controller::class, 'index'])->name('dashboard_v2');

    // Customers
    Route::get('/customers', [\App\Http\Controllers\CustomerController::class, 'indexEmployee'])
        ->name('employee_customers');
    Route::post('/customers', [\App\Http\Controllers\CustomerController::class, 'indexDataEmployee']);

    Route::get('/customers/create', [\App\Http\Controllers\CustomerController::class, 'createEmployee'])
        ->name('employee_customers.create');
    Route::post('/customers/create', [\App\Http\Controllers\CustomerController::class, 'storeEmployee']);

    Route::get('/customers/{customer}', [\App\Http\Controllers\CustomerController::class, 'showEmployee'])
        ->name('employee_customers.show');

    Route::post('/customers/{customer}/makePayment', [\App\Http\Controllers\CustomerController::class, 'makePaymentEmployee'])
        ->name('employee_customers.makePayment');

    // Payments
    Route::get('/payments', [\App\Http\Controllers\PaymentController::class, 'indexEm'])
        ->name('employee_payments');
    Route::post('/payments', [\App\Http\Controllers\PaymentController::class, 'indexDataEm']);
    Route::get('/payments/{payment}', [\App\Http\Controllers\PaymentController::class, 'showEm'])
        ->name('employee_payments.show');
    Route::get('/payments/{payment}/print', [\App\Http\Controllers\PaymentController::class, 'printOutEm'])
        ->name('employee_payments.print');

    // Invoice
    Route::get('/invoices', [\App\Http\Controllers\InvoiceController::class, 'indexEm'])
        ->name('employee_invoices');
    Route::post('/invoices', [\App\Http\Controllers\InvoiceController::class, 'indexDataEm']);
    Route::get('/invoices/{invoice}', [\App\Http\Controllers\InvoiceController::class, 'showEm'])
        ->name('employee_invoices.show');
    Route::post('/invoices/{invoice}/pay', [\App\Http\Controllers\InvoiceController::class, 'payEm'])
        ->name('employee_invoices.pay');

    // Jobs
    Route::get('/jobs', [\App\Http\Controllers\JobController::class, 'indexEm'])
        ->name('employee_jobs');
    Route::post('/jobs', [\App\Http\Controllers\JobController::class, 'indexDataEm']);

    Route::get('/jobs/{job}', [\App\Http\Controllers\JobController::class, 'showEm'])
        ->name('employee_jobs.show');
    Route::post('/jobs/{job}/markComplete', [\App\Http\Controllers\JobController::class, 'markCompleted'])
        ->name('employee_jobs.markComplete');
});
