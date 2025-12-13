<?php

namespace App\Models\User;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Collaboration\Company;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = ['first_name','last_name', 'email', 'mobile', 'status', 'activation', 'profile_photo_path', 'password', 'remember_token'];

    public function getFullNameAttribute()
    {
        return $this->first_name .' '. $this->last_name;
    }

    public function company()
    {
        return $this->hasOne(Company::class,'owner_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class,'creator_id');
    }

}
