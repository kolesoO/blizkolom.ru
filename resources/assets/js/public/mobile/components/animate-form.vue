<template>
    <form class="form" @submit.prevent="submit">
        <div
                v-for="item in fieldsList"
                class="form-group"
        >
            <input
                    v-if="item.type === 'text' || item.type === 'email'"
                    :type="item.type"
                    :name="item.code"
                    :placeholder="item.placeholder"
                    :value="item.default_value"
                    @change="bindValue(item.code, $event.target.value)"
                    required
            >
            <textarea
                    v-if="item.type === 'textarea'"
                    :name="item.code"
                    :placeholder="item.placeholder"
                    @change="bindValue(item.code, $event.target.value)"
                    required
            >{{ item.default_value }}</textarea>
            <label class="control-label">{{ item.title }}</label>
            <i class="bar"></i>
        </div>
        <div class="button-container">
            <button type="submit" class="button">
                <span>Отправить</span>
            </button>
        </div>
        <div v-if="isSent && isSuccess" class="form-message">
            <span class="success">Успешно отправлено!<br>Спасибо за обратную связь!</span>
        </div>
        <div v-if="!isSuccess" class="form-message">
            <span class="error">Что-то пошло не так... Попробуйте повторить отправку формы</span>
        </div>
    </form>
</template>

<script>
    export default {
        name: "v-animate-form",
        data: function() {
            return {
                formCode: '',
                fieldsList: [],
                fieldsValue: {},
                isSent: false,
                isSuccess: true,
            }
        },
        methods: {
            bindValue: function (fieldCode, value) {
                this.fieldsValue[fieldCode] = value;
                this.isSent = false;
                this.isSuccess = true;
            },
            submit: function () {
                let ctx = this;

                ctx.$root.doRequest(
                    'POST',
                    'form/' + ctx.formCode + '/result',
                    [],
                    ctx.fieldsValue,
                    (response) => {
                        if (response.status === 200) {
                            ctx.isSent = true;
                            ctx.isSuccess = true;
                        } else {
                            ctx.isSuccess = false;
                        }
                    }
                );
            }
        },
        mounted() {
            if (!!this.$el.getAttribute('code')) {
                this.formCode = this.$el.getAttribute('code');

                let ctx = this;

                ctx.$root.doRequest(
                    'GET',
                    'form/' + this.formCode + '/fields',
                    [],
                    {
                        'active': 1
                    },
                    (response) => {
                        ctx.fieldsList = response.body;
                        ctx.fieldsList.forEach(function (item) {
                            ctx.fieldsValue[item.code] = '';
                        });
                    }
                );
            }
        }
    }
</script>
