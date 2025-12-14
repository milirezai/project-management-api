<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TestApiController;

Route::get('/milad',[TestApiController::class,'index']);





