$(document).ready(function(){

    var fltrs = [];
    var fltrs_stt = [];

    $('.fltr .open .select').each(function(i,elem) {
        fltrs[i] = $(elem).text();
        fltrs_stt[i] = false;
    });
    function submitFilter($formBlock, $block){
        var arCode = ["table_name", "filter_field_code"],
            data = {AJAX_ACTION: "smartFilter", COMPONENT_NAME: "Companies.Map", DATA: []};
        $formBlock.find(".select.slcted").each(function(index){
            var ob = {value: (!!$(this).attr("data-value") ? $(this).attr("data-value") : $(this).text())};
            for(var i in arCode){
                if(!!$(this).parents("ul").first().attr("data-" + arCode[i])){
                    ob[arCode[i]] = $(this).parents("ul").first().attr("data-" + arCode[i]);
                }
            }
            data.DATA.push(ob);
            if(!!$(this).parents("ul").first().attr("data-key")){
                data[$(this).parents("ul").first().attr("data-key")] = $(this).attr("data-value");
            }
            else if($(this).parents("ul").first().attr("data-additional") == "Y"){
                data["ADDITIONAL_SERVICES_" + index] = $(this).attr("data-value");
            }
        })
        console.log(data);
        $block = (!!$block ? $block : $("#catalog-wrap"));
        $block.addClass("loading");
        $.ajax({
            url: "",
            method: 'post',
            data: data,
            dataType: 'html',
            success: function(answer){
                $block.html(answer);
                $block.removeClass("loading");
            },
            error: function(a, b){
                console.log(a);
                console.log(b);
            }
        })
    };
    function fltr_rmv(ths) {
        buf = $(ths).text();
        $(ths).parent('.fltr').children(".fltr div:last-child").toggleClass( "closed" );
        $(ths).remove();
        $('#filters .select.slcted').each(function(i,elem) {
            if ($(elem).text()==buf) {
                $(elem).removeClass('slcted');
            };
        });
        fltrs_stt.forEach(function(item, i, fltrs_stt) {
            if (fltrs[i]==buf) {
                fltrs_stt[i]=false;
            };
        });
        submitFilter($("#filters"), $("#catalog-wrap"));
    };
    $(function() {
        var ViewHeight = 3*Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
        $(window).scroll(function() {
            if ($(this).scrollTop() > ViewHeight) {
                $('#scrolltop').fadeIn()
            } else {
                $('#scrolltop').fadeOut()
            }
        });
        $('#scrolltop').click(function() {
            $('body,html').animate({
                scrollTop: 0
            }, 800)
        })
        $('#toggle-map').click(function() {
            $("#toggle-map").fadeTo("slow", 0);
            yandexMap.container.fitToViewport();
        })
        $('.menu-btn').click(function() {
            $( "aside" ).toggleClass("disable");
            $( "html" ).toggleClass("no-scroll")
        })
        $('.info.left').click(function() {
            $( ".region" ).toggleClass("disable");
            $( "html" ).toggleClass("no-scroll")
        })
        $('.menu-smoke').click(function() {
            $( "aside" ).toggleClass("disable");
            $( "html" ).toggleClass("no-scroll")
        })
        $('.region-smoke').click(function() {
            $( ".region" ).toggleClass("disable");
            $( "html" ).toggleClass("no-scroll")
        })
        $('.prc-cat').click(function() {
            $( this ).parent( ".price" ).children( ".prc-cat" ).removeClass("selected");
            $( this ).addClass("selected");
            price_type = $( this ).attr("id").split('-')[0];
            company_id = $( this ).attr("id").split('-')[1];
            // С‚СѓС‚ РґРѕР»Р¶РµРЅ Р±С‹С‚СЊ AJAX Р·Р°РіСЂСѓР¶Р°СЋС‰РёР№ С†РµРЅС‹ РІ Р·Р°РІРёСЃРёРјРѕСЃС‚Рё РѕС‚ С‚РёРїР° РІС‹РІРѕРґРёРјС‹С… С†РµРЅ (РѕСЃРЅРѕРІРЅРѕРµ, С†РІРµС‚РјРµС‚, С‡РµСЂРјРµС‚ Рё С‚.Рї.) Рё id РєРѕРјРїР°РЅРёРё, Р·Р°РїРёСЃС‹РІР°РµРј РІ result
            result = '<table class="price-table"><thead><tr><th>РўРёРї</th><th>Р¦РµРЅР° <span>(&lt;50 РєРі)</span></th><th>Р¦РµРЅР° <span>(&gt;50 РєРі)</span></th></tr></thead><tbody><tr><td>' + price_type + ' 1</td><td>РўРµСЃС‚ <span>в‚Ѕ/РєРі</span></td><td>РўРµСЃС‚ <span>в‚Ѕ/РєРі</span></td></tr><tr><td>'+price_type+' 2</td><td>С‚РµСЃС‚ <span>в‚Ѕ/РєРі</span></td><td>С‚РµСЃС‚ <span>в‚Ѕ/РєРі</span></td></tr><tr><td>'+price_type+' 3</td><td>С‚РµСЃС‚ <span>в‚Ѕ/РєРі</span></td><td>С‚РµСЃС‚ <span>в‚Ѕ/РєРі</span></td></tr></tbody></table>';
            // РІС‹РІРѕРґ result РІ div-РєРѕРЅС‚РµР№РЅРµСЂ
            $( this ).parent( ".price" ).parent( ".top" ).parent( ".card" ).children( ".bottom" ).html( result )
        })
        $('.btn-callback').click(function() {
            company_cb = $( this ).parent( ".phone" ).parent( ".top" ).children( ".name" ).text();
            $(".callback-form [name='company']").val(company_cb);
            $(".callback-form").toggleClass("disable");
            $(".message-cb").remove()
        })
        $('.callback-form img').click(function() {
            $(".callback-form").toggleClass("disable");
            $('.btn-form').addClass("enable")
        })
        $(".card").on( "click", '#card-map .close',function() {
            $( '#card-map' ).remove();
        })
        $('#filters .title').click(function() {
            $('.fltr').removeClass('closed');
            $( '#filters .title' ).remove();
        })

//С„РёР»СЊС‚СЂС‹
        $( ".fltr div:first-child" ).click(function() {
            $(this).toggleClass( "opened" );
            $(this).parent('.fltr').children(".fltr div:last-child").toggleClass( "closed" );
        });
        $( ".fltr .btn" ).click(function() {
            $(this).parents('#filters').first().find("li.select.slcted").each(function(i,elem) {
                j = 0;
                fltrs.forEach(function(item, i, fltrs) {
                    if (fltrs[i]==$(elem).text()) {
                        j = i + 1;
                    };
                });
                if (fltrs_stt[j-1]==false) {
                    fltrs_stt[j-1]=true;
                };
            });
            $(this).parent('ul').parent('.open').toggleClass("closed");
            $(this).parent('ul').parent('.open').parent('.fltr').children(".opened").toggleClass("opened");
            $("#filters .fltr_slct").remove();
            fltrs_stt.forEach(function(item, i, fltrs_stt) {
                if (fltrs_stt[i]==true) {
                    $("#filters").prepend('<div class="fltr_slct"><div onclick="fltr_rmv(this);">' + fltrs[i] + '</div></div>');
                };
            });
            submitFilter($("#filters"), $("#catalog-wrap"));
        });



        $('.card .map-load').click(function() {
            $( '#card-map' ).remove();
            $( this ).parent( ".adr" ).parent( ".top" ).children( ".coord" ).after("<div id='card-map'><div class='close'><img src='/mobile/resource/cancel-gray.svg'></div></div>");
            buf = $( this ).parent( ".adr" ).parent( ".top" ).children( ".coord" ).text();
            coord = buf.split(', ');
            var yandexMap, mapPlacemarks = [];
            function mapInit() {
                yandexMap = new ymaps.Map("card-map", {
                    center: [coord[0], coord[1]],
                    zoom: 9,
                    controls: []
                });
                yandexMap.behaviors.disable("scrollZoom");
                mapPlacemark = new ymaps.Placemark(
                    [coord[0], coord[1]], {
                        balloonContentBody: [
                            '<address>',
                            '<strong>Р Р°СЃРїРѕР»РѕР¶РµРЅРёРµ РїСѓРЅРєС‚Р° РїСЂРёРµРјР°</strong>',
                            '<br/>',
                            '</address>'
                        ].join('')
                    }, {
                        iconLayout: "default#image",
                        iconImageHref: "https://static.blizkolom.ru/img/marker.svg",
                        iconImageSize: [31, 30],
                        iconImageOffset: [-15, -30],
                        balloonPane: "outerBalloon"
                    }
                );
                yandexMap.geoObjects.add(mapPlacemark);
            }
            ymaps.ready(mapInit);
        })
        $('.btn-form').click(function() {
            if($( this ).hasClass("enable")) {
                // Р·РґРµСЃСЊ РґРѕР»Р¶РЅР° Р±С‹С‚СЊ AJAX РѕС‚РїСЂР°РІРєР° Р·Р°СЏРІРєРё РЅР° РѕР±СЂР°С‚РЅС‹Р№ Р·РІРѕРЅРѕРє
                $( this ).after('<p class="message-cb">РЈСЃРїРµС€РЅРѕ РѕС‚РїСЂР°РІР»РµРЅРѕ! Р–РґРёС‚Рµ РїРѕРєР° РїСѓРЅРєС‚ РїСЂРёРµРјР° РІР°Рј РїРµСЂРµР·РІРѕРЅРёС‚.</p>');
                $( this ).removeClass("enable")
            }
        })
        /*$( ".search input" ).on('input keyup',function() {
            buf = $( this ).val();
            $.ajax({
                type: 'post',
                url: 'https://static.blizkolom.ru/mobile/get-region.php',
                data: { reg: buf },
                success: function (data) {
                    $(".search .result div").remove();
                    if (buf=='') {
                        data = '<div>РЈРєР°Р¶РёС‚Рµ РіРѕСЂРѕРґ!<div>'
                    } else if (data=='') {
                        data = '<div>Р“РѕСЂРѕРґ РЅРµ РЅР°Р№РґРµРЅ!<div>'
                    };
                    $(".search .result").append(data)
                }
            });
        })*/

        $( ".fltr .select" ).click(function() {
            $(this).toggleClass( "slcted" );
        });

        $(".regions").on( "click", '.reg-load',function() {
            window.location = $(this).attr("data-reg");
        })

        $(".cards").on( "click", '.btn-load-more',function() {
            console.log('Р·Р°РіСЂСѓР·РєР° РµС‰С‘ <=20 РїСѓРЅРєС‚РѕРІ РїСЂРёРµРјР°');
        })

    })

    // lazy-load images
    document.addEventListener("DOMContentLoaded", function() {
        var lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));

        if ("IntersectionObserver" in window) {
            let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        let lazyImage = entry.target;
                        lazyImage.src = lazyImage.dataset.src;
                        lazyImage.srcset = lazyImage.dataset.srcset;
                        lazyImage.classList.remove("lazy");
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });

            lazyImages.forEach(function(lazyImage) {
                lazyImageObserver.observe(lazyImage);
            });
        } else {
            // Possibly fall back to a more compatible method here
        }
    });

})