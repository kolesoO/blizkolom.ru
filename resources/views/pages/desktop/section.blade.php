@include("site-templates.public.desktop.header")

<div class="filters-content-wrap">
    <v-company-list property_id="{{ implode(',', $property_id) }}"></v-company-list>
</div>

@include("site-templates.public.desktop.footer")
