<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class AppFormRequest extends FormRequest
{
    /**
     * @return mixed
     *
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'success' => false,
            'message' => 'Some errors occurred',
            'errors' => $validator->errors(),
        ]);
        $response->setStatusCode(404);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());

    }
}
