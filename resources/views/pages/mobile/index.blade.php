@include("site-templates.public.mobile.header")

<div class="filters-content-wrap">
    <v-filter property_id="{{ implode(',', $property_id) }}"></v-filter>
</div>
<v-map></v-map>
<div class="cards">{!! $company_list !!}</div>

{{ \DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::render() }}

<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "Product",
  "name": "{{ $header["seo"]["h1"] }}",
  "description": "{{ $header["seo"]["description"] }}",
  "AggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "9.8",
    "reviewCount": "{{ (int) $header['company_count']['count'] * 2 }}",
    "bestRating": "10"
  },
  "image": {
    "@type": "ImageObject",
    "url": "https://static.blizkolom.ru/img/lp/punkt-priema-410.jpg"
  },
  "offers": {
    "@type": "AggregateOffer",
    "lowPrice": "0",
    "priceCurrency": "RUB"
  }
}
</script>

@include("site-templates.public.mobile.footer")
