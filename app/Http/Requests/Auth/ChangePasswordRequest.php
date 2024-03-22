<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\AppFormRequest;
use App\Rules\ConfirmPassword;
use App\Rules\CurrentPassword;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends AppFormRequest
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
                'min:8',
            ],
            'confirmPassword' => [
                'required',
                'string',
                new ConfirmPassword(),
            ]
        ];
    }
}
