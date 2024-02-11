<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tmdbId'=> $this->tmdbId,
            'title' => $this->title,
            'posterPath' => $this->posterPath,
            'synopsis' => $this->synopsis,
            'year' => $this->year,
            'rating' => $this->rating,
            'user_id' => $this->user_id,
        ];
    }
}
