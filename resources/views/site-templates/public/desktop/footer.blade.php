            <footer>
                <div class="ftr-content">
                    {!! $menu['take'] !!}
                    {!! $menu['to_points'] !!}
                    <div class="line">{!! $footer["copyright"] !!}</div>
                </div>
            </footer>
            <div class="callback-form-back disable"></div>
            <v-form code="callback"></v-form>
        </div>
        @if (env('APP_ENV') == 'prod')
            <!-- Yandex.Metrika counter --> <script type="text/javascript" > (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)}) (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym"); ym(46445502, "init", { clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); </script> <noscript><div><img src="https://mc.yandex.ru/watch/46445502" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
        @endif
        <script type="text/javascript" src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" async></script>
        <script type="text/javascript" src="{{ asset("js/public/desktop/app.js") }}" async></script>
    </body>
</html>
