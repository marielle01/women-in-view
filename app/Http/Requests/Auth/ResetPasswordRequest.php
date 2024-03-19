<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\AppFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends AppFormRequest
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
            'email' => [
                'required',
                'email',
            ],
            'token' => [
                'required',
            ],
            'password' => [
                'required',
                'string',
                'regex:/^(?=.*?[a-zA-Z])(?=.*?[0-9])(=.*?[#?!@$%^&*-]){0,}.{8,}$/',
            ],
        ];
    }
}
