<?php

namespace App\Models\Project;

use App\Models\Collaboration\Comment;
use App\Models\Collaboration\File;
use App\Models\User\User;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'user_id', 'project_id', 'creator_id', 'start_data', 'end_date', 'status', 'priority'];

    protected static function newFactory()
    {
        return TaskFactory::new();
    }
    public function comments()
    {
        return $this->morphMany(Comment::class,'commentable');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'creator_id');
    }
    public function files()
    {
        return $this->morphMany(File::class,'fileable');
    }

    public function company()
    {
        return $this->project->company();
    }

}
