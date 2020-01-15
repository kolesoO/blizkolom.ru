<template>
    <div class="panel" v-if="arItems.length > 0">
        <p class="panel-heading">Навигация</p>
        <router-link to="/list"></router-link>
        <router-link to="/detail"></router-link>
        <a
                href="#"
                class="panel-block"
                v-for="item in arItems"
                :data-code="item.code"
                :data-field="item.field"
                @click="navClick"
        >
            <span>{{ item.caption }}</span>
        </a>
    </div>
</template>

<script>
    export default {
        name: "v-left-nav",
        data: function() {
            return {
                arItems: [
                    {
                        caption: "Статьи",
                        code: "article",
                        field: "id,name,code"
                    }
                ]
            };
        },
        methods: {
            navClick: function(evt) {
                evt.preventDefault();
                window.app.$emit("v_list-change", {
                    methodCode: evt.target.getAttribute("data-code"),
                    arFieldsToSelect: evt.target.getAttribute("data-field")
                });
            }
        }
    }
</script>