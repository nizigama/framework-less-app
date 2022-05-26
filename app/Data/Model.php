<?php

declare(strict_types=1);

namespace App\Data;

use App\Services\Logger;
use JsonSerializable;
use PDO;
use stdClass;

abstract class Model implements JsonSerializable
{
    protected static PDO $db;

    public static function loadDB(Logger $logger)
    {
        self::$db = DB::getInstance($logger)->getDB();
    }

    public function jsonSerialize()
    {
        $obj = new stdClass();

        $data = get_object_vars($this);
        $properties = array_keys(get_object_vars($this));

        foreach ($properties as $property) {
            $obj->$property = $data[$property];
        }

        return $obj;
    }
}
