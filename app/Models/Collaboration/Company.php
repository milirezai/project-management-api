<?php

namespace App\Models\Collaboration;

use App\Models\Project\Project;
use App\Models\User;
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
    public function users()
    {
        return $this->hasMany(User::class,'company_id');
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
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
