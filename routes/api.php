<?php

use App\Http\Controllers\API\V1\AppointmentController;
use App\Http\Controllers\API\V1\Auth\AuthenticationController;
use App\Http\Controllers\API\V1\GeneralController;
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

Route::group(['prefix' => 'v1'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/send-code', [AuthenticationController::class, 'sendVerificationCode']);
        Route::post('/login', [AuthenticationController::class, 'login']);
        Route::post('/register', [AuthenticationController::class, 'updateUserInformation'])->middleware('auth:sanctum');
        Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware('auth:sanctum');
    });

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/get-appointment', [AppointmentController::class, 'getAppointment']);
        Route::post('/appointments', [AppointmentController::class, 'appointments']);
    });

    Route::group(['prefix' => 'general'], function () {
        Route::post('/privacy-policy', [GeneralController::class, 'privacyPolicy']);
        Route::post('/my-reserves', [GeneralController::class, 'userAppointments'])->middleware('auth:sanctum');
    });

});
