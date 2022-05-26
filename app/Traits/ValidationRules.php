<?php

declare(strict_types=1);

namespace App\Traits;

trait ValidationRules
{
    protected static function required(string $value): ?string
    {
        return trim($value) !== "" ? null : "$value cannot be empty";
    }

    protected static function numeric(string $value): ?string
    {
        return is_numeric($value) ? null : "$value is not a valid numeric value";
    }
}
