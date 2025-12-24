<?php

namespace App\Http\Resources\Api\V1\Project;

use App\Http\Resources\Api\V1\Collaboration\CompanyResource;
use App\Http\Resources\Api\V1\Collaboration\FileResource;
use App\Http\Resources\Api\V1\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'creator' => UserResource::make($this->whenLoaded('creator')),
            'members' => UserResource::collection($this->whenLoaded('members')),
            'company' => CompanyResource::make($this->whenLoaded('company')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'comments' => CompanyResource::collection($this->whenLoaded('comments')),
            'files' => FileResource::collection($this->whenLoaded('files'))
        ];
    }
}
