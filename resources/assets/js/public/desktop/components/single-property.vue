<template>
    <div>
        <div class="region not-opened">
            <span>{{ title }}</span>
        </div>
        <div class="regions">
            <div class="search">
                <input
                        type="search"
                        placeholder="Поиск"
                        value=""
                        v-model="searchInput"
                        @keyup="search"
                >
                <div class="result">
                    <div v-if="searchedItems.length === 0 && searchInput.length > 0">Город не найден!</div>
                    <a
                            class="reg-load"
                            v-for="item in searchedItems"
                            :href="item.url"
                    >{{ item.title }}</a>
                </div>
            </div>
            <div class="cities">
                <div class="title">Популярные</div>
                <a
                        class="reg-load"
                        v-for="item in items"
                        :href="item.url"
                >{{ item.title }}</a>
            </div>
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
