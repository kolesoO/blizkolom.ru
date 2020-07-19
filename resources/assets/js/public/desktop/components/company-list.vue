<template>
    <div>
        <p v-if="isEmptyCompanies()">Компании не найдены</p>
        <div
                v-for="company in companiesList.list"
                :id="company.id_formatted"
                class="card"
        >
            <div class="top">
                <div class="image">
                    <img
                            v-if="company.preview_picture"
                            :src="company.preview_picture"
                            :data-src="company.preview_picture"
                            :data-srcset="company.preview_picture"
                            :srcset="company.preview_picture"
                    >
                    <img
                            v-else
                            src="https://static.blizkolom.ru/img/company.png"
                            data-src="https://static.blizkolom.ru/img/company.png"
                            data-srcset="https://static.blizkolom.ru/img/company.png"
                            srcset="https://static.blizkolom.ru/img/company.png"
                    >
                    <div :class="['rating', company.rating['info']]">{{ company.rating['rating'] }}</div>
                </div>
                <div class="name">
                    <a :href="company.page_url">{{ company.name }}</a>
                </div>
                <div v-if="company.contacts" class="adr">{{ company.contacts }}</div>
                <div v-if="company.map_coords_str" class="coord">{{ company.map_coords_str }}</div>
                <div v-if="company.options.length > 0" class="serv">{{ company.options }}</div>
                <div v-if="company.phone" class="phone">
                    <a
                            :href="['tel:' + company.phone]"
                            class="js-statistic"
                            :data-company_id="company.id"
                            data-type="call"
                    >{{ company.phone }}</a>
                    <div
                            class="btn-callback"
                            :data-company_name="company.name"
                            :data-company_id="company.id"
                    >обратный звонок</div>
                </div>
                <div v-if="company.email" class="mail">{{ company.email }}</div>
                <div v-if="company.url" class="site">{{ company.url }}</div>
                <div
                        v-if="company.openTime.state === 'from'"
                        :class="['clock', company.openTime.status ? 'green' : 'red']"
                >открыто с {{ company.openTime.time }}</div>
                <div
                        v-if="company.openTime.state === 'to'"
                        :class="['clock', company.openTime.status ? 'green' : 'red']"
                >открыто до {{ company.openTime.time }}</div>
                <div
                        v-if="company.openTime.state === 'full'"
                        :class="['clock', company.openTime.status ? 'green' : 'red']"
                >открыто {{ company.openTime.time }}</div>
                <div
                        v-if="company.prices.length > 0"
                        class="price"
                >
                    <span
                            v-for="(priceInfo, key) in company.prices"
                            :class="['prc-cat', key === 0 ? 'selected' : '']"
                            :data-target="['#comp_price-' + company.id + '-' + priceInfo.id]"
                    >{{ priceInfo.title }}</span>
                </div>
            </div>
            <div
                    v-for="(priceInfo, key) in company.prices"
                    :id="['comp_price-' + company.id + '-' + priceInfo.id]"
                    :class="['bottom', key > 0 ? 'closed' : '']"
            >
                <table class="price-table">
                    <thead>
                        <tr>
                            <th>Тип</th>
                            <th>Цена</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="price in priceInfo.values">
                            <td>{{ price.type }}</td>
                            <td>{{ price.value }} <span>руб/кг</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div style="text-align:center" v-if="getCompanyCount() < companiesList.total">
            <button class="button" @click="getMoreData()">
                <span>Показать еще</span>
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "v-company-list",
        data: function() {
            return {
                asyncMode: false,
                startCount: 0,
                companiesLimit: 10,
                companiesOffset: 0,
                companiesList: {
                    list: [],
                    total: 0
                },
                selectedFiltersId: [],
                pricePropsId: [],
                priceProps: [],
                filtersData: []
            }
        },
        methods: {
            getCompanyCount: function () {
                return this.startCount + this.companiesList.list.length;
            },
            isEmptyCompanies: function () {
                return this.companiesList.list.length === 0 && this.asyncMode;
            },
            getCompanyId: function (id) {
                return 'comp-' + id;
            },
            getMoreData: function() {
                let ctx = this;

                ctx.asyncMode = true;
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
                        if (response.body.list.length > 0) {
                            response.body.list.forEach(function (item) {
                                item['id_formatted'] = ctx.getCompanyId(item.id);
                                ctx.companiesList.list.push(item);
                            });
                        }
                    }
                );
                ctx.companiesOffset += ctx.companiesLimit;
            },
            updatePriceProps: function () {
                let ctx = this,
                    pushedIds = [],
                    defPriceProps = ctx.getDefaultPriceProps();

                ctx.priceProps = [];
                ctx.filtersData.forEach(function (item) {
                    if (ctx.pricePropsId.indexOf(item.id) !== -1 && pushedIds.indexOf(item.id) === -1) {
                        if (defPriceProps.indexOf(item.id) === -1) {
                            ctx.priceProps.unshift(item)
                        } else {
                            ctx.priceProps.push(item);
                        }
                        pushedIds.push(item.id);
                    } else if (!!item.childs) {
                        item.childs.forEach(function (childItem) {
                            if (ctx.pricePropsId.indexOf(childItem.id) !== -1 && pushedIds.indexOf(childItem.id) === -1) {
                                if (defPriceProps.indexOf(childItem.id) === -1) {
                                    ctx.priceProps.unshift(childItem)
                                } else {
                                    ctx.priceProps.push(childItem);
                                }
                                pushedIds.push(childItem.id);
                            } else if (!!childItem.childs) {
                                childItem.childs.forEach(function (subChildItem) {
                                    if (ctx.pricePropsId.indexOf(subChildItem.id) !== -1 && pushedIds.indexOf(subChildItem.id) === -1) {
                                        if (defPriceProps.indexOf(subChildItem.id) === -1) {
                                            ctx.priceProps.unshift(subChildItem)
                                        } else {
                                            ctx.priceProps.push(subChildItem);
                                        }
                                        pushedIds.push(subChildItem.id);
                                    }
                                });
                            }
                        });
                    }
                })
            },
            getDefaultPriceProps: function () {
                let result = [];

                if (!!this.$el.getAttribute('price_props')) {
                    result =  this.$el.getAttribute('price_props').split(',');
                    for (let key in result) {
                        result[key] = parseInt(result[key]);
                    }
                }

                return result;
            }
        },
        mounted() {
            let ctx = this;

            ctx.pricePropsId = ctx.getDefaultPriceProps();

            if (!!ctx.$el.getAttribute('property_id')) {
                ctx.selectedFiltersId = ctx.$el.getAttribute('property_id').split(',');
                for (let key in ctx.selectedFiltersId) {
                    ctx.selectedFiltersId[key] = parseInt(ctx.selectedFiltersId[key]);
                }
            }

            if (!!ctx.$el.getAttribute('start_count')) {
                ctx.companiesOffset = parseInt(ctx.$el.getAttribute('start_count'));
                ctx.startCount = ctx.companiesOffset;
            }

            if (!!ctx.$el.getAttribute('total_count')) {
                ctx.companiesList.total = parseInt(ctx.$el.getAttribute('total_count'));
            }

            ctx.$root.$on('company-list-updated', function (data) {
                let syncWrapper = document.getElementById('sync-list'),
                    priceList,
                    priceInfo;

                if (!!syncWrapper) {
                    syncWrapper.remove();
                }

                ctx.asyncMode = true;
                ctx.startCount = 0;
                ctx.companiesList.list = [];

                data.list.forEach(function (item) {
                    item['id_formatted'] = ctx.getCompanyId(item.id);
                    priceInfo = [];
                    if (item.prices.length > 0) {
                        ctx.priceProps.forEach(function (pricePropItem) {
                            priceList = [];
                            if (!!pricePropItem.childs) {
                                pricePropItem.childs.forEach(function (pricePropItem) {
                                    item.prices.forEach(function(itemPrice) {
                                        if (pricePropItem.id === itemPrice.property_id) {
                                            priceList.push({
                                                type: pricePropItem.title,
                                                value: itemPrice.value
                                            });
                                        }
                                    });
                                });
                            }
                            if (priceList.length > 0) {
                                priceInfo.push({
                                    id: pricePropItem.id,
                                    title: pricePropItem.title,
                                    values: priceList
                                });
                            }
                        });
                    }
                    item.prices = priceInfo;
                    ctx.companiesList.list.push(item);
                });
                ctx.companiesList.total = data.total;
            });

            ctx.$root.$on('selected-filters-updated', function (filterIds) {
                ctx.selectedFiltersId = filterIds;

                //price props
                ctx.pricePropsId = ctx.getDefaultPriceProps();
                ctx.selectedFiltersId.forEach(function (id) {
                    if (ctx.pricePropsId.indexOf(id) === -1) {
                        ctx.pricePropsId.push(id);
                    }
                });
                ctx.updatePriceProps();
                //end
            });

            ctx.$root.$on('filters-data-created', function (data) {
                ctx.filtersData = data;
                ctx.updatePriceProps();
            });
        }
    }
</script>
