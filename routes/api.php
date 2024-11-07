<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReqController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

route::prefix('depanem')->group(function () {
    Route::post('registerUser', [AuthController::class, 'registerUser']);
    Route::post('technicianRegister', [AuthController::class, 'TechnicianRegister']);
    Route::post('login', [AuthController::class, 'Login']);
    Route::get('users', [AuthController::class, 'listUsers']);
    Route::get('currentUser/{id}', [AuthController::class, 'CurrentUser']);
    Route::get('CountUsers', [AuthController::class, 'CountUsers']);
    Route::get('CountTechnicians', [AuthController::class, 'CountTechnicians']);
    Route::get('CheckIsTechnician/{id}', [AuthController::class, 'CheckIsTechnician']);
    Route::get('GetAllTechnicians', [AuthController::class, 'GetAllTechnicians']);
    //routes for Forgotten password  
    Route::post('forgot-password', [AuthController::class, 'Forgotten_password']);
    Route::post('VerifyOTP', [AuthController::class, 'Verify_otp']);
    Route::post('ResetPassword', [AuthController::class, 'Reset_password']);

    Route::post('editUser/{id}', [AuthController::class, 'UpdateUser']);
    Route::post('editTechnician/{id}', [AuthController::class, 'UpdateUser']);



    // Route::post('ResetPassword', [AuthController::class, 'ResetPassword']);
    // Route::put('ResetPassword/{token}', [AuthController::class, 'ResetPassword']);
    
    // Route::get('GetTechnician/{id}', [AuthController::class, 'GetTechnician']);
    
    // Route::get('GetLocation/{id}', [AuthController::class, 'GetLocalization']);
    // php artisan serve --host 0.0.0.0 --port 8000
    // npm run dev:host
    // php artisan migrate:refresh --path=database/migrations/la migration.php
    
    
    
    Route::post('/SendRequest/{tech_id}/{user_id}', [ReqController::class, 'SendRequest']);
    Route::get('/GetRequestsByTechnician/{technician_id}', [ReqController::class, 'getRequestsByTechnician']);
    Route::put('UpdateRequest/{id}', [ReqController::class, 'updateRequest']);
    Route::get('CountCheckedRequests/{technician_id}', [ReqController::class, 'countCheckedRequests']);


    Route::get('GetAllRequests/{tech_id}', [ReqController::class, 'GetAllRequests']);
    Route::get('GetRequest/{id}', [ReqController::class, 'GetRequest']);
    Route::put('AcceptRequest/{id}', [ReqController::class, 'AcceptRequest']);
    Route::put('DeclineRequest/{id}', [ReqController::class, 'DeclineRequest']);
    Route::get('GetRequestsByUser/{user_id}', [ReqController::class, 'getRequestsByUser']);

    
    





   
    
   
});
