<?php

declare(strict_types=1);

namespace App\Http;

class Response
{
    public const BAD_REQUEST = 400;
    public const OK = 200;

    private static function json()
    {
        self::setHeader(["Content-Type" => "application/json"]);
    }

    public static function response(mixed $data, array $headers = [], int $statusCode = self::OK)
    {
        self::json();

        foreach ($headers as $header) {
            self::setHeader($header);
        }

        http_response_code($statusCode);
        echo json_encode($data);
    }

    private static function setHeader(array $header): void
    {
        $type = array_key_first($header);
        $value = array_values($header)[0];
        header("$type: $value");
    }
}
