<?php

declare(strict_types=1);

namespace App;

use DateTime;

class App
{
    public const VERSION = 1.0;

    public function __construct(string $environment = "development")
    {
        // connect to database

        $this->errorLogging($environment);
    }

    private function errorLogging(string $environment): void
    {
        $errorFile = fopen(dirname(__DIR__) .  "/logs/" . $environment . "/errors.log", "a+");

        if ($environment === "production") {
            error_reporting(~E_ALL);
            ini_set('display_errors', "0");
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', "1");
        }


        register_shutdown_function(function () use ($errorFile) {

            if (!is_null($err = error_get_last())) {

                $timestamp = (new DateTime())->format("Y-m-d, H:i:s");
                $type = $err['type'];
                $line = $err['line'];
                $message = $err['message'];

                fwrite($errorFile, "datetime: {$timestamp}\ntype: $type\nline: $line\nmessage: $message\n\n");
            }

            fclose($errorFile);
        });
    }
}
