@if (is_array($arResult["items"]) && count($arResult["items"]) > 0)
    <div class="menu">
        <div class="content-head">
            @foreach ($arResult["items"] as $key => $arItem)
                <a href="{{ $arItem["link"] }}" class="{{ $arItem["class"] }}">{{ $arItem["title"] }}</a>
            @endforeach
        </div>
    </div>
@endif
