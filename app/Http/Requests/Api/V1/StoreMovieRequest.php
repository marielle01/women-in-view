<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\AppFormRequest;
use App\Models\Api\V1\Movie;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMovieRequest extends AppFormRequest
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
                'required',
                'integer',
                Rule::unique(Movie::class)
            ],
            'original_title' => [
                'required',
                'string'
            ],
            'poster_path' => [
                'required',
                'string'
            ],
            'backdrop_path' => [
                'required',
                'string'
            ],
            'overview' => [
                'required',
                'string'
            ],
            'release_date' => [
                'required',
                'date'
            ],
            'user_id' => [
                'required',
                'integer'
            ],
            'rating' => [
                'required',
                'integer'
            ],
        ];
    }
}
