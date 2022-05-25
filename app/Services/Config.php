<?php

declare(strict_types=1);

namespace App\Services;

use Dotenv\Dotenv;

class Config
{
    private static array $configs;

    public static function load()
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__) . "/../");
        $dotenv->load();

        self::$configs = $_ENV;
    }

    public static function environment(): string
    {
        return self::$configs["APP_ENVIRONMENT"];
    }

    /**
     * Get a configuration value from the .env file
     */
    public static function getEnv(string $key): string
    {
        return self::$configs[$key];
    }

    /**
     * Set a configuration value during runtime
     */
    public function setEnv(string $key, string $value): string
    {
        return self::$configs[$key] = $value;
    }
}
