<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CustomIPValidator implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|\*)\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|\*)\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|\*)$/', $value) )
            $fail('The :attribute must be follow this pattern X.X.X.X or X.X.X.*');
    }
}
