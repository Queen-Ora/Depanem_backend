<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

route::prefix('depanem')->group(function () {
    Route::post('registerUser', [AuthController::class, 'registerUser']);
   
    
   
});