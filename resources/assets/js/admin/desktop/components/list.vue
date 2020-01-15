<template>
    <div class="is-24">
        <p v-if="arItems.length == 0">Список пуст</p>
        <table class="table is-hoverable" v-else>
            <thead>
                <tr>
                    <th v-for="arFieldsListItem in arFieldsList">{{ arFieldsListItem.caption }}</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td>
                        <a class="button is-info">Добавить</a>
                    </td>
                </tr>
            </tfoot>
            <tbody>
                <tr v-for="arItem in arItems">
                    <th v-for="arFieldsListItem in arFieldsList">
                        <a href="#" v-if="arFieldsListItem.type == 'link'">{{ arItem[arFieldsListItem.code] }}</a>
                        <span v-else>{{ arItem[arFieldsListItem.code] }}</span>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "v-list",
        routerPath: "/list",
        data: function() {
            return {
                arItems: [],
                arFieldsList: [],
                arFieldsToSelect: [],
                methodCode: "",
                fieldHandBook: {
                    name: "Название"
                }
            };
        },
        methods: {
            update: function() {
                let ctx = this;
                ctx.$http
                    .get(window.apiUrl.default + ctx.methodCode, {
                        params: {
                            select: ctx.arFieldsToSelect
                        }
                    })
                    .then(resp => {
                        if (resp.data.data instanceof Array) {
                            let fieldInfo,
                                fieldCode;
                            ctx.arItems = resp.data.data;
                            if (!!ctx.arItems[0]) {
                                for (fieldCode in ctx.arItems[0]) {
                                    fieldInfo = {
                                        type: "text",
                                        code: fieldCode,
                                        caption: !!ctx.fieldHandBook[fieldCode] ? ctx.fieldHandBook[fieldCode] : fieldCode
                                    };
                                    if (fieldCode == "name") {
                                        fieldInfo.type = "link";
                                    }
                                    ctx.arFieldsList.push(fieldInfo);
                                }
                            }
                        }
                    })
                    .catch(error => {
                        alert(error);
                    });
            }
        },
        created() {

        },
        mounted() {

        }
    }
</script>