<template>
    <div class="region disable">
        <div class="regions">
            <div class="search">
                <input
                        type="search"
                        placeholder="Поиск города"
                        value=""
                        v-model="searchInput"
                        @keyup="search"
                >
                <div class="result">
                    <div v-if="searchedItems.length === 0 && searchInput.length > 0">Город не найден!</div>
                    <div
                            class="reg-load"
                            v-for="item in searchedItems"
                    >
                        <a :href="item.url">{{ item.title }}</a>
                    </div>
                </div>
            </div>
            <div class="cities">
                <div class="title">Сейчас выбран</div>
                <p>{{ title }}</p>
            </div>
            <div class="cities">
                <div class="title">Популярные города</div>
                <div
                        class="reg-load"
                        v-for="item in items"
                >
                    <a :href="item.url">{{ item.title }}</a>
                </div>
            </div>
        </div>
        <div class="region-smoke">
            <img src="/images/cancel.svg" alt="Выход">
        </div>
    </div>
</template>

<script>
    export default {
        name: "v-single-property",
        data: function() {
            return {
                title: '',
                searchInput: '',
                items: [],
                searchedItems: []
            }
        },
        methods: {
            search: function (event) {
                let ctx = this;
                ctx.$root.doRequest(
                    'GET',
                    'property',
                    [],
                    {
                        'urlable': 1,
                        'root_url': 1,
                        'with_url': 1,
                        'title': event.target.value
                    },
                    (response) => {
                        ctx.searchedItems = response.body;
                    }
                );
            }
        },
        mounted() {
            if (!!this.$el.getAttribute('title')) {
                this.title = this.$el.getAttribute('title');
            }
            let ctx = this;
            ctx.$root.doRequest(
                'GET',
                'property',
                [],
                {
                    'urlable': 1,
                    'root_url': 1,
                    'popular': 1,
                    'with_url': 1
                },
                (response) => {
                    ctx.items = response.body;
                }
            );
        }
    }
</script>
