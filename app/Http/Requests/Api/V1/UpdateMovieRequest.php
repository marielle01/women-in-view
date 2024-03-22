<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\AppFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends AppFormRequest
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
            'rating' => 'integer',
        ];
    }
}
