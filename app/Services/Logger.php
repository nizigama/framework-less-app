<?php

declare(strict_types=1);

namespace App\Services;

use DateTime;

class Logger
{
    private static ?Logger $instance = null;

    private function __construct()
    {
    }

    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Logger
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function log(string $message, string $type, int $line): void
    {
        $errorFile = fopen(dirname(__DIR__) .  "/../logs/app.log", "a+");

        $timestamp = (new DateTime())->format("Y-m-d, H:i:s");

        fwrite($errorFile, "datetime: {$timestamp}\ntype: $type\nline: $line\nmessage: $message\n\n");

        fclose($errorFile);
    }

    public function catch(string $environment): self
    {
        $errorFile = fopen(dirname(__DIR__) .  "/../logs/" . $environment . "/errors.log", "a+");

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

        return $this;
    }
}
