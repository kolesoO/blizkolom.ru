<?php

use App\Http\Controllers\Pages;
use Illuminate\Routing\Router;

/* @var Router $router */

$router->get("/kak-sdat", "Pages@how");

$router->get("/netochnost", "Pages@netochnost");

//prices
//$router->get("/ceny", "Pages@prices");
//end

//index
$router->get("/{propertyCode?}", 'Pages@index');
$router->get('/filter/{filteredPropCode}', function ($filteredPropCode) {
    /** @var Pages $pageController */
    $pageController = app()->make(Pages::class);

    return $pageController->index(null, $filteredPropCode);
});
$router->get('{propertyCode}/filter/{filteredPropCode}', 'Pages@index');
//end

//company
$router->get(
    '/{propertyCode}/priem/{companyCode}',
    [
        'as' => 'company-detail',
        'uses' => 'Pages@company'
    ]
);
//end

//section
$router->get('/{propertyCode}/{property2Code}', 'Pages@section');
$router->get('/{propertyCode}/{property2Code}/filter/{filteredPropCode}', function ($propertyCode, $property2Code, $filteredPropCode) {
    /** @var Pages $pageController */
    $pageController = app()->make(Pages::class);

    return $pageController->section($propertyCode, $property2Code, null, $filteredPropCode);
});

$router->get('/{propertyCode}/{property2Code}/{property3Code?}', 'Pages@section');
$router->get('/{propertyCode}/{property2Code}/{property3Code?}/filter/{filteredPropCode}', function ($propertyCode, $property2Code, $property3Code, $filteredPropCode) {
    /** @var Pages $pageController */
    $pageController = app()->make(Pages::class);

    return $pageController->section($propertyCode, $property2Code, $property3Code, $filteredPropCode);
});
//end
