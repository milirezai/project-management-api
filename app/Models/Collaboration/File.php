<?php

namespace App\Models\Collaboration;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['user_id', 'fileable_id', 'fileable_type', 'path', 'type', 'size', 'status'];

    public function fileable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
