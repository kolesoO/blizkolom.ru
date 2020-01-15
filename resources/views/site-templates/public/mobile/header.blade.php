<html lang="ru">
<head>
    <title>{{ $header["seo"]["seo_title"] }}</title>

    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta name="description" content="{{ $header["seo"]["seo_description"] }}" />
    <meta name="keywords" content="{{ $header["seo"]["seo_keywords"] }}" />
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="address=no"/>
    <meta name="theme-color" content="#1f1f1f">

    <link rel="icon" href="https://static.blizkolom.ru/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" charset="utf-8" type="text/css" href="{{ asset("css/public/mobile/app.css") }}" />
    <link media="none" rel="stylesheet" type="text/css" href="{{ asset("css/public/mobile/app-after.css") }}">
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
                    <div class="main-page"><img src="https://static.blizkolom.ru/img/blizkolom.png" alt="BlizkoLom"></div>
                </div>
                <div class="info left">
                    <img class="search-btn" src="https://static.blizkolom.ru/img/location.svg" alt="Поиск">
                </div>
            </div>
        </header>
        <aside class="disable">
            <div class="menu">
                {!! $menu_take !!}
                {!! $menu_to_points !!}
                {!! $header["menu_more"] !!}
            </div>
            <div class="menu-smoke"><img src="/images/cancel.svg" alt="Выход"></div>
        </aside>
        <v-region-search></v-region-search>
        <h1>{{ $header["seo"]["seo_h1"] }}</h1>