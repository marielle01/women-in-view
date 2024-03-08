<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\AppFormRequest;
use App\Models\Api\V1\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends AppFormRequest
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
                'required',
                'string',
                'max:255'
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)
            ],
            'password' => [
                'required',
                'string',
                //'regex:/^(?=.*?[a-zA-Z])(?=.*?[0-9]){0,}.{8,}$/',
            ],
            'role_id' => [
                'exists:\Spatie\Permission\Models\Role,id'
            ]
        ];

    }
}
