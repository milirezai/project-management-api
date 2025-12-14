<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;




// auth
Route::prefix('auth')->group(function (){
    Route::post('/',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);
    Route::post('/logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
});



