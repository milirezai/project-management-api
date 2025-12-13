<?php

namespace App\Models\Collaboration;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['user_id', 'fileable_id', 'fileable_type', 'path', 'type', 'size', 'status'];

    public function fileable()
    {
        return $this->morphTo();
    }
}
