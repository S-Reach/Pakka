<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ContentModerationController;
use App\Http\Controllers\ReportedContentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\WriterEarningsController;
use App\Http\Controllers\Auth\AdminForgotPasswordController;
use App\Http\Controllers\AccountSettingsController;

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

// Authentication Routes
Route::get('/', [AdminLoginController::class, 'showLogin'])->name('login');
Route::post('/', [AdminLoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

//admin forgot password
Route::get('/admin/forgot-password', [AdminForgotPasswordController::class, 'showForgotForm'])
    ->name('admin.password.request');
Route::post('/admin/forgot-password', [AdminForgotPasswordController::class, 'sendResetLink'])
    ->name('admin.password.email');
Route::get('/admin/reset-password/{token}', [AdminForgotPasswordController::class, 'showResetForm'])
    ->name('admin.password.reset'); // ✅ FIXED
Route::post('/admin/reset-password', [AdminForgotPasswordController::class, 'reset'])
    ->name('admin.password.update');

// Admin Dashboard Route
Route::get('/admindashboard', [AdminDashboardController::class, 'index'])
    ->name('admindashboard');

// Content Moderation Route
Route::get('/contentmoderation', [ContentModerationController::class, 'index'])
    ->name('contentmoderation');
Route::get('/contentmoderation/show/{id}',[ContentModerationController::class, 'show'])
    ->name('contentmoderation.show');
Route::post('/contentmoderation/{id}/approve', [ContentModerationController::class, 'approve'])
    ->name('contentmoderation.approve');
Route::post('/contentmoderation/{id}/reject', [ContentModerationController::class, 'reject'])
    ->name('contentmoderation.reject');

// Reported Content Route
Route::get('/reportedcontent', [ReportedContentController::class, 'index'])
    ->name('reportedcontent');
Route::patch('/reports/{type}/{id}', [ReportedContentController::class, 'updateReportStatus'])
    ->name('reports.update');
Route::post('/reports/notify/{type}/{id}', [ReportedContentController::class, 'notifyWriter'])
    ->name('reports.notify');
Route::patch('/reports/{type}/{id}/hide', [ReportedContentController::class, 'hideContent'])
    ->name('reports.hide');
Route::patch('/reports/{type}/{id}/unhide', [ReportedContentController::class, 'unhideContent'])
    ->name('reports.unhide');

// Admin Payments Route
Route::get('/paymentverification', [App\Http\Controllers\PaymentController::class, 'index'])
    ->name('paymentverification');
Route::get('/paymentverification/{id}', [App\Http\Controllers\PaymentController::class, 'show'])
    ->name('payment.show');

// Writer Earnings Route
Route::get('/writerearnings', [App\Http\Controllers\WriterEarningsController::class, 'writerEarnings'])
    ->name('writerearnings');
Route::post('/withdraw/{id}/paid', [App\Http\Controllers\WriterEarningsController::class, 'markAsPaid'])
    ->name('withdraw.paid');

// User Management Route
Route::get('/usermanagement', [App\Http\Controllers\UserManagementController::class, 'index'])
    ->name('usermanagement');
Route::get('/user/{id}', [UserManagementController::class, 'show'])
    ->name('users.show');
Route::post('/user/warn/{id}', [UserManagementController::class, 'warn']);
Route::post('/user/suspend/{id}', [UserManagementController::class, 'suspend']);
Route::post('/user/activate/{id}', [UserManagementController::class, 'activate']);

// Admin setting
Route::prefix('admin')->middleware('auth:admin')->group(function () {

    Route::get('/account-settings', [AccountSettingsController::class, 'index'])
        ->name('account.settings');

    Route::post('/account-settings/update', [AccountSettingsController::class, 'update'])
        ->name('account.update');

    Route::post('/account-settings/password', [AccountSettingsController::class, 'changePassword'])
        ->name('account.password');
});