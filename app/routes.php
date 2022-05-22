<?php

declare(strict_types=1);

use App\Controller;
use App\Http\Router;

$router = Router::getInstance();

$router->get("/", function () {

    echo "<form action='?passed=query&when=2022' method='POST' enctype='multipart/form-data'>
    
        <input type='text' name='name' />
        <br>
        <input type='file' name='picture' />
        <button type='submit'>Submit</button>
    </form>";
});

$router->post("/", [Controller::class, "index"]);
