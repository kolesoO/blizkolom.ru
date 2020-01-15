<template>
    <div>
        <div id="filters">
            <div class="fltr_slct" v-for="item in selectedFilters">
                <div @click="updateSelectedFilterIds(item.id, true)">{{ item.title }}</div>
            </div>
            <div class="fltr" v-for="item in filtersList">
                <div class="">{{ item.title }}</div>
                <div class="open closed">
                    <ul class="shadow">
                        <li
                                v-for="childItem in item.childs"
                                :class="childItem.class"
                                @click="updateSelectedFilterIds(childItem.id)"
                        >{{ childItem.title }}</li>
                        <li class="btn" @click="filterData">Применить</li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="content">
            <div id="listing">
                <div id="yamap" class="fixed">
                    <!--div class="map-btn">
                        <span class="opened">Увеличить карту</span>
                        <span class="closed">Уменьшить карту</span>
                    </div-->
                    <div id="map"></div>
                    <div class="promo-map">
                        <span class="title">Не можете найти свой пункт приема?</span>
                        <span>Добавьте его сами, это бесплатно!</span>
                        <div class="btn">Добавить компанию</div>
                    </div>
                </div>
                <div id="cards">
                    <p v-show="companiesList.list.length === 0">Компании не найдены</p>
                    <div
                            v-for="company in companiesList.list"
                            :id="company.id_formatted"
                            class="card"
                    >
                        <div class="top">
                            <div class="image">
                                <img
                                        src="https://static.blizkolom.ru/img/company/moscow/pmk-mini.jpg"
                                        data-src="https://static.blizkolom.ru/img/company/moscow/pmk-mini.jpg"
                                        data-srcset="https://static.blizkolom.ru/img/company/moscow/pmk-mini.jpg"
                                        srcset="https://static.blizkolom.ru/img/company/moscow/pmk-mini.jpg"
                                >
                                <div class="rating good">5.0</div>
                            </div>
                            <div class="name">
                                <a :href="company.page_url">{{ company.name }}</a>
                            </div>
                            <div class="adr">{{ company.contacts }}</div>
                            <div class="coord">{{ company.map_coords_str }}</div>
                            <div class="serv">Лицензия, Физлица, Юрлица, Вывоз, Демонтаж</div>
                            <div class="phone">
                                <span>{{ company.phone }}</span>
                                <div class="btn-callback">обратный звонок</div>
                            </div>
                            <div class="mail">{{ company.email }}</div>
                            <div class="site">{{ company.url }}</div>
                            <div class="clock green">открыто до 18:00</div>
                            <!--div class="price">
                                <span
                                        v-for="prop in tableProps"
                                        class="prc-cat"
                                        @click="getPrices(company.id, prop.code)"
                                >{{ prop.title }}</span>
                            </div-->
                        </div>
                        <!--div class="bottom">
                            <table class="price-table">
                                <thead>
                                    <tr>
                                        <th>Тип</th>
                                        <th>Цена <span>(&lt; 50 кг)</span></th>
                                        <th>Цена <span>(&gt; 50 кг)</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Медь</td>
                                        <td>365 <span>₽/кг</span></td>
                                        <td>372 <span>₽/кг</span></td>
                                    </tr>
                                    <tr>
                                        <td>Алюминий</td>
                                        <td>58 <span>₽/кг</span></td>
                                        <td>60 <span>₽/кг</span></td>
                                    </tr>
                                    <tr>
                                        <td>Латунь</td>
                                        <td>58 <span>₽/кг</span></td>
                                        <td>60 <span>₽/кг</span></td>
                                    </tr>
                                    <tr>
                                        <td>Нержавейка</td>
                                        <td>46 <span>₽/кг</span></td>
                                        <td>48 <span>₽/кг</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div-->
                    </div>
                    <button
                            class="button"
                            v-show="companiesList.list.length < companiesList.total"
                            @click="getMoreData()"
                    >
                        <span>Показать еще</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "v-company-list",
        data: function() {
            return {
                filtersList: [],
                selectedFilters: [],
                selectedFiltersId: [],
                companiesLimit: 20,
                companiesOffset: 0,
                companiesList: {
                    'list': [],
                    'total': 0
                },
                tablePropCode: [],
                tableProps: []
            }
        },
        methods: {
            updateSelectedFilterIds: function(filterId, submit = false) {
                let index = this.selectedFiltersId.indexOf(filterId);
                if (index === -1) {
                    this.selectedFiltersId.push(filterId);
                } else {
                    this.selectedFiltersId.splice(index, 1);
                }
                if (submit) {
                    this.filterData();
                }
            },
            refreshSelectedFilters: function() {
                let ctx = this,
                    item;
                ctx.selectedFilters = [];
                for (let key in ctx.filtersList) {
                    for (let keyInner in ctx.filtersList[key].childs) {
                        item = ctx.filtersList[key].childs[keyInner];
                        if (ctx.selectedFiltersId.indexOf(item.id) !== -1) {
                            ctx.selectedFilters.push(item);
                            ctx.filtersList[key].childs[keyInner]['class'] = 'select slcted';
                        } else {
                            ctx.filtersList[key].childs[keyInner]['class'] = 'select';
                        }
                    }
                }
            },
            filterData: function() {
                let ctx = this;
                ctx.$root.doRequest(
                    'GET',
                    'company',
                    [],
                    {
                        'active': 1,
                        'property_id': ctx.selectedFiltersId,
                        'limit': ctx.companiesLimit
                    },
                    (response) => {
                        for (let key in response.body.list) {
                            response.body.list[key]['id_formatted'] = 'comp-' + response.body.list[key].id;
                        }
                        ctx.companiesList = response.body;
                        ctx.refreshSelectedFilters();
                        ctx.initMap();
                    }
                );
                ctx.companiesOffset = ctx.companiesLimit;
            },
            getMoreData: function() {
                let ctx = this;
                ctx.$root.doRequest(
                    'GET',
                    'company',
                    [],
                    {
                        'active': 1,
                        'property_id': ctx.selectedFiltersId,
                        'offset': ctx.companiesOffset,
                        'limit': ctx.companiesLimit
                    },
                    (response) => {
                        for (let key in response.body.list) {
                            response.body.list[key]['id_formatted'] = 'comp-' + response.body.list[key].id;
                            ctx.companiesList.list.push(response.body.list[key]);
                        }
                        ctx.refreshSelectedFilters();
                        ctx.initMap();
                    }
                );
                ctx.companiesOffset += ctx.companiesLimit;
            },
            getPlacemarkData: function(item) {
                if (!item || typeof item.map_coords != "object") {
                    return null;
                }
                return new window.ymaps.Placemark(
                    item.map_coords, {
                        balloonContentBody: [
                            '<address>',
                            '<strong><a href="' + item.page_url + '">' + item.name + '</a></strong>',
                            '<br/>',
                            item.contacts + ' <span class="red">закрыто до 9:00 пн</span>',
                            '<br/>',
                            item.phone,
                            '<br/>',
                            '<a href="#comp-' + item.id + '" class="price-link">смотреть цены</a>',
                            '</address>'
                        ].join('')
                    }, {
                        iconLayout: "default#image",
                        iconImageHref: "https://static.blizkolom.ru/img/marker.svg",
                        iconImageSize: [31, 30],
                        iconImageOffset: [-15, -30],
                        balloonPane: "outerBalloon"
                    }
                );
            },
            initMap: function() {
                let mapPlacemark,
                    mapPlacemarks = [],
                    target = document.getElementById('map');

                if (!target) {
                    return;
                }

                target.innerHTML = '';
                window.yandexMap = new ymaps.Map("map", {
                    center: [55.76, 37.64],
                    zoom: 9
                });
                window.clusterer = new ymaps.Clusterer({
                    preset: 'islands#invertedBlackClusterIcons',
                    groupByCoordinates: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false
                });
                window.yandexMap.behaviors.disable("scrollZoom");
                for (let key in this.companiesList.list) {
                    mapPlacemark = this.getPlacemarkData(this.companiesList.list[key]);
                    if (!!mapPlacemark) {
                        window.yandexMap.geoObjects.add(mapPlacemark);
                        mapPlacemarks.push(mapPlacemark);
                    }
                }
                window.clusterer.add(mapPlacemarks);
                window.yandexMap.geoObjects.add(window.clusterer);
                window.yandexMap.setBounds(
                    window.yandexMap.geoObjects.getBounds(),
                    {
                        zoomMargin: 9
                    }
                );

                //scrolling
                let truck = document.getElementById("yamap"),
                    truck_pos_t = truck.getBoundingClientRect()["top"],
                    truck_pos_b = truck.getBoundingClientRect()["bottom"],
                    cont_pos_b = document.getElementById("content").getBoundingClientRect()["bottom"]-19.5;

                window.onscroll = function() {
                    if (window.pageYOffset >= truck_pos_t) {
                        if (window.pageYOffset >= (cont_pos_b - truck_pos_b + truck_pos_t)) {
                            truck.classList.remove("fixed");
                        } else {
                            truck.classList.add("fixed");
                        }
                    } else {
                        if (window.pageYOffset < truck_pos_t) {
                            truck.classList.remove("fixed");
                        }
                    }
                };
                //end
            },
            getPrices: function(companyId, propertyCode) {
                let ctx = this;
                ctx.$root.doRequest(
                    'GET',
                    'price',
                    [],
                    {
                        'company_id': [companyId],
                        'property_code': propertyCode
                    },
                    (response) => {

                    }
                );
            }
        },
        mounted() {
            if (!!this.$el.getAttribute('property_id')) {
                this.selectedFiltersId = this.$el.getAttribute('property_id').split(',');
                for (let key in this.selectedFiltersId) {
                    this.selectedFiltersId[key] = parseInt(this.selectedFiltersId[key]);
                }
            }
            if (!!this.$el.getAttribute('table_property_code')) {
                this.tablePropCode = this.$el.getAttribute('table_property_code').split(',');
            }

            let ctx = this,
                interval = setInterval(
                    function() {
                        if (typeof window.ymaps == 'object') {
                            ctx.$root.doRequest(
                                'GET',
                                'property',
                                [],
                                {
                                    'filtered': '1'
                                },
                                (response) => {
                                    let item, itemInner;
                                    for (let key in response.body) {
                                        item = response.body[key];
                                        if (ctx.tablePropCode.indexOf(item.code) > -1) {
                                            ctx.tableProps.push(item);
                                        }
                                        if (!item.parent_id) {
                                            item['childs'] = [];
                                            for (let keyInner in response.body) {
                                                itemInner = response.body[keyInner];
                                                if (itemInner.parent_id === item.id) {
                                                    itemInner['class'] = 'select';
                                                    item['childs'].push(itemInner);
                                                }
                                            }
                                            ctx.filtersList.push(item);
                                        }
                                    }
                                    ctx.filterData();
                                }
                            );
                            clearInterval(interval);
                        }
                    },
                    1000
                );
        }
    }
</script>
