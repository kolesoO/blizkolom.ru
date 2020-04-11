@include("site-templates.public.mobile.header")

<div class="filters-content-wrap">
    <v-filter property_id="{{ implode(',', $property_id) }}"></v-filter>
</div>
<v-map></v-map>
<div class="cards">{!! $company_list !!}</div>

@include("site-templates.public.mobile.footer")
