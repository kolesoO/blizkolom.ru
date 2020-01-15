<template>
    <div class="callback-form disable">
        <img src="/images/cancel-gray.svg" alt="Выход">
        <div class="cb-title">Обратный звонок</div>
        <form>
            <p v-for="item in fieldsList">
                <label :for="item.code">{{ item.title }}</label>
                <input :name="item.code" :type="item.type" :value="item.default_value" required>
            </p>
            <button type="submit" class="btn-form enable" @click="submit">Перезвонить мне</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "v-form",
        data: function() {
            return {
                formCode: '',
                fieldsList: []
            }
        },
        methods: {
            submit: function () {
                alert('submit');
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
                    }
                );
            }
        }
    }
</script>
