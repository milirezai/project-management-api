<?php

namespace App\Models\Collaboration;

use App\Models\User\User;
use Database\Factories\FileFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;
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

    public function scopeCreator (Builder $builder, int $user_id)
    {
        $builder->where('user_id','=',$user_id);
    }

    public function scopeFileableType (Builder $builder, string $type)
    {
        $type = $type === 'task' ? 'App\Models\Project\Task' : 'App\Models\Project\Project';
        $builder->where('fileable_type','=',$type);
    }

    public function scopeType (Builder $builder, string $type)
    {
        $builder->where('type','=',$type);
    }

    public function scopeProject (Builder $builder, int $project)
    {
        $builder->fileableType('project')->where('fileable_id','=',$project);
    }
    public function scopeTask (Builder $builder, int $task)
    {
        $builder->fileableType('task')->where('fileable_id','=',$task);
    }
}
