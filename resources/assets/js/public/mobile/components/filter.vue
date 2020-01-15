<template>
    <form id="filters" @submit.prevent="submit">
        <input type="hidden" name="filter[owner_table]" :value="owner_table">
        <div class="fltr" v-for="field in fields">
            <div>{{ field.name }}</div>
            <div class="open closed">
                <ul class="shadow">
                    <li
                            class="select"
                            v-for="valueItem in field.values"
                    >
                        <input type="checkbox" :name="field.code" :value="valueItem.id">
                        <span>{{ valueItem.value }}</span>
                    </li>
                    <li><button type="submit" class="btn">Применить</button></li>
                </ul>
            </div>
        </div>
    </form>
</template>

<script>
    export default {
        name: "v-filter",
        data: function(){
            return {
                apiCode: "property",
                fields: []
            }
        },
        props: {
            owner_table: {
                type: String
            }
        },
        methods: {
            /**
             *
             * @param event
             */
            submit: function(event) {
                this.$emit("filterSubmit", formDataJSON(new FormData(event.target)));
            }
        },
        watch: {
            owner_table: function() {
                let ctx = this;
                ctx.$root.doRequest(
                    "get",
                    ctx.apiCode,
                    {},
                    {
                        filter: {
                            owner_table: ctx.owner_table
                        }
                    },
                    function(response) {
                        for (let key in response.data) {
                            if (response.data[key].type_code == "L") {
                                response.data[key].code = "filter[" + response.data[key].code + "]";
                            }
                        }
                        ctx.fields = response.data;
                    }
                );
            }
        },
        mounted() {

        }
    }
</script>