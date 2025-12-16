<?php

namespace App\Models\Collaboration;

use App\Models\User\User;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'status', 'address', 'phone_number', 'email', 'website', 'type', ''];

    protected static function newFactory()
    {
        return CompanyFactory::new();
    }
    public function owner()
    {
        return $this->belongsTo(User::class,'owner_id');
    }

}
