<template>
    <div id="filters">
        <div class="fltr_slct" v-for="item in selectedFilters">
            <div @click="updateSelectedFilterIds(item.id, true)">{{ item.title }}</div>
        </div>
        <div class="fltr" v-for="item in filtersList">
            <div class="">{{ item.title }}</div>
            <div class="open closed">
                <ul class="shadow">
                    <li
                            v-for="childItem in item.childs"
                            :class="childItem.class"
                    >
                        <a
                                class="pseudo"
                                href="#"
                                @click="updateSelectedFilterIds(childItem.id)"
                        >{{ childItem.title }}</a>
                    </li>
                    <li class="btn" @click="filterData">Применить</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "v-filter",
        data: function() {
            return {
                filtersList: [],
                selectedFilters: [],
                selectedFiltersId: [],
                mainUrl: ''
            }
        },
        methods: {
            getFilterItem: function (filtersList, id) {
                let result = false;

                filtersList.forEach(function (item, index) {
                    if (item.id === id) {
                        result = {
                            index: index,
                            item: item
                        };
                    }
                });

                return result;
            },
            updateSelectedFilterIds: function(filterId, submit = false) {
                let index = this.selectedFiltersId.indexOf(filterId),
                    ctx = this,
                    findItem;

                if (index === -1) {
                    ctx.selectedFiltersId.push(filterId);
                } else {
                    ctx.selectedFiltersId.splice(index, 1);
                    findItem = ctx.getFilterItem(ctx.filtersList, filterId);
                    if (findItem) {
                        findItem.item.childs.forEach(function (item) {
                            index = ctx.selectedFiltersId.indexOf(item.id);
                            if (index > -1) {
                                ctx.selectedFiltersId.splice(index, 1);
                            }
                            if (!!item.childs) {
                                item.childs.forEach(function (subItem) {
                                    index = ctx.selectedFiltersId.indexOf(subItem.id);
                                    if (index > -1) {
                                        ctx.selectedFiltersId.splice(index, 1);
                                    }
                                });
                            }
                        });
                    }
                }

                if (submit) {
                    this.filterData();
                }
            },
            refreshSelectedFilters: function() {
                let ctx = this,
                    item,
                    findItem;

                ctx.selectedFilters = [];
                for (let key in ctx.filtersList) {
                    if (!!ctx.filtersList[key].childs) {
                        for (let keyInner in ctx.filtersList[key].childs) {
                            item = ctx.filtersList[key].childs[keyInner];
                            if (ctx.selectedFiltersId.indexOf(item.id) !== -1) {
                                ctx.selectedFilters.push(item);
                                item['class'] = 'select slcted';
                                if (!!item.childs && item.childs.length > 0) {
                                    findItem = ctx.getFilterItem(ctx.filtersList, item.id);
                                    if (!findItem) {
                                        ctx.filtersList.push(item);
                                        item.childs.forEach(function (child, index) {
                                            if (ctx.selectedFiltersId.indexOf(child.id) !== -1) {
                                                ctx.selectedFilters.push(child);
                                                item.childs[index]['class'] = 'select slcted';
                                            } else {
                                                item.childs[index]['class'] = 'select';
                                            }
                                        });
                                    }
                                }
                            } else {
                                item['class'] = 'select';
                                findItem = ctx.getFilterItem(ctx.filtersList, item.id);
                                if (findItem) {
                                    ctx.filtersList.splice(findItem.index, 1);
                                }
                                if (!!item.childs) {
                                    item.childs.forEach(function (item) {
                                        if (ctx.selectedFiltersId.indexOf(item.id) === -1) {
                                            findItem = ctx.getFilterItem(ctx.filtersList, item.id);
                                            if (findItem) {
                                                ctx.filtersList.splice(findItem.index, 1);
                                            }
                                        }
                                    })
                                }
                            }
                        }
                    }
                }
            },
            filterData: function() {
                let ctx = this;

                ctx.getFullData();
                ctx.$root.doRequest(
                    'GET',
                    'company',
                    [],
                    {
                        'active': 1,
                        'property_id': ctx.selectedFiltersId
                    },
                    (response) => {
                        if (response.body.list.length > 0) {
                            for (let key in response.body.list) {
                                response.body.list[key]['id_formatted'] = 'comp-' + response.body.list[key].id;
                            }
                        }
                        ctx.companiesList = response.body;
                        ctx.refreshSelectedFilters();
                        ctx.$root.$emit('company-list-updated', ctx.companiesList);
                    }
                );
                window.history.pushState({},"", ctx.getUrl());
                ctx.$root.$emit('selected-filters-updated', this.selectedFiltersId);
            },
            getFullData: function () {
                let ctx = this;
                ctx.$root.doRequest(
                    'GET',
                    'company',
                    [],
                    {
                        'active': 1,
                        'property_id': ctx.selectedFiltersId,
                        'limit': 100500
                    },
                    (response) => {
                        if (response.body.list.length > 0) {
                            for (let key in response.body.list) {
                                response.body.list[key]['id_formatted'] = 'comp-' + response.body.list[key].id;
                            }
                        }
                        ctx.$root.$emit('company-full_list-updated', response.body);
                    }
                );
            },
            getMainUrl: function () {
                let path = location.pathname.replace(/\/filter.*/i, '');

                if (path === '/') {
                    path = '';
                }

                return location.origin + path;
            },
            getUrl: function () {
                let filterItem,
                    ctx = this,
                    url = [];

                this.selectedFiltersId.forEach(function (filterId) {
                    filterItem = ctx.getFilterItem(ctx.filtersList, filterId);
                    if (!!filterItem) {
                        url.push(filterItem.item.code);
                    } else {
                        ctx.filtersList.forEach(function (filterItem) {
                            if (!!filterItem.childs && filterItem.childs.length > 0) {
                                filterItem = ctx.getFilterItem(filterItem.childs, filterId);
                                if (!!filterItem) {
                                    url.push(filterItem.item.code);
                                }
                            }
                        });
                    }
                });

                return ctx.mainUrl + '/filter/' + (url.length > 0 ? url.join('-or-') : 'clear');
            }
        },
        mounted() {
            let ctx = this;

            if (!!ctx.$el.getAttribute('property_id')) {
                ctx.selectedFiltersId = ctx.$el.getAttribute('property_id').split(',');
                for (let key in ctx.selectedFiltersId) {
                    ctx.selectedFiltersId[key] = parseInt(ctx.selectedFiltersId[key]);
                }
            }

            ctx.mainUrl = ctx.getMainUrl();

            ctx.$root.$on('map-is-ready', function () {
                ctx.$root.doRequest(
                    'GET',
                    'property',
                    [],
                    {
                        'filtered': '1',
                        'with_url': '1',
                    },
                    (response) => {
                        let item, itemInner, itemInner2, itemInner3;

                        for (let key in response.body) {
                            item = response.body[key];
                            if (!item.parent_id) {
                                item['childs'] = [];
                                for (let keyInner in response.body) {
                                    itemInner = response.body[keyInner];
                                    if (itemInner.parent_id === item.id) {
                                        itemInner['class'] = 'select';
                                        itemInner['childs'] = [];
                                        for (let keyInner2 in response.body) {
                                            itemInner2 = response.body[keyInner2];
                                            if (itemInner2.parent_id === itemInner.id) {
                                                itemInner2['class'] = 'select';
                                                itemInner2['childs'] = [];
                                                for (let keyInner3 in response.body) {
                                                    itemInner3 = response.body[keyInner3];
                                                    if (itemInner3.parent_id === itemInner2.id) {
                                                        itemInner2['class'] = 'select';
                                                        itemInner2['childs'].push(itemInner3);
                                                    }
                                                }
                                                itemInner['childs'].push(itemInner2);
                                            }
                                        }
                                        item['childs'].push(itemInner);
                                    }
                                }
                                ctx.filtersList.push(item);
                            }
                        }
                        ctx.getFullData();
                        ctx.refreshSelectedFilters();
                        ctx.$root.$emit('filters-data-created', ctx.filtersList);
                    }
                );
            });
        }
    }
</script>
