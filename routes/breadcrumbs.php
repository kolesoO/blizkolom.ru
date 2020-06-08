<?php

declare(strict_types=1);

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::for('root', static function (BreadcrumbsGenerator $trail) {
    $trail->push(
        'Пункты сдачи',
        route('index', ['propertyCode' => null], false),
        ['meta_content' => '&#9851;️ Пункты сдачи']
    );
});

Breadcrumbs::for('how', static function (BreadcrumbsGenerator $trail) {
    $seoData = app()->component->includeComponent("Seo", "", [
        "code" => "/kak-sdat"
    ]);
    $trail->parent('root');
    $trail->push(
        (string) $seoData['breadcrumb_title'],
        route('how', [], false),
        ['meta_content' => '&#128312 ' . (string) $seoData['breadcrumb_title']]
    );
});

Breadcrumbs::for('netochnost', static function (BreadcrumbsGenerator $trail) {
    $seoData = app()->component->includeComponent("Seo", "", [
        "code" => "/netochnost"
    ]);
    $trail->parent('root');
    $trail->push(
        (string) $seoData['breadcrumb_title'],
        route('how', [], false),
        ['meta_content' => '&#128312 ' . (string) $seoData['breadcrumb_title']]
    );
});
