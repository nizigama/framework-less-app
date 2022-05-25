<?php

declare(strict_types=1);

namespace App\Data;

use App\Services\Logger;
use PDO;

abstract class Model
{
    protected PDO $db;

    public function __construct()
    {
        $logger = Logger::getInstance()->catch();
        $this->db = DB::getInstance($logger)->getDB();
    }
}
