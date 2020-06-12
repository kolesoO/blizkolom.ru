@php
    use App\Models\Property;

    /** @var Property $item */
@endphp

@if (is_array($arResult['items']))
    <div class="block">
        <div class="title">Города</div>
        @foreach ($arResult['items'] as $item)
            <a href="/{{ $item->code }}">{{ $item->title }}</a>
        @endforeach
    </div>
@endif
