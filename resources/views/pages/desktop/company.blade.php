@include("site-templates.public.desktop.header")

<div class="fast-links">
    <a href="#about">Описание</a>
    <a href="#price">Цены на прием</a>
    <!--a href="#yamap">На карте</a-->
    <a href="#rev">Отзывы</a>
</div>
<div class="card big">
    <div class="top">
        <div class="image" id="about">
            @isset($company->detail_picture)
                <img src="{{ $company->detail_picture->path }}">
            @else
                <img src="https://static.blizkolom.ru/img/company.png">
            @endif
        </div>
        <div class="adr">{{ $company->contacts }}</div>
        <div class="coord">{{ $company->map_coords }}</div>
        <div class="serv">Лицензия, Физлица, Юрлица, Вывоз, Демонтаж</div>
        <div class="about">{{ $company->detail_text }}</div>
        <div class="phone">
            <span>{{ $company->phone }}</span>
            <div class="btn-callback">обратный звонок</div>
        </div>
        <div class="mail">{{ $company->email }}</div>
        <div class="site">{{ $company->url }}</div>
        <div class="clock green">открыто до 18:00</div>
        <div class="price" id="price">
            <span class="prc-cat selected" id="cvetmet-3">Цветмет</span>
            <span href="#" class="prc-cat" id="chermet-3">Чермет</span>
            <span href="#" class="prc-cat" id="izdel-3">Изделия</span>
            <span href="#" class="prc-cat" id="class-3">Классы</span>
        </div>
    </div>
    <div class="bottom">
        <table class="price-table">
            <thead>
            <tr>
                <th>Тип</th>
                <th>Цена <span>(&lt; 50 кг)</span></th>
                <th>Цена <span>(&gt; 50 кг)</span></th>
            </tr>
            </thead>
            <tbody>
            <tr class="title">
                <td colspan="3">Медь</td>
            </tr>
            <tr>
                <td>Кусковая медь</td>
                <td>58 <span>₽/кг</span></td>
                <td>60 <span>₽/кг</span></td>
            </tr>
            <tr>
                <td>Медная стружка</td>
                <td>58 <span>₽/кг</span></td>
                <td>60 <span>₽/кг</span></td>
            </tr>
            <tr>
                <td>Медная шина</td>
                <td>46 <span>₽/кг</span></td>
                <td>48 <span>₽/кг</span></td>
            </tr>
            <tr>
                <td>Медь «блеск»</td>
                <td>58 <span>₽/кг</span></td>
                <td>60 <span>₽/кг</span></td>
            </tr>
            <tr>
                <td>Медь «микс»</td>
                <td>58 <span>₽/кг</span></td>
                <td>60 <span>₽/кг</span></td>
            </tr>
            <tr class="title">
                <td colspan="3">Нержавейка</td>
            </tr>
            <tr>
                <td>10% никеля</td>
                <td>46 <span>₽/кг</span></td>
                <td>48 <span>₽/кг</span></td>
            </tr>
            <tr>
                <td>6% никеля</td>
                <td>46 <span>₽/кг</span></td>
                <td>48 <span>₽/кг</span></td>
            </tr>
            <tr>
                <td>8% никеля</td>
                <td>46 <span>₽/кг</span></td>
                <td>48 <span>₽/кг</span></td>
            </tr>
            <tr>
                <td>Стружка нерж.</td>
                <td>46 <span>₽/кг</span></td>
                <td>48 <span>₽/кг</span></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div id="rev">
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
</div>

@include("site-templates.public.desktop.footer")
