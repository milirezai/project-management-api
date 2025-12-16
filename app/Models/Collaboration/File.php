<?php

namespace App\Models\Collaboration;

use App\Models\User\User;
use Database\Factories\FileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'fileable_id', 'fileable_type', 'path', 'type', 'size', 'status'];

    public static function newFactory()
    {
        return FileFactory::new();
    }
    public function fileable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
