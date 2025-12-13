<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Collaboration\Company;
use App\Models\User\User;
use App\Models\Project\Task;
use App\Models\Project\Project;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/',function (){
    $task = Project::find(1);
    dd($task->creator->projects);
});
