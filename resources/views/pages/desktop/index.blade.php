@include("site-templates.public.desktop.header")

<div class="filters-content-wrap">
    <v-filter property_id="{{ implode(',', $property_id) }}"></v-filter>
</div>
<div id="content">
    <div id="listing">
        <v-map></v-map>
        <div id="cards">{!! $company_list !!}</div>
    </div>
    {{ \DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::render() }}
</div>

@include("site-templates.public.desktop.footer")
