<?php

namespace App\Http\Resources\Api\V1\Project;

use App\Http\Resources\Api\V1\Collaboration\CompanyResource;
use App\Http\Resources\Api\V1\UserResource;
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
            'creator_id' => UserResource::make($this->whenLoaded('creator')),
            'company_id' => CompanyResource::make($this->whenLoaded('company'))
        ];
    }
}
