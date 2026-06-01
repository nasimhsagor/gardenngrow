<?php

declare(strict_types=1);

namespace App\Rules;

use App\ValueObjects\PhoneNumber;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BangladeshiPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!is_string($value) || !PhoneNumber::isValid($value)) {
            $fail(trans('general.phone_number_invalid', ['attribute' => $attribute]));
        }
    }
}
