<?php

declare(strict_types=1);

namespace App\Traits;

use App\Data\Models\Product;
use App\Data\Models\ProductType;

trait ValidationRules
{
    protected static function required(mixed $value, string $field): ?string
    {
        if (is_string($value)) {
            $value = trim($value);
        }
        return empty($value) !== "" ? null : "$field cannot be empty";
    }

    protected static function numeric(mixed $value, string $field): ?string
    {
        return is_numeric($value) ? null : "$field is not a valid numeric value";
    }

    protected static function arrayOfNumbers(mixed $value, string $field): ?string
    {

        if (!is_array($value)) {
            return "$field is not a valid array";
        }

        foreach ($value as $item) {

            if (!is_numeric($item)) {

                return "$item is not a valid numeric value";
            }
        }

        return null;
    }

    protected static function uniqueSku(mixed $value, string $field): ?string
    {
        return !Product::skuAlreadyUsed($value) ? null : "$field has already been used by another product as SKU";
    }

    protected static function productsExist(mixed $value, string $field): ?string
    {
        $productsRetrieved = Product::getProductsByIDs($value);
        $IDsGiven = $value;
        return count($IDsGiven) === count($productsRetrieved) ? null : "$field has products that have not been found";
    }

    protected static function typeExists(mixed $value, string $field): ?string
    {
        $type = ProductType::findByID(intval($value));
        return !is_null($type) ? null : "$field is not a valid type";
    }
}
