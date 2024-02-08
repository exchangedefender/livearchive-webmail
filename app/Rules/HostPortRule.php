<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HostPortRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!preg_match(pattern: '/^([\w.\-_]*?):?(\d*?)$/', subject: $value)) {
            $fail(':attribute was not in a valid host:port format');
        }
    }
}
