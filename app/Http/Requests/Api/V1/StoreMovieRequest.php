<?php

namespace App\Http\Requests\Api\V1;

use App\Models\Api\V1\Movie;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMovieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tmdb_id' => [
                'integer',
                Rule::unique(Movie::class)
            ],
            'original_title' => [
                'string'
            ],
            'poster_path' => [
                'string'
            ],
            'backdrop_path' => [
                'string'
            ],
            'overview' => [
                'string'
            ],
            'release_date' => [
                'date'
            ],
            'user_id' => [
                'integer'
            ],
            'rating' => [
                'integer'
            ],
        ];
    }
}
