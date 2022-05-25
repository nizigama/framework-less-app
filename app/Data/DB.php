<?php

declare(strict_types=1);

namespace App\Data;

use App\Services\Config;
use App\Services\Logger;
use PDO;
use PDOException;

class DB
{
    private static ?DB $instance = null;
    private PDO $db;

    private function __construct(Logger $logger)
    {
        $driver = Config::getEnv("DB_DRIVER");
        $host = Config::getEnv("DB_HOST");
        $port = Config::getEnv("DB_PORT");
        $database = Config::getEnv("DB_DATABASE");
        $user = Config::getEnv("DB_USER");
        $pass = Config::getEnv("DB_PASSWORD");

        try {
            $this->db = new PDO("$driver:host=$host;port=$port;dbname=$database", $user, $pass, []);
        } catch (PDOException $ex) {
            $logger->log($ex->getMessage(), (string)$ex->getCode(), __LINE__);
            throw new PDOException("Failed to connect to db", (int)$ex->getCode());
        }
    }

    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(Logger $logger): DB
    {
        if (self::$instance === null) {
            self::$instance = new self($logger);
        }

        return self::$instance;
    }

    public function getDB(): PDO
    {
        return $this->db;
    }
}
