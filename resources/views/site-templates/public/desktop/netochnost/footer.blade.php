            <footer>
                <div class="ftr-content">
                    {!! $menu['take'] !!}
                    {!! $menu['to_points'] !!}
                    {!! $footer['cvetmet_sections'] !!}
                    {!! $footer['chermet_sections'] !!}
                    <div class="line">{!! $copyright !!}</div>
                </div>
            </footer>
            <div class="callback-form-back disable"></div>
            <v-form code="callback"></v-form>
        </div>
        <script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" async></script>
        <script type="text/javascript" src="{{ asset("js/public/desktop/app.js") }}" async></script>
    </body>
</html>
