<div id="sync-list">
    @if (is_array($arResult['items']) && count($arResult['items']) > 0)
        @foreach($arResult['items'] as $item)
            <div id="comp-{{ $item->id }}" class="card">
                <div class="top">
                    <div class="image">
                        @if ($item->preview_picture)
                            <img
                                    src="{{ $item->preview_picture }}"
                                    data-src="{{ $item->preview_picture }}"
                                    data-srcset="{{ $item->preview_picture }}"
                                    srcset="{{ $item->preview_picture }}"
                            >
                        @else
                            <img
                                    src="https://static.blizkolom.ru/img/company.png"
                                    data-src="https://static.blizkolom.ru/img/company.png"
                                    data-srcset="https://static.blizkolom.ru/img/company.png"
                                    srcset="https://static.blizkolom.ru/img/company.png"
                            >
                        @endif
                        <div class="rating {{ $item->rating['info'] }}">{{ $item->rating['rating'] }}</div>
                    </div>
                    <div class="name">
                        <a href="{{ $item->pageUrl }}">{{ $item->name }}</a>
                    </div>
                    <div class="adr">{{ $item->contacts }}</div>
                    @if ($item->map_coords)
                        <div class="coord">{{ $item->map_coords }}</div>
                    @endif
                    <div class="serv">{{ implode(', ', $item->options->pluck('value')->toArray()) }}</div>
                    @if ($item->phone)
                        <div class="phone">
                            <span>{{ $item->phone }}</span>
                            <div class="btn-callback" data-company_name="{{ $item->name }}">обратный звонок</div>
                        </div>
                    @endif
                    @if ($item->email)
                        <div class="mail">{{ $item->email }}</div>
                    @endif
                    @if ($item->url)
                        <div class="site">{{ $item->url }}</div>
                    @endif
                    @if ($item->openTime['state'] === 'from')
                        <div class="clock @if ($item->openTime['status']) green @else red @endif ">открыто с {{ $item->openTime['time'] }}</div>
                    @elseif ($item->openTime['state'] === 'to')
                        <div class="clock @if ($item->openTime['status']) green @else red @endif ">открыто до {{ $item->openTime['time'] }}</div>
                    @elseif ($item->openTime['state'] === 'full')
                        <div class="clock @if ($item->openTime['status']) green @else red @endif ">открыто {{ $item->openTime['time'] }}</div>
                    @endif
                    @if (count($item->prices) > 0)
                        <div class="price">
                            @foreach ($item->prices as $key => $price)
                                <span
                                        class="prc-cat @if ($key === 0) selected @endif "
                                        data-target="#comp_price-{{ $item->id }}-{{ $price['id'] }}"
                                >{{ $price['title'] }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
                @foreach ($item->prices as $key => $price)
                    <div
                            id="comp_price-{{ $item->id }}-{{ $price['id'] }}"
                            class="bottom @if ($key > 0) closed @endif "
                    >
                        <table class="price-table">
                            <thead>
                                <tr>
                                    <th>Тип</th>
                                    <th>Цена</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($price['values'] as $value)
                                    <tr>
                                        <td>{{ $value['type'] }}</td>
                                        <td>{{ $value['value'] }} <span>₽/кг</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        @endforeach
    @else
        <span>Компании не найдены</span>
    @endif
</div>
<v-company-list
        property_id="{{ implode(',', $arParams['property_id']) }}"
        price_props="{{ implode(',', $arParams['price_props']) }}"
        start_count="{{ count($arResult['items']) }}"
        total_count="{{ $arResult['full_count'] }}"
></v-company-list>
