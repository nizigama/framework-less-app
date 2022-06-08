<?php

declare(strict_types=1);

namespace App\Http;

use App\Controllers\Controller;

class Router
{
    private array $routes;
    private static ?Router $instance = null;

    private function __construct()
    {
        $this->routes = [];
    }


    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Router
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $path
     * @param closure | array<class-string, string> $handler
     */
    public function get($path, $handler): self
    {
        $this->routes['get'][$path] = $handler;
        return $this;
    }

    /**
     * @param string $path
     * @param closure | array<class-string, string> $handler
     */
    public function post($path, $handler): self
    {
        $this->routes['post'][$path] = $handler;
        return $this;
    }

    /**
     * @param string $path
     * @param closure | array<class-string, string> $handler
     */
    public function put($path, $handler): self
    {
        $this->routes['put'][$path] = $handler;
        return $this;
    }

    /**
     * @param string $path
     * @param closure | array<class-string, string> $handler
     */
    public function delete($path, $handler): self
    {
        $this->routes['delete'][$path] = $handler;
        return $this;
    }

    public function loadRoutes(): self
    {
        require_once __DIR__ . '/../routes.php';
        return $this;
    }

    public function resolve(Request $request)
    {
        $requestUri = explode("?", $_SERVER["REQUEST_URI"]);
        $currentPath = $requestUri[0];
        $requestMethod = strtolower($_SERVER["REQUEST_METHOD"]);
        $handler = $this->routes[$requestMethod][$currentPath];

        if (is_null($handler)) {
            echo "Route not found";
            return;
        }

        if (!$this->validHandler($handler)) {
            echo "Invalid handler";
            return;
        }

        if (is_callable($handler)) {
            call_user_func($handler, [$request, ...$request->queryValues]);
            return;
        }

        call_user_func_array([new $handler[0](), $handler[1]], [$request, ...$request->queryValues]);
    }

    // validate route handler
    private function validHandler($handler): bool
    {

        if (is_callable($handler)) {
            return true;
        }

        if (count($handler) != 2) {
            return false;
        }

        if (!class_exists($handler[0])) {
            return false;
        }

        $parent = class_parents($handler[0]);

        // Verify that the class extends the Controller class
        if (array_key_first($parent) != Controller::class) {
            return false;
        }

        // Get the methods declared in the passed class
        $methods = get_class_methods($handler[0]::class);

        // Verify that the given method exists in the given Controller class
        if (!in_array($handler[1], $methods)) {
            return false;
        }

        return true;
    }
}
