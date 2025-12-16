<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Collaboration\Comment;
use App\Models\Collaboration\Company;
use App\Models\Collaboration\File;
use App\Models\Project\Project;
use App\Models\Project\Task;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = ['first_name','last_name', 'mobile', 'status', 'activation', 'profile_photo_path', 'password','company_id'];

    protected static function newFactory()
    {
        return UserFactory::new();
    }
    public function getFullNameAttribute()
    {
        return $this->first_name .' '. $this->last_name;
    }

    public function company()
    {
        return $this->hasOne(Company::class,'owner_id');
    }
    public function userCompany()
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class,'creator_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'author_id');
    }
}
