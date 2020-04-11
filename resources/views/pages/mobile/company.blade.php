@include("site-templates.public.mobile.company.header")

<div class="fast-links">
    <a href="#about">Описание</a>
    <a href="#price">Цены на прием</a>
    <!--a href="#yamap">На карте</a-->
    <!--a href="#rev">Отзывы</a-->
</div>
<div class="card big">
    <div class="top">
        <div class="image" id="about">
            @isset($company->detail_picture)
                <img src="{{ $company->detail_picture->path }}">
            @else
                <img src="https://static.blizkolom.ru/img/company.png">
            @endif
            <div class="rating {{ $company->rating['info'] }}">{{ $company->rating['rating'] }}</div>
        </div>
        <div class="adr">{{ $company->contacts }}</div>
        <div class="coord">{{ $company->map_coords }}</div>
        <div class="serv">Лицензия, Физлица, Юрлица, Вывоз, Демонтаж</div>
        <div class="about">{{ $company->detail_text }}</div>
        <div class="phone">
            <span>{{ $company->phone }}</span>
            <div class="btn-callback" data-company_name="{{ $company->name }}">
                <img src="/images/callback-gray.svg">
            </div>
        </div>
        <div class="mail">{{ $company->email }}</div>
        <div class="site">{{ $company->url }}</div>
        @if ($company->openTime['state'] === 'from')
            <div class="clock @if ($company->openTime['status']) green @else red @endif ">открыто с {{ $company->openTime['time'] }}</div>
        @else
            <div class="clock @if ($company->openTime['status']) green @else red @endif ">открыто до {{ $company->openTime['time'] }}</div>
        @endif
        @if (count($company->prices) > 0)
            <div class="price" id="price">
                @foreach ($company->prices as $key => $price)
                    <span
                            class="prc-cat @if ($key === 0) selected @endif "
                            data-target="#comp_price-{{ $company->id }}-{{ $price['id'] }}"
                    >{{ $price['title'] }}</span>
                @endforeach
            </div>
        @endif
    </div>
    @foreach ($company->prices as $key => $price)
        <div
                id="comp_price-{{ $company->id }}-{{ $price['id'] }}"
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
                        @if (isset($value['childs']) && count($value['childs']) > 0)
                            <tr class="title">
                                <td colspan="3">{{ $value['type'] }}</td>
                            </tr>
                            @foreach ($value['childs'] as $child)
                                <tr>
                                    <td>{{ $child['type'] }}</td>
                                    <td>{{ $child['value'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>{{ $value['type'] }}</td>
                                <td>{{ $value['value'] }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
</div>
<!--div id="rev">
    <div class="title">Отзывы о пункте приема</div>
    <div class="rating">4.0 <img src="/images/star.svg"><img src="/images/star.svg"><img src="/images/star.svg"><img src="/images/star.svg"><img src="/images/star-gray.svg"><div>2 отзыва</div></div>
    <div class="review">
        <img class="user" src="/images/user.svg">
        <div class="name">Константин</div>
        <div class="rat">4.0 <img src="/images/star.svg"><img src="/images/star.svg"><img src="/images/star.svg"><img src="/images/star.svg"><img src="/images/star-gray.svg"></div>
        <div class="desc">За более чем 5 месяцев сдаю на регулярной основе металлолом только в данный пункт приема. Высокие цены на прием и профессионализм!</div>
        <div class="date">05-06-2019</div>
        <div class="like">12</div>
        <div class="dislike">1</div>
    </div>
    <div class="review">
        <img class="user" src="/images/user.svg">
        <div class="name">Илья</div>
        <div class="rat">4.0 <img src="/images/star.svg"><img src="/images/star.svg"><img src="/images/star.svg"><img src="/images/star.svg"><img src="/images/star-gray.svg"></div>
        <div class="desc">Пацаны вообще ребята!</div>
        <div class="date">06-06-2019</div>
        <div class="like">3</div>
        <div class="dislike">10</div>
    </div>
</div-->

@include("site-templates.public.mobile.company.footer")
