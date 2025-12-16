<?php

namespace App\Models\Collaboration;

use App\Models\User\User;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ['body', 'author_id', 'commentable_id', 'commentable_type', 'status'];

    protected static function newFactory()
    {
        return CommentFactory::new();
    }
    public function commentable()
    {
        return $this->morphTo();
    }
    public function author()
    {
        return $this->belongsTo(User::class,'author_id');
    }
}
