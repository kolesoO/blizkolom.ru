@if (is_array($arResult["items"]) && count($arResult["items"]) > 0)
    <div class="block">
        <div class="title">{!! $arResult["name"] !!}</div>
        @foreach ($arResult["items"] as $key => $arItem)
            <a href="{{ $arItem["link"] }}">{{ $arItem["title"] }}</a>
        @endforeach
    </div>
@endif