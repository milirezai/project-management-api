<?php

namespace App\Models\Collaboration;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['body', 'author_id', 'commentable_id', 'commentable_type', 'status'];

    public function commentable()
    {
        return $this->morphTo();
    }
    public function author()
    {
        return $this->belongsTo(User::class,'author_id');
    }
}
