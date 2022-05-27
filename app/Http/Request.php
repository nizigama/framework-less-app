<?php

declare(strict_types=1);

namespace App\Http;

use stdClass;

class Request
{
    private static ?Request $instance = null;
    public array $headers = [];
    public array $query = [];
    public array $queryValues = [];
    public stdClass $body;

    private function __construct()
    {
        $this->headers = $this->parseRequestHeaders();
        $this->query = $this->parseRequestQueryParameters();
        $this->queryValues = array_values($this->parseRequestQueryParameters());
        $this->body = $this->parseRequestBody();
    }

    /**
     * gets the instance via lazy initialization (created on first usage)
     */
    public static function getInstance(): Request
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function data(): array
    {
        return $this->parseRequestBodyToArray();
    }

    private function parseRequestHeaders(): array
    {
        return array_filter($_SERVER, function ($k) {

            $starter = mb_substr($k, 0, 5);

            if ($starter === "HTTP_") {
                return true;
            }
            return false;
        }, ARRAY_FILTER_USE_KEY);
    }

    private function parseRequestQueryParameters(): array
    {
        $requestUri = explode("?", $_SERVER["REQUEST_URI"]);

        $queryString = count($requestUri) > 1 ? $requestUri[1] : null;

        parse_str($queryString ?? "", $queryStrings);

        return $queryStrings;
    }

    private function parseRequestBody(): stdClass
    {
        $method = $_SERVER["REQUEST_METHOD"];
        $requestBody = new stdClass();

        switch ($method) {
            case 'GET':
                $this->readRequestBody($_GET, $requestBody);
                break;
            case 'POST':
                $this->readRequestBody($_POST, $requestBody);
                break;
            case 'PUT':
                $this->readRequestBody($_POST, $requestBody);
                break;
            case 'DELETE':
                $this->readRequestBody($_GET, $requestBody);
                break;
            default:
                $this->readRequestBody($_GET, $requestBody);
                break;
        }

        return $requestBody;
    }

    private function parseRequestBodyToArray(): array
    {
        $method = $_SERVER["REQUEST_METHOD"];
        $requestBody = [];

        switch ($method) {
            case 'GET':
                $this->readRequestBodyToArray($_GET, $requestBody);
                break;
            case 'POST':
                $this->readRequestBodyToArray($_POST, $requestBody);
                break;
            case 'PUT':
                $this->readRequestBodyToArray($_POST, $requestBody);
                break;
            case 'DELETE':
                $this->readRequestBodyToArray($_GET, $requestBody);
                break;
            default:
                $this->readRequestBodyToArray($_GET, $requestBody);
                break;
        }

        return $requestBody;
    }

    private function readRequestBody(array $requestData, stdClass &$requestBody): stdClass
    {
        if ($this->headers["HTTP_CONTENT_TYPE"] === "application/json") {
            $input = file_get_contents('php://input');
            $requestData = is_string($input) ? json_decode($input, true) : [];
        }
        foreach ($requestData as $key => $value) {
            $requestBody->$key = $value;
        }
        return $requestBody;
    }

    private function readRequestBodyToArray(array $requestData, array &$requestBody)
    {
        if ($this->headers["HTTP_CONTENT_TYPE"] === "application/json") {
            $input = file_get_contents('php://input');
            $requestData = is_string($input) ? json_decode($input, true) : [];
        }
        foreach ($requestData as $key => $value) {
            $requestBody[$key] = $value;
        }
    }
}
