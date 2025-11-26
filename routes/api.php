<?php

use App\Http\Controllers\Facility\HomeController as FacilityHomeController;
use App\Http\Controllers\Facility\ShiftController;
use App\Http\Controllers\User\Api\AuthController;
use App\Http\Controllers\User\Api\HomeController;
use App\Http\Controllers\User\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\Api\ExperienceController;
use App\Http\Controllers\User\Api\TravelerController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('verify-email', 'verifyEmail');
        Route::post('resend-otp', 'resendOtp');
        Route::post('forget-password', 'forgetPassword');
        Route::post('login', 'login');
    });
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::controller(ProfileController::class)->group(function () {
        Route::post('/profile/update', 'updateProfile');
        Route::post('/profile/change/password', 'changePassword');
    });

    Route::post('/update/role', [AuthController::class, 'updateRole']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/change-email-request', [AuthController::class, 'requestEmailChange']);
    Route::post('/change-email-confirm', [AuthController::class, 'confirmEmailChange']);
    Route::post('/user/details/store', [AuthController::class, 'storeUserDetails']);
    Route::post('/user/details/update', [AuthController::class, 'updateUserDetails']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware(['auth:sanctum', 'agency.mode'])->prefix('agency')->group(function () {
    Route::get('/experience', [ExperienceController::class, 'index']);
    Route::post('/experience', [ExperienceController::class, 'store']);
    Route::get('/experience/{id}', [ExperienceController::class, 'show']);
    Route::post('/experience/{id}/update', [ExperienceController::class, 'update']);
    Route::delete('/experience/{id}/delete', [ExperienceController::class, 'destroy']);
    Route::post('/experience/{id}/status', [ExperienceController::class, 'changeStatus']);
});

Route::middleware(['auth:sanctum', 'guide.mode'])->prefix('guide')->group(function () {
    Route::get('/experience', [ExperienceController::class, 'index']);
    Route::post('/experience', [ExperienceController::class, 'store']);
    Route::get('/experience/{id}', [ExperienceController::class, 'show']);
    Route::post('/experience/{id}/update', [ExperienceController::class, 'update']);
    Route::delete('/experience/{id}/delete', [ExperienceController::class, 'destroy']);
    Route::post('/experience/{id}/status', [ExperienceController::class, 'changeStatus']);
});


Route::middleware(['auth:sanctum', 'traveler.mode'])->prefix('traveler')->group(function () {
    Route::get('/get/experience', [TravelerController::class, 'getExperiences']);
    Route::get('/get/experience/{id}', [TravelerController::class, 'getExperienceDetails']);
    Route::post('/get/filtered/experiences', [TravelerController::class, 'getFilteredExperiences']);
    Route::post('/rate/experience', [TravelerController::class, 'addRating']);

    Route::get('/get/agencies', [TravelerController::class, 'getAgencies']);
    Route::get('/get/agency/{id}', [TravelerController::class, 'getAgencyDetails']);

});