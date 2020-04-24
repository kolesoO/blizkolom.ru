<template>
    <div class="callback-form disable">
        <img src="/images/cancel-gray.svg" alt="Выход">
        <div class="cb-title">Обратный звонок</div>
        <form @submit.prevent="submit">
            <p v-for="item in fieldsList">
                <label :for="item.code" v-if="item.type !== 'hidden'">{{ item.title }}</label>
                <input
                        :name="item.code"
                        :type="item.type"
                        :placeholder="item.placeholder"
                        :value="item.default_value"
                        @change="bindValue(item.code, $event.target.value)"
                        required
                >
            </p>
            <button type="submit" class="btn-form">Перезвонить мне</button>
            <p v-if="isSent" class="green">Сообщение успешно отправлено!<br>Ждите, пока пункт приема вам перезвонит</p>
        </form>
    </div>
</template>

<script>
    export default {
        name: "v-form",
        data: function() {
            return {
                formCode: '',
                fieldsList: [],
                fieldsValue: {},
                isSent: false
            }
        },
        methods: {
            bindValue: function (fieldCode, value) {
                this.fieldsValue[fieldCode] = value;
                this.isSent = false;
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
                    'form/' + ctx.formCode + '/fields',
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
