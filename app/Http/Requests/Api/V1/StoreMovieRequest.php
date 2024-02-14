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
            'imdb_id' => ['required', 'integer'],
            'original_title' => ['required', 'string'],
            'poster_path' => ['required', 'string'],
            'overview' => ['required', 'longText'],
            'release_date' => ['required', 'date'],
            'user_id' => ['required', 'integer'],
            'rating' => ['required', 'integer'],
        ];
    }
}
