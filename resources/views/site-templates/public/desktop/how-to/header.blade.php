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
    <link rel="stylesheet" type="text/css" href="{{ asset("css/public/desktop/app.css") }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset("css/public/desktop/app-after.css") }}">
    <link rel="manifest" href="/manifest.json">
</head>
<body>
    <div id="app" class="clear">
        <header class="shadow">
            <div class="content-head">
                <div class="logo">
                    <div class="main-page">
                        <a href="/"><img src="https://static.blizkolom.ru/img/blizkolom.png" alt="BlizkoLom" title="Все пункты приема"></a>
                    </div>
                </div>
                <form action="/action/search" method="post" class="search-site">
                    <input type="search" name="q" placeholder="Поиск среди 121 пункта сдачи металлолома">
                    <input type="submit" class="header-search-btn" value="">
                </form>
                <v-single-property title="{{ $root_property_title }}"></v-single-property>
            </div>
        </header>
        {!! $menu['general'] !!}
        <div class="how-to clear" itemscope itemtype="http://schema.org/HowTo">
            <h1 itemprop="name">{{ $header["seo"]["h1"] }}</h1>
            <p itemprop="description">Краткая инструкция по тому как наиболее выгодно сдать металлолом.</p>
            <meta  itemprop="supply" itemtype="http://schema.org/HowToSupply" content="Металлолом" />
