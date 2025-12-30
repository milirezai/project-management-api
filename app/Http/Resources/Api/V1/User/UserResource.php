<?php

namespace App\Http\Resources\Api\V1\User;

use App\Http\Resources\Api\V1\Collaboration\CompanyResource;
use App\Http\Resources\Api\V1\Project\ProjectResource;
use App\Http\Resources\Api\V1\Project\TaskResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' =>  $this->first_name,
            'last_name' =>  $this->last_name,
            'mobile' =>  $this->mobile,
            'email' => $this->email,
            'profile_photo_path' =>  $this->profile_photo_path,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'company' => CompanyResource::make($this->whenLoaded('company')),
            'ownedCompany' => CompanyResource::make($this->whenLoaded('ownedCompany')),
            'projects' => ProjectResource::collection($this->whenLoaded('projects')),
            'assignedProjects' => ProjectResource::collection($this->whenLoaded('assignedProjects')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            'assignedTasks' => TaskResource::collection($this->whenLoaded('assignedTasks'))
        ];
    }
}
