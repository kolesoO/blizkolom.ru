require('./bootstrap');

import listComponent from "./components/list.vue";
import detailComponent from "./components/detail.vue";

/**
 * @var router
 */

let requireComponent,
    componentConfig,
    componentName,
    app,
    routerData = [];

//подключение компонентов Vue
requireComponent = require.context(
    './components',
    true,
    /.*\.vue$/
);
requireComponent.keys().forEach((fileName) => {
    componentConfig = requireComponent(fileName);
    if (!!componentConfig.default.name) {
        componentName = componentConfig.default.name;
    } else {
        componentName = "v-" + fileName
            .replace(/^.\//, '')
            .replace(/\//g, '-')
            .replace(/\.\w+$/, '');
    }
    if (!!componentConfig.default.routerPath) {
        routerData.push({
            path: componentConfig.default.routerPath,
            component: componentName
        });
    }
    Vue.component(componentName, componentConfig.default || componentConfig);
});
//end

if (routerData.length > 0 || true) {
    router.addRoutes([
        {
            path: "/list",
            component: listComponent
        },
        {
            path: "/detail",
            component: detailComponent
        }
    ]);
}

app = new Vue({
    router,
    data: {
        api: {
            host: "http://blizkolom.local",
            version: "1"
        }
    },
    methods: {
        /**
         *
         * @param code
         * @returns {string}
         */
        getApiUrl: function(code) {
            return this.api.host + "/api/v" + this.api.version + "/" + code
        },
        /**
         *
         * @param method
         * @param apiCode
         * @param headersList
         * @param paramsList
         * @param promiseAction
         */
        doRequest: function(method, apiCode, headersList, paramsList, promiseAction) {
            let ctx = this;
            method = method.toLowerCase();
            if (typeof ctx.$http[method] == "function") {
                ctx.$http[method](ctx.getApiUrl(apiCode), {
                    headers: headersList,
                    params: paramsList
                })
                    .then(resp => {
                        promiseAction(resp);
                    })
                    .catch(error => {
                        alert(error);
                    });
            }
        }
    }
}).$mount("#app");