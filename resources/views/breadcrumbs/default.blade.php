@php
    /** @var array $breadcrumbs */
    /** @var int $fullCount */

    $fullCount = count($breadcrumbs);
@endphp

<div class="bread" itemscope="" itemtype="https://schema.org/BreadcrumbList">
    @foreach ($breadcrumbs as $key => $breadcrumb)
        <span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a
                    itemprop="item"
                    href="{{ $breadcrumb->url }}"
            >{{ $breadcrumb->title }}<meta itemprop="name" content="{{ $breadcrumb->meta_content ?? '' }}"></a>
            @if ($key < $fullCount - 1)
                <span> / </span>
            @endif
            <meta itemprop="position" content="{{ ($key + 1) }}">
        </span>
    @endforeach
</div>
