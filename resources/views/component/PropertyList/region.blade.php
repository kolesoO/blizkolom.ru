@php
    use App\Models\Property;

    /** @var Property[] $arResult */
@endphp

@if (is_array($arResult))
    <div class="block">
        <div class="title">Города</div>
        @foreach ($arResult as $item)
            <a href="/{{ $item->code }}">{{ $item->title }}</a>
        @endforeach
    </div>
@endif
