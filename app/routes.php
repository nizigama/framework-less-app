<?php

declare(strict_types=1);

use App\Controllers\Controller;
use App\Http\Router;

$router = Router::getInstance();

$router->get("/", [Controller::class, "index"]);
