@php
    use App\Models\Property;

    /** @var Property|null $parent */
    $parent = $arResult['parent'] ?? null;
@endphp

@if (is_array($arResult['items']))
    <div class="block">
        @if ($parent)
            <div class="title">
                <a href="{{ $parent->url }}">{{ $parent->seo['menu_title'] ?? $parent->title }}</a>
            </div>
        @endif
        @foreach ($arResult['items'] as $item)
            <a href="{{ $item->url }}">{{ $item->title }}</a>
        @endforeach
    </div>
@endif
