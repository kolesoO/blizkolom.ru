<?php
declare(strict_types=1);

use App\Http\Middleware\OwnAccess;
use Illuminate\Support\Facades\Route;

Route::middleware([OwnAccess::class])
    ->group(static function () {
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

                Route::post('/register', 'RegisterController@register');
                Route::post('/login', 'AuthController@login');
            }
        );
    });

Route::middleware('auth:api')
    ->group(static function () {
        Route::group(
            [
                "namespace" => "Api\V1",
                "prefix" => "/v1",
            ],
            function() {
                Route::get('/me', 'AuthController@me');

                Route::post('/company', 'CompanyController@store');
                Route::get('/company/my', 'CompanyController@listByClient');
                Route::post('/company/{id}', 'CompanyController@update');
                Route::delete('/company/{id}', 'CompanyController@delete');

                Route::post('/client', 'ClientController@store');
                Route::get('/client/{id}', 'ClientController@show');
                Route::post('/client/{id}', 'ClientController@update');
                Route::post('/client/{id}/change_password', 'ClientController@changePwd');
            }
        );
    });
