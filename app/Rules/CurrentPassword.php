<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class CurrentPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the provided value does not match the hashed current user's password.
        if (! Hash::check($value, auth('sanctum')->user()->password)) {
            // If not a match, invoke the fail closure with a validation error message.
            $fail("The {$attribute} is invalid.");
        }
    }
}
