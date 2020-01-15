require('./bootstrap');
require('./main');

if (!!document.getElementById("app")) {
    let requireComponent,
        componentConfig,
        componentName,
        app;

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
        Vue.component(componentName, componentConfig.default || componentConfig);
    });
    //end

    app = new Vue({
        el: "#app",
        data: {
            host: "http://blizkolom.local",
            apiVersion: 1
        },
        methods: {
            /**
             *
             * @param code
             * @returns {string}
             */
            getApiUrl: function(code) {
                return this.host + "/api/v" + this.apiVersion + "/" + code
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
        },
        mounted() {

        }
    });
}
