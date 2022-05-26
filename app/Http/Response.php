<?php

declare(strict_types=1);

namespace App\Http;

class Response
{
    public static function json(): self
    {
        self::setHeader(["Content-Type" => "application/json"]);

        return static ;
    }

    public static function response(array $data, array $headers)
    {
        foreach ($headers as $header) {
            self::setHeader($header);
        }

        echo json_encode($data);
    }

    public static function setHeader(array $header): void
    {
        $type = array_key_first($header);
        $value = array_values($header)[0];
        header("$type: $value");
    }
}
