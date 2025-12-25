<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Collaboration\Comment;
use App\Models\Collaboration\Company;
use App\Models\Collaboration\File;
use App\Models\Project\Project;
use App\Models\Project\Task;
use App\Trait\HasAcl;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasAcl;

    protected $fillable = ['first_name','last_name', 'mobile', 'status', 'activation', 'profile_photo_path', 'password','company_id'];

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function company()
    {
        return $this->hasOne(Company::class,'owner_id');
    }
    public function ownedCompany()
    {
        return $this->belongsTo(Company::class,'company_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class,'creator_id');
    }

    public function assignedProjects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class,'creator_id');
    }
    public function assignedTasks()
    {
        return $this->hasMany(Task::class,'user_id');
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class,'author_id');
    }

    public function scopeCompanyUsers (Builder $builder, int $company_id)
    {
        $builder->where('company_id','=',$company_id);
    }
}
