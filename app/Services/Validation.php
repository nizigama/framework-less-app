<?php

declare(strict_types=1);

namespace App\Services;

use App\Traits\ValidationRules;

// This validation class should be complex enough with different methods to validate whether
// the data passed in has the expected structure
// whether the rules array also has a valid structure
// to prevent runtime errors when the incoming data or rules aren't what the make method expects
class Validation
{
    use ValidationRules;

    public bool $failed;
    public array $errorMessages;


    private function __construct(bool $failed, array $errors = [])
    {
        $this->failed = $failed;
        $this->errorMessages = $errors;
    }


    /**
     * @param array<string, string> $data
     * @param array<string, array<string>> $rules
     */
    public static function make($data, $rules): Validation
    {

        $usedRules = [];

        foreach (array_values($rules) as $validationRule) {
            array_push($usedRules, ...$validationRule);
        }

        $usedRules = array_unique($usedRules);

        $missingRules = array_filter($usedRules, function (string $rule) {
            return !method_exists(Validation::class, $rule);
        });

        if (count($missingRules) > 0) {
            return array_map(function ($functionName) {
                return "'$functionName' is not a valid validation rule";
            }, $missingRules);
        };

        $errorMessages = [];

        foreach ($rules as $key => $validationRules) {
            $dataToValidate = array_key_exists($key, $data) ? $data[$key] : "";

            foreach ($validationRules as $validationRule) {
                $result = call_user_func_array([Validation::class, $validationRule], [$dataToValidate, $key]);
                if (!is_bool($result) && !is_null($result)) {
                    array_push($errorMessages, $result);
                    break;
                }
            }
        }

        return count($errorMessages) > 0 ? new Validation(true, $errorMessages) : new Validation(false);
    }
}
