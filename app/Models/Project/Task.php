<?php

namespace App\Models\Project;

use App\Models\Collaboration\Comment;
use App\Models\Collaboration\File;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'user_id', 'start_data', 'end_date', 'status', 'project_id', 'priority'];

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function userAssign()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function files()
    {
        return $this->morphMany(File::class,'fileable');
    }

}
