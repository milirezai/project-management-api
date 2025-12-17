<?php

namespace App\Models\Project;

use App\Models\Collaboration\Comment;
use App\Models\Collaboration\Company;
use App\Models\Collaboration\File;
use App\Models\User\User;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'start_date', 'end_date', 'status', 'creator_id','company_id'];

    protected static function newFactory()
    {
        return ProjectFactory::new();
    }
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
