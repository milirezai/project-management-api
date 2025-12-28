<?php

namespace App\Models\User;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'status'];

    protected static function newFactory()
    {
        return RoleFactory::new();
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
