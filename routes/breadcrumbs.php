<?php

declare(strict_types=1);

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::for('root', static function (BreadcrumbsGenerator $trail) {
    $trail->push(
        'Главная',
        route('index', ['propertyCode' => null], false),
        ['meta_content' => 'Пункты сдачи']
    );
});

Breadcrumbs::for('how', static function (BreadcrumbsGenerator $trail) {
    $seoData = app()->component->includeComponent("Seo", "", [
        "code" => "/kak-sdat"
    ]);
    $trail->parent('root');
    $trail->push(
        (string) $seoData['h1'],
        route('how', [], false),
        ['meta_content' => (string) $seoData['h1']]
    );
});

Breadcrumbs::for('netochnost', static function (BreadcrumbsGenerator $trail) {
    $seoData = app()->component->includeComponent("Seo", "", [
        "code" => "/netochnost"
    ]);
    $trail->parent('root');
    $trail->push(
        (string) $seoData['h1'],
        route('how', [], false),
        ['meta_content' => (string) $seoData['h1']]
    );
});
