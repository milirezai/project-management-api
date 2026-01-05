<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Collaboration\CompanyController;
use App\Http\Controllers\Api\V1\Project\ProjectController;
use App\Http\Controllers\Api\V1\V1Controller;
use App\Http\Controllers\Api\V1\Project\TaskController;
use App\Http\Controllers\Api\V1\Collaboration\FileController;
use App\Http\Controllers\Api\V1\Collaboration\CommentController;
use App\Http\Controllers\Api\V1\User\UserController;
use App\Http\Controllers\Api\V1\User\RoleController;
use App\Http\Controllers\Api\V1\User\PermissionController;

// for swagger
Route::get('/',[V1Controller::class,'index']);

// auth
Route::middleware('throttle:limiter')->prefix('auth')->group(function (){
    Route::post('/',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');
});

// user
Route::apiResource('users',UserController::class)->middleware(['auth:sanctum','throttle:limiter']);
Route::middleware(['auth:sanctum','throttle:limiter'])->controller(UserController::class)
    ->prefix('users')->group(function (){
        Route::get('/{user}/roles','roles');
        Route::post('/{user}/roles','syncRoles');
    });

// role
Route::middleware(['auth:sanctum','throttle:limiter'])->controller(RoleController::class)
    ->prefix('roles')->group(function (){
        Route::get('/','index');
        Route::get('/{role}','show');
        Route::get('/{role}/permissions','getPermissions')->can('view','role');
        Route::put('/{role}/permissions','syncPermissions')->can('update','role');
    });

// permissions
Route::middleware(['auth:sanctum','throttle:limiter'])->controller(PermissionController::class)
    ->prefix('permissions')->group(function (){
        Route::get('/','index');
    });

// company
Route::apiResource('companies',CompanyController::class)->middleware(['auth:sanctum','throttle:limiter']);

// project
Route::apiResource('projects',ProjectController::class)->middleware(['auth:sanctum','throttle:limiter']);

// task
Route::apiResource('tasks',TaskController::class)->middleware(['auth:sanctum','throttle:limiter']);

// file
Route::apiResource('files',FileController::class)->middleware(['auth:sanctum','throttle:limiter']);

// comment
Route::apiResource('comments',CommentController::class)->middleware(['auth:sanctum','throttle:limiter']);

