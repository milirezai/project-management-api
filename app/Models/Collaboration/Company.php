<?php

namespace App\Models\Collaboration;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['name', 'description', 'status', 'address', 'phone_number', 'email', 'website', 'type'];


    public function owner()
    {
        return $this->belongsTo(User::class,'owner_id');
    }

}
