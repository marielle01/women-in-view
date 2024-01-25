<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ConfirmPassword implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the provided value is different from the 'password' in the request.
        if ($value !== request()->password) {
            // If different, invoke the fail closure with a validation error message.
            $fail("The {$attribute} is different to password");
        }
    }
}
