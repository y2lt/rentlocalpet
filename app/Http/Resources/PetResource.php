<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'age' => $this->age,
            'daily_rate' => $this->daily_rate,
            'is_available' => $this->is_available,
            'photos' => $this->photos,
            'average_rating' => $this->reviews_avg_rating,
            'type' => new PetTypeResource($this->whenLoaded('type')),
            'breed' => new BreedResource($this->whenLoaded('breed')),
            'owner' => new UserResource($this->whenLoaded('user')),
            'reviews' => ReviewResource::collection($this->whenLoaded('reviews')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
