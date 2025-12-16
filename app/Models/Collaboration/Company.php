<?php

namespace App\Models\Collaboration;

use App\Models\User\User;
use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'status', 'address', 'phone_number', 'email', 'website', 'type', 'owner_id'];

    protected static function newFactory()
    {
        return CompanyFactory::new();
    }
    public function owner()
    {
        return $this->belongsTo (User::class,'owner_id');
    }
    public function scopeStatus (Builder $query, int $status): void
    {
        $query->where('status','=',$status);
    }
    public function scopeType (Builder $query, string $type): void
    {
        $query->where('type','like',"%$type%");
    }

}
