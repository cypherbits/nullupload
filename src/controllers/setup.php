<?php

use nullupload\DB;
use Slim\Http\Request;
use Slim\Http\Response;

$app->group('/setup', function () {

    $this->map(['GET', 'POST'], "", function ($request, $response, $args) {



    })->setName("setupPage");

});
