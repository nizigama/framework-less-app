<?php

declare(strict_types=1);

namespace App\Traits;

use App\Data\Models\Product;
use App\Data\Models\ProductType;

trait ValidationRules
{
    protected static function required(string $value, string $field): ?string
    {
        return trim($value) !== "" ? null : "$field cannot be empty";
    }

    protected static function numeric(string $value, string $field): ?string
    {
        return is_numeric($value) ? null : "$field is not a valid numeric value";
    }

    protected static function uniqueSku(string $value, string $field): ?string
    {
        return !Product::skuAlreadyUsed($value) ? null : "$field has already been used by another product as SKU";
    }

    protected static function typeExists(string $value, string $field): ?string
    {
        $type = ProductType::findByID(intval($value));
        return !is_null($type) ? null : "$field is not a valid type";
    }
}
