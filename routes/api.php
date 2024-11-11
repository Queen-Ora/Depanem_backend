<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\ReqController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

route::post('/trial', function (Request $request){

    return response()->json(['message' => 'Hello World!'], 200);
    return response()->json('kam');
    // return "kam";
});
    

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
    Route::get('available/{id}', [AuthController::class, 'Avaibility']);  


    Route::post('saveOpinion/{id}', [OpinionController::class, 'PublishOpinion']);
    Route::get('getOpinions', [OpinionController::class, 'GetOpinions']);







   
    // php artisan serve --host 0.0.0.0 --port 8000
    // npm run dev:host
    // php artisan migrate:refresh --path=database/migrations/la migration.php
    
    
    
    Route::post('/SendRequest/{tech_id}/{user_id}', [ReqController::class, 'SendRequest']);
    Route::get('/GetRequestsByTechnician/{technician_id}', [ReqController::class, 'getRequestsByTechnician']);
    Route::put('UpdateRequest/{id}', [ReqController::class, 'updateRequest']);
    Route::get('CountCheckedRequests/{technician_id}', [ReqController::class, 'countCheckedRequests']);
    Route::get('AcceptService/{technician_id}/{user_id}', [ReqController::class, 'AcceptService']);
    Route::get('/GetRequestsByUser/{user_id}', [ReqController::class, 'getRequestsByUser']);
    Route::get('/RejectRequest/{request_id}/{user_id}', [ReqController::class, 'RejectRequest']);
    Route::get('/GetRejectRequests', [ReqController::class, 'getRejRequests']);
    Route::get('/FinishRequest/{request_id}/{user_id}', [ReqController::class, 'FinRequest']);
    Route::get('/GetFinRequest', [ReqController::class, 'getFinRequests']);






    Route::get('GetAllRequests/{tech_id}', [ReqController::class, 'GetAllRequests']);
    Route::get('GetRequest/{id}', [ReqController::class, 'GetRequest']);
    Route::put('AcceptRequest/{id}', [ReqController::class, 'AcceptRequest']);
    Route::put('DeclineRequest/{id}', [ReqController::class, 'DeclineRequest']);
    Route::get('GetRequestsByUser/{user_id}', [ReqController::class, 'getRequestsByUser']);

    
    





   
    
   
});
