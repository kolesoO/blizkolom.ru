<template>
    <address>
        <strong>
            <a :href="balloonContent.site">{{ balloonContent.name }}</a>
        </strong>
        <br>
        <span>{{ balloonContent.address }}</span>
        <br>
        <span>Режим работы: пн-вс 9:00-18:00</span>
        <span class="red">закрыто до 9:00 пн</span>
        <br>
        <a href="#company-" class="price-link">смотреть цены</a>
    </address>
</template>

<script>
    export default {
        name: "v-marker",
        data: function(){
            return {
                coords: [],
                balloonContent: {},
                defaultSettings: {
                    iconLayout: "default#image",
                    iconImageHref: "https://static.blizkolom.ru/img/marker.svg",
                    iconImageSize: [31, 30],
                    iconImageOffset: [-15, -30],
                    balloonPane: "outerBalloon"
                }
            }
        },
        props: {
            content: {
                type: Object
            }
        },
        watch: {
            content: function () {
                this.balloonContent = this.content;
            }
        }
    }
</script>

<style scoped>

</style>