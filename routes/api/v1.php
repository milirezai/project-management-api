<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Collaboration\CompanyController;
use App\Http\Controllers\Api\V1\Project\ProjectController;


// auth
Route::prefix('auth')->group(function (){
    Route::post('/',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
});

// company
Route::apiResource('companies',CompanyController::class)->middleware('auth:sanctum');

// project
Route::apiResource('projects',ProjectController::class)->middleware('auth:sanctum');
