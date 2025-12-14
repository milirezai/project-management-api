<?php

namespace App\Models\Project;

use App\Models\Collaboration\Comment;
use App\Models\Collaboration\File;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'status', 'creator_id'];

    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'creator_id');
    }

    public function files()
    {
        return $this->morphMany(File::class,'fileable');
    }

}
