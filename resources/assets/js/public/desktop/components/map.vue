<template>
    <div id="yamap">
        <div class="map-btn" @click="resize">
            <span class="opened">Увеличить карту</span>
            <span class="closed">Уменьшить карту</span>
        </div>
        <div id="map"></div>
        <div class="promo-map">
            <span class="title">Не можете найти свой пункт приема?</span>
            <span>Добавьте его сами, это бесплатно!</span>
            <!--div class="btn">Добавить компанию</div-->
        </div>
    </div>
</template>

<script>
    export default {
        name: "v-map",
        data: function() {
            return {
                companiesList: {
                    list: [],
                    total: 0
                },
            }
        },
        methods: {
            getPlacemarkData: function(item) {
                if (!item || typeof item.map_coords != "object") {
                    return null;
                }

                let openCloseInfo = '';

                if (item.openTime.state === 'from') {
                    openCloseInfo = ' <span class="' + (item.openTime.status ? 'green' : 'red') + '">открыто с ' + item.openTime.time + '</span>';
                } else if (item.openTime.state === 'to') {
                    openCloseInfo = ' <span class="' + (item.openTime.status ? 'green' : 'red') + '">открыто до ' + item.openTime.time + '</span>';
                } else if (item.openTime.state === 'full') {
                    openCloseInfo = ' <span class="' + (item.openTime.status ? 'green' : 'red') + '">открыто ' + item.openTime.time + '</span>';
                }

                return new window.ymaps.Placemark(
                    item.map_coords, {
                        balloonContentBody: [
                            '<address>',
                            '<strong><a href="' + item.page_url + '">' + item.name + '</a></strong>',
                            '<br>',
                            item.contacts + '<br>' + openCloseInfo,
                            '<br>',
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

                if (!!window.yandexMap) {
                    window.yandexMap.destroy();
                }

                target.innerHTML = '';
                window.yandexMap = new window.ymaps.Map("map", {
                    center: [55.76, 37.64],
                    zoom: 9
                });
                window.clusterer = new window.ymaps.Clusterer({
                    preset: 'islands#invertedBlackClusterIcons',
                    groupByCoordinates: false,
                    clusterHideIconOnBalloonOpen: false,
                    geoObjectHideIconOnBalloonOpen: false
                });
                window.yandexMap.behaviors.disable("scrollZoom");

                if (this.companiesList.list.length > 0) {
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
                }
            },
            resize: function () {
                let ctx = this;

                setTimeout(function () {
                    ctx.initMap();
                }, 100);
            }
        },
        mounted() {
            let ctx = this,
                interval = setInterval(
                    function() {
                        if (typeof window.ymaps == 'object') {
                            ctx.$root.$emit('map-is-ready');
                            clearInterval(interval);
                        }
                    },
                    1000
                );
            ctx.$root.$on('company-full_list-updated', function (data) {
                ctx.companiesList = data;
                ctx.initMap();
            });
        }
    }
</script>
