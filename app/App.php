<?php

declare(strict_types=1);

namespace App;

use App\Data\DB;
use App\Http\Request;
use App\Http\Router;
use App\Services\Logger;
use DateTime;

class App
{
    public const VERSION = 1.0;
    private Router $router;
    private Request $request;
    private Logger $logger;
    private DB $db;

    public function __construct(string $environment = "development")
    {
        $this->logger = Logger::getInstance()->catch($environment);

        // pass configurations variables
        $this->db = DB::getInstance($this->logger);
        // create response object
        $this->router = Router::getInstance();
        $this->request = Request::getInstance();
    }

    public function run()
    {
        $this->router->loadRoutes()->resolve($this->request);
    }
}
