<?php

declare(strict_types=1);

namespace App\Http;

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
     * @param array<string, array<class-string, string>> $handler
     */
    public function get($path, $handler): self
    {
        $this->routes['get'][$path] = $handler;
        return $this;
    }

    /**
     * @param string $path
     * @param array<string, array<class-string, string>> $handler
     */
    public function post($path, $handler): self
    {
        $this->routes['post'][$path] = $handler;
        return $this;
    }

    /**
     * @param string $path
     * @param array<string, array<class-string, string>> $handler
     */
    public function put($path, $handler): self
    {
        $this->routes['put'][$path] = $handler;
        return $this;
    }

    /**
     * @param string $path
     * @param array<string, array<class-string, string>> $handler
     */
    public function delete($path, $handler): self
    {
        $this->routes['delete'][$path] = $handler;
        return $this;
    }

    public function resolve()
    {
        $requestUri = explode("?", $_SERVER["REQUEST_URI"]);
        $currentPath = $requestUri[0];
        $queryString = count($requestUri) > 1 ? explode("?", $_SERVER["REQUEST_URI"])[1] : "";
        $requestMethod = strtolower($_SERVER["REQUEST_METHOD"]);
        $handler = $this->routes[$requestMethod][$currentPath];

        parse_str($queryString ?? "", $queryStrings);

        if (is_null($handler)) {
            echo "Route not found";
            return;
        }

        if (count($handler) != 2) {
            echo "Invalid handler";
            return;
        }

        if (!class_exists($handler[0])) {
            echo "Controller not found";
            return;
        }

        if (!method_exists($handler[0], $handler[1])) {
            echo "Controller method not found";
            return;
        }

        $parameters = array_values($queryStrings);

        call_user_func_array([new $handler[0](), $handler[1]], $parameters);
    }
}
