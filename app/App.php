<?php

declare(strict_types=1);

namespace App;

use App\Data\DB;
use App\Http\Request;
use App\Http\Router;
use App\Services\Logger;

class App
{
    public const VERSION = 1.0;
    private Router $router;
    private Request $request;

    public function __construct(string $environment = "development")
    {
        Logger::getInstance()->catch($environment);

        $this->router = Router::getInstance();
        $this->request = Request::getInstance();
    }

    public function run()
    {
        $this->router->loadRoutes()->resolve($this->request);
    }
}
