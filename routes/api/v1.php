<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Collaboration\CompanyController;
use App\Http\Controllers\Api\V1\Project\ProjectController;
use App\Http\Controllers\Api\V1\V1Controller;
use App\Http\Controllers\Api\V1\Project\TaskController;
use App\Http\Controllers\Api\V1\Collaboration\FileController;
use App\Http\Controllers\Api\V1\Collaboration\CommentController;

// for swagger
Route::get('/',[V1Controller::class,'index']);

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

// task
Route::apiResource('tasks',TaskController::class)->middleware('auth:sanctum');

// file
Route::apiResource('files',FileController::class)->middleware('auth:sanctum');

// comment
Route::apiResource('comments',CommentController::class)->middleware('auth:sanctum');
