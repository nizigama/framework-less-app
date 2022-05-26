<?php

declare(strict_types=1);

namespace App\Data;

use App\Services\Logger;
use PDO;

abstract class Model
{
    protected static PDO $db;

    public static function loadDB(Logger $logger)
    {
        self::$db = DB::getInstance($logger)->getDB();
    }
}
