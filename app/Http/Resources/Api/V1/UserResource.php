<?php

namespace App\Http\Resources\Api\V1;

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
            'profile_photo_path' =>  $this->profile_photo_path,
        ];
    }
}
