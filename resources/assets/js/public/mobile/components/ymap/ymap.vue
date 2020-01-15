<template>
    <div class="wrapper">
        <v-filter @filterSubmit="loadItems" :owner_table="owner_table"></v-filter>
        <div id="yamap"></div>
    </div>
</template>

<script>
    //import filterComp from "../../components/filter";
    export default {
        name: "v-ymap",
        comments: {
            //filterComp
        },
        data: function(){
            return {
                apiCode: "company",
                owner_table: "",
                centerCoords: [],
                zoom: 9
            }
        },
        props: {
        },
        methods: {
            loadItems: function(formData) {
                let ctx = this;
                ctx.$root.doRequest(
                    "get",
                    ctx.apiCode,
                    {},
                    formData,
                    function(response) {
                        /*ctx.fields = [];
                        for (let code in response.data) {
                            if (response.data[code].type == "L") {
                                response.data[code].code += "[]";
                            }
                            ctx.fields.push(response.data[code]);
                        }*/
                    }
                );
            }
        },
        mounted() {
            let ctx = this;
            ctx.centerCoords = ctx.$el.getAttribute("center").split(",");
            ctx.owner_table = ctx.$el.getAttribute("owner_table");
            /*ctx.$http
                .get(ctx.$root.getApiUrl(ctx.apiCode), {
                    params: {
                        filter: {
                            owner_table: ctx.$el.getAttribute("owner_table")
                        }
                    }
                })
                .then(resp => {
                    ctx.fields = [];
                    for (let code in resp.data) {
                        if (resp.data[code].type == "L") {
                            resp.data[code].code += "[]";
                        }
                        ctx.fields.push(resp.data[code]);
                    }
                })
                .catch(error => {
                    alert(error);
                });*/
        }
    }
</script>