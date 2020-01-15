<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
        "namespace" => "Api\V1",
        "prefix" => "/v1",
    ],
    function() {
        Route::get('/property', 'PropertyController@index');
        Route::get('/company', 'CompanyController@index');

        Route::get('/form', 'FormController@index');
        Route::get('/form/{code}/fields', 'FormFieldsController@index');
        Route::post('/form/{code}/result', 'FormResultsController@store');

        Route::get('/price', 'PriceController@index');
    }
);
