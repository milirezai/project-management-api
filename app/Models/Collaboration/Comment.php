<?php

namespace App\Models\Collaboration;

use App\Models\User;
use Database\Factories\CommentFactory;
use Illuminate\Database\Eloquent\Builder;
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

    public function scopeCreator (Builder $builder, int $creator)
    {
        $builder->where('author_id','=', $creator);
    }
    public function scopeCommentableType (Builder $builder, string $type)
    {
        if (in_array($type,['task','project'])) {
        $type = $type === 'task' ? 'App\Models\Project\Task' : 'App\Models\Project\Project';
        $builder->where('commentable_type','=',$type);
        }
    }
    public function scopeProject (Builder $builder, int $prject)
    {
        $builder->commentableType('project')->where('commentable_id','=',$prject);
    }
    public function scopeTask (Builder $builder, int $task)
    {
        $builder->commentableType('task')->where('commentable_id','=',$task);
    }

}
