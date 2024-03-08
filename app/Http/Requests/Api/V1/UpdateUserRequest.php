<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\AppFormRequest;
use App\Models\Api\V1\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends AppFormRequest
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
            'name' => [
                'string',
                'max:255',
            ],
            'email' => [
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => [
                'string',
                'regex:/^(?=.*?[a-zA-Z])(?=.*?[0-9])(=.*?[#?!@$%^&*-]){0,}.{8,}$/',
            ],
        ];
    }
}
