<template>
    <div class="form">
        <div
                v-for="item in fieldsList"
                class="form-group"
        >
            <input
                    v-if="item.type === 'text' || item.type === 'email'"
                    :type="item.type"
                    required="required"
                    :name="item.code"
                    :value="item.default_value"
            >
            <textarea
                    v-if="item.type === 'textarea'"
                    :name="item.code"
                    required="required"
            >{{ item.default_value }}</textarea>
            <label class="control-label">{{ item.title }}</label>
            <i class="bar"></i>
        </div>
        <div class="button-container">
            <button type="submit" class="button" @click="submit">
                <span>Отправить</span>
            </button>
        </div>
        <div class="form-message" v-show="!validForm">
            <span class="error">Заполните все поля!</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: "v-animate-form",
        data: function() {
            return {
                formCode: '',
                fieldsList: [],
                validForm: true
            }
        },
        methods: {
            submit: function () {
                let buf = true;
                if ($("input[type=text]").val()=='') {
                    $("input[type=text]").parent('.form-group').addClass('error');
                    buf = false
                };
                if ($("input.email").val()=='') {
                    $("input.email").parent('.form-group').addClass('error');
                    buf = false

                };
                if ($("textarea").val()=='') {
                    $("textarea").parent('.form-group').addClass('error');
                    buf = false
                };
                if (buf) {
                    $(".button-container .button span").text('Отправка...');
                    /*$.ajax({
                        type: 'post',
                        url: 'http://front.blizkolom.ru/dev/mail-send.php',
                        data: { name: $("input[type=text]").val(), email: $("input.email").val(), inaccuracy: $("textarea").val() },
                        success: function () {
                            $(".form-message").empty();
                            $(".button-container .button span").text('Отправить');
                            $(".form-message").html('<span class="success">Успешно отправлено!<br>Спасибо за обратную связь!</span>')
                        },
                        error:  function () {
                            $(".form-message").empty();
                            $(".button-container .button span").text('Отправить');
                            $(".form-message").html('<span class="error">Ошибка отправки! Напишите нам на blizkolom.ru@yandex.ru</span>')
                        }
                    });*/
                } else {
                    this.validForm = false;
                };
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
