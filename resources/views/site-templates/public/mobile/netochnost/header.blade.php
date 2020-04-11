<html lang="ru">
<head>
    <title>{{ $header["seo"]["title"] }}</title>

    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta name="description" content="{{ $header["seo"]["description"] }}" />
    <meta name="keywords" content="{{ $header["seo"]["keywords"] }}" />
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="address=no"/>
    <meta name="theme-color" content="#1f1f1f">

    <link rel="icon" href="https://static.blizkolom.ru/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="{{ asset("css/public/mobile/app.css") }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("css/public/mobile/app-after.css") }}">
    <link rel="stylesheet" type="text/css" href="{{ asset("css/public/mobile/netochnost.css") }}">
    <link rel="manifest" href="/manifest.json">
</head>
<body>
<div id="app" class="clear">
    <header class="shadow">
        <div class="content-head">
            <div class="menu-btn open">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="logo">
                <div class="main-page">
                    <a href="/"><img src="https://static.blizkolom.ru/img/blizkolom.png" alt="BlizkoLom"></a>
                </div>
            </div>
            <div class="info left">
                <img class="search-btn" src="https://static.blizkolom.ru/img/location.svg" alt="Поиск">
            </div>
        </div>
    </header>
    <aside class="disable">
        <div class="menu">
            {!! $menu['take'] !!}
            {!! $menu['to_points'] !!}
            {!! $menu['more'] !!}
        </div>
        <div class="menu-smoke">
            <img src="/images/cancel.svg" alt="Выход">
        </div>
    </aside>
    <v-single-property title="{{ $root_property_title }}"></v-single-property>
    <h1>{{ $header["seo"]["h1"] }}</h1>
