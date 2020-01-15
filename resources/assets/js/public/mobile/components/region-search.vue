<template>
    <div class="region disable">
        <div class="regions">
            <div class="search">
                <input type="search" placeholder="Поиск города" value="" @search="search" @keyup="search">
                <div class="result" v-if="searchItems.length > 0">
                    <div
                            v-for="item in searchItems"
                            class="reg-load"
                            :data-reg="item.url"
                    >{{ item.name }}</div>
                </div>
            </div>
            <div class="cities">
                <div class="title">Популярные города</div>
                <div
                        v-for="item in popularItems"
                        class="reg-load"
                        :data-reg="item.url"
                >{{ item.name }}</div>
            </div>
        </div>
        <div class="region-smoke"><img src="/images/cancel.svg" alt="Выход"></div>
    </div>
</template>

<script>
    export default {
        name: "v-region-search",
        data: function(){
            return {
                apiCode: "region",
                popularItems: [],
                searchItems: []
            }
        },
        methods: {
            /**
             *
             * @param url
             * @returns {string}
             */
            getFullUrl: function(url) {
                return location.protocol + "//" + url;
            },
            /**
             *
             */
            search: function() {
                let ctx = this;
                ctx.searchItems = [];
                if (event.target.value.length > 0) {
                    ctx.$http
                        .get(ctx.$root.getApiUrl(ctx.apiCode), {
                            params: {
                                filter: {
                                    "%name": event.target.value
                                }
                            }
                        })
                        .then(resp => {
                            resp.data.forEach(function(item){
                                if (!!item.url) {
                                    item.url = ctx.getFullUrl(item.url);
                                }
                                ctx.searchItems.push(item);
                            })
                        })
                        .catch(error => {
                            alert(error);
                        });
                }
            }
        },
        mounted() {
            let ctx = this;
            ctx.$http
                .get(ctx.$root.getApiUrl(ctx.apiCode), {
                    params: {
                        filter: {
                            popular: "Y"
                        }
                    }
                })
                .then(resp => {
                    resp.data.forEach(function(item){
                        if (!!item.url) {
                            item.url = ctx.getFullUrl(item.url);
                        }
                        ctx.popularItems.push(item);
                    })
                })
                .catch(error => {
                    alert(error);
                });
        }
    }
</script>