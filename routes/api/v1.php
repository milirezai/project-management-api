<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\TestApiController;
use App\Models\User\User;
use App\Models\Collaboration\Comment;
use App\Models\Collaboration\File;
use App\Models\Project\Project;
use App\Models\Project\Task;


Route::get('/milad',function (){


    $task = Task::find(1);
    dd(
        $task->files
    );

});





