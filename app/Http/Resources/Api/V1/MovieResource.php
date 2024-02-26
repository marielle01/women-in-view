<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
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
            'tmdb_id'=> $this->imdb_id,
            'original_title' => $this->original_title,
            'poster_path' => $this->poster_path,
            'overview' => $this->overview,
            'release_date' => $this->release_date,
            'rating' => $this->rating,
            'user_id' => $this->user_id,
        ];
    }
}
