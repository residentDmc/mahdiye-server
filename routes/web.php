<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LeaderController;
use App\Http\Controllers\Web\ReserveController;
use App\Http\Controllers\Web\SettingController;
use App\Http\Controllers\Web\UserController;
use App\Http\Middleware\CheckUserRole;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('dashboard');
});
Route::group(['middleware' => 'auth', 'prefix' => 'dashboard'], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::group(['middleware' => CheckUserRole::class], function () {
        Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::get('/reserve/create-multiple', [ReserveController::class, 'createMultiple'])->name('reserve.create-multiple');
        Route::post('/reserve/store-multiple', [ReserveController::class, 'storeMultiple'])->name('reserve.store-multiple');
        Route::resource('reserve', ReserveController::class);
        Route::delete('appointment/{id}/destroy', [ReserveController::class, 'destroyAppointment'])->name('reserve.destroy-appointment');
        Route::post('appointment/change-status', [ReserveController::class, 'changeAppointmentStatus'])->name('change-status-appointment');
        Route::post('appointment/get-summary', [ReserveController::class, 'appointmentSummary'])->name('appointment-summary');
        Route::get('appointment/all', [ReserveController::class, 'allAppointments'])->name('all-appointments');
        Route::get('settings/privacy-policy/edit', [SettingController::class, 'privacyPolicy']);
        Route::post('settings/privacy-policy/edit', [SettingController::class, 'storePrivacyPolicy']);
    });
});

Route::get('login', [AuthController::class, 'loginPage'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('do-login');
Route::get('/run-command', [Controller::class, 'runCommand']);
