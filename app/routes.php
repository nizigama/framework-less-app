<?php

declare(strict_types=1);

use App\Controllers\Controller;
use App\Controllers\ProductController;
use App\Http\Router;

$router = Router::getInstance();


$router->get("/", [Controller::class, "index"]);

// API endpoints
$router->get("/api", [Controller::class, "index"]);
$router->get("/api/products", [ProductController::class, "index"]);
$router->get("/api/products/create", [ProductController::class, "create"]);
$router->post("/api/products", [ProductController::class, "store"]);
$router->post("/api/products/delete", [ProductController::class, "destroy"]);
