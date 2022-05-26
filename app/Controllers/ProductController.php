<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Data\Models\Product;
use App\Data\Models\ProductType;
use App\Http\Request;
use App\Http\Response;
use App\Services\Validation;

class ProductController
{
    public function index()
    {
        $types = Product::all();

        Response::response($types);
    }

    public function create()
    {
        $types = ProductType::all();

        Response::response($types);
    }

    public function store(Request $request, ...$queryParameters)
    {
        $validation = Validation::make($request->data(), [
            "input" => ["rule1", "rule2"]
        ]);

        if ($validation->failed) {
            Response::response(["errors" => $validation->errorMessages], [], Response::BAD_REQUEST);
        }
    }
}
