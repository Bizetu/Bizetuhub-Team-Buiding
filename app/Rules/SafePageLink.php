<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SafePageLink implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! \is_string($value)) {
            return;
        }

        if ((str_starts_with($value, '/') && ! str_starts_with($value, '//')) || str_starts_with($value, '#')) {
            return;
        }

        $scheme = parse_url($value, PHP_URL_SCHEME);
        if (filter_var($value, FILTER_VALIDATE_URL) && \in_array(strtolower((string) $scheme), ['http', 'https'], true)) {
            return;
        }

        $fail("The {$attribute} must be a site path, anchor, or HTTP(S) URL.");
    }
}
