<?php

namespace App\Http\Resources\Api\V1\Project;

use App\Http\Resources\Api\V1\Collaboration\CommentResource;
use App\Http\Resources\Api\V1\Collaboration\FileResource;
use App\Http\Resources\Api\V1\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'priority' => $this->priority,
            'creator' => UserResource::make($this->whenLoaded('creator')),
            'assignee' => UserResource::make($this->whenLoaded('assignee')),
            'project' => ProjectResource::make($this->whenLoaded('project')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'files' => FileResource::collection($this->whenLoaded('files'))
        ];
    }
}
