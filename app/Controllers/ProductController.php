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
            "name" => ["required"],
            "sku" => ["required", "uniqueSku"],
            "price" => ["required", "numeric"],
            "typeID" => ["required", "numeric", "typeExists"],
            "size" => ["required", "numeric"],
            "height" => ["required", "numeric"],
            "weight" => ["required", "numeric"],
            "length" => ["required", "numeric"],
            "width" => ["required", "numeric"],
        ]);

        if ($validation->failed) {
            Response::response(["errors" => $validation->errorMessages], [], Response::BAD_REQUEST);
            return;
        }

        $productID = Product::createProduct($request->data());

        if (is_null($productID)) {
            Response::response(["message" => "Failed to save product"], [], Response::INTERNAL_SERVER_ERROR);
            return;
        }

        Response::response(["productID" => $productID]);
    }
}
