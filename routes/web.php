<?php

use App\Http\Controllers\Pages;
use Illuminate\Routing\Router;

/* @var Router $router */

$router->get("/kak-sdat", [
    'as' => 'how',
    'uses' => 'Pages@how',
]);
$router->get("/netochnost", [
    'as' => 'netochnost',
    'uses' => 'Pages@netochnost',
]);

//prices
//$router->get("/ceny", "Pages@prices");
//end

//index
$router->get("/{propertyCode?}", [
    'as' => 'index',
    'uses' => 'Pages@index',
]);
$router->get('/filter/{filteredPropCode}', [
    'as' => 'index-filter',
    'uses' => static function ($filteredPropCode) {
        /** @var Pages $pageController */
        $pageController = app()->make(Pages::class);

        return $pageController->index(null, $filteredPropCode);
    }
]);
$router->get('{propertyCode}/filter/{filteredPropCode}', [
    'as' => 'index-property-filter',
    'uses' => 'Pages@index',
]);
//end

//company
$router->get(
    '/priem/{companyCode}',
    [
        'as' => 'company-detail',
        'uses' => 'Pages@company'
    ]
);
//end

//section
$router->get('/{propertyCode}/{property2Code}', [
    'as' => 'section',
    'uses' => 'Pages@section',
]);
$router->get('/{propertyCode}/{property2Code}/filter/{filteredPropCode}', [
    'as' => 'section-filter',
    'uses' => static function ($propertyCode, $property2Code, $filteredPropCode) {
        /** @var Pages $pageController */
        $pageController = app()->make(Pages::class);

        return $pageController->section($propertyCode, $property2Code, null, $filteredPropCode);
    }
]);

$router->get('/{propertyCode}/{property2Code}/{property3Code?}', [
    'as' => 'section-3',
    'uses' => 'Pages@section'
]);
$router->get('/{propertyCode}/{property2Code}/{property3Code?}/filter/{filteredPropCode}', [
    'as' => 'section-3-filter',
    'uses' => static function ($propertyCode, $property2Code, $property3Code, $filteredPropCode) {
        /** @var Pages $pageController */
        $pageController = app()->make(Pages::class);

        return $pageController->section($propertyCode, $property2Code, $property3Code, $filteredPropCode);
    }
]);
//end
