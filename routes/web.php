<?php

use Illuminate\Routing\Router;

/* @var Router $router */

$router->get("/kak-sdat", "Pages@how");
$router->get("/netochnost", "Pages@netochnost");
$router->get("/{propertyCode?}", "Pages@index");
$router->get(
    '/{propertyCode}/priem/{companyCode}',
    [
        'as' => 'company-detail',
        'uses' => 'Pages@company'
    ]
);
$router->get("/{propertyCode}/{property2Code}/{property3Code?}", "Pages@section");
