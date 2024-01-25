<?php

namespace App\Http\Requests;

use App\Rules\ConfirmPassword;
use App\Rules\CurrentPassword;
use Illuminate\Foundation\Http\FormRequest;
use function Laravel\Prompts\password;

class ChangePasswordRequest extends FormRequest
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
            'currentPassword' => [
                'required',
                'string',
                new CurrentPassword(),
            ],
            'password' => [
                'required',
                'string',
                'regex:/^(?=.*?[a-zA-Z])(?=.*?[0-9])(=.*?[#?!@$%^&*-]){0,}.{8,}$/',
            ],
            'confirmPassword' => [
                'required',
                'string',
                new ConfirmPassword(),
            ]
        ];
    }
}
