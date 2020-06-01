        <footer>
            {!! $menu['take'] !!}
            {!! $menu['to_points'] !!}
            <div class="line">{!! $copyright !!}</div>
        </footer>
        <v-form code="callback"></v-form>
        <div id="scrolltop" style="display: none;">
            <img src="/images/up-chevron.svg" alt="^">
        </div>
        </div>
        <script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" async></script>
        <script defer type="text/javascript" src="{{ asset("js/public/mobile/app.js") }}"></script>
    </body>
</html>
