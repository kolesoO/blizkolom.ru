            </div>
            {{ \DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs::render() }}
            <footer>
                {!! $menu['take'] !!}
                {!! $menu['to_points'] !!}
                {!! $footer['cvetmet_sections'] !!}
                {!! $footer['chermet_sections'] !!}
                <div class="line">{!! $copyright !!}</div>
            </footer>
            <v-form code="callback"></v-form>
            <div id="scrolltop" style="display: none;">
                <img src="/images/up-chevron.svg" alt="^">
            </div>
        </div>
        @if (env('APP_ENV') == 'prod')
            <!-- Yandex.Metrika counter --> <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(46445502, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/46445502" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
        @endif
        <script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" async></script>
        <script defer type="text/javascript" src="{{ asset("js/public/mobile/app.js") }}"></script>
    </body>
</html>
