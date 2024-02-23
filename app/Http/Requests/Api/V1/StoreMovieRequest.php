<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

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
                'required',
            ],
            'original_title' => [
                'string',
                'required',
            ],
            'poster_path' => [
                'string',
                'required',
            ],
            'backdrop_path' => [
                'string',
                'required',
            ],
            'overview' => [
                'string',
                'required',
            ],
            'release_date' => [
                'date',
                'required',
            ],
            'user_id' => [
                'integer',
                'required',
            ],
            'rating' => [
                'integer',
                'required',
            ],
        ];
    }
}
