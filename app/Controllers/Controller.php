<?php

declare(strict_types=1);

namespace App\Controllers;

use App\App;
use App\Http\Request;
use App\Http\Response;
use App\Services\Validation;

class Controller
{
    public function index(Request $request, ...$queryParameters)
    {


        Response::response(["status" => "Up and running", "version" => App::VERSION]);
    }

    /*
    public function example_with_validation(Request $request, ...$queryParameters)
    {
        $validation = Validation::make($request->data(), [
            "input" => ["rule1", "rule2"]
        ]);

        if ($validation->failed) {
         Response::response(["errors" => $validation->errorMessages],[],Response::BAD_REQUEST);
        }
    }
    */
}
