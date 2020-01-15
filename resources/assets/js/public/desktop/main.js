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

    //need
    $('body').on('click', '.prc-cat', function() {
        $( this ).parent( ".price" ).children( ".prc-cat" ).removeClass("selected");
        $( this ).addClass("selected");
    });
    $('body').on('click', '.btn-callback', function () {
        $(".callback-form").toggleClass("disable");
        $(".callback-form-back").toggleClass("disable");
        $(".message-cb").remove();
    });
    //end

    $('.callback-form img').click(function() {
        $(".callback-form").toggleClass("disable");
        $(".callback-form-back").toggleClass("disable");
        $('.btn-form').addClass("enable")
    })
    $('.callback-form-back').click(function() {
        $(".callback-form").toggleClass("disable");
        $(".callback-form-back").toggleClass("disable");
        $('.btn-form').addClass("enable")
    })
    $(".card").on( "click", '#card-map .close',function() {
        $( '#card-map' ).remove();
    })
    $('#filters .title').click(function() {
        $('.fltr').removeClass('closed');
        $( '#filters .title' ).remove();
    })

    //need
    $('body').on('click', '.fltr div:first-child', function() {
        $(this).toggleClass( "opened" );
        $(this).parent('.fltr').children(".fltr div:last-child").toggleClass( "closed" );
    });
    //end

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
    $('.price td span').click(function() {
        $( '#card-map' ).remove();
        $( this ).parent( ".adr" ).parent( ".top" ).children( ".coord" ).after("<div id='card-map'><div class='close'><img src='/dev/resource/cancel-gray.svg'></div></div>");
        coord = ['36', '52'];
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
                        '<strong>Р  Р В°РЎРѓР С—Р С•Р В»Р С•Р В¶Р ВµР Р…Р С‘Р Вµ Р С—РЎС“Р Р…Р С”РЎвЂљР В° Р С—РЎР‚Р С‘Р ВµР СР В°</strong>',
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
    });

    //need
    $('body').on('click', '.fltr .select', function() {
        $(this).toggleClass( "slcted" );
    });
    //end

    $(".cards").on( "click", '.btn-load-more',function() {
        console.log('Р В·Р В°Р С–РЎР‚РЎС“Р В·Р С”Р В° Р ВµРЎвЂ°РЎвЂ <=20 Р С—РЎС“Р Р…Р С”РЎвЂљР С•Р Р† Р С—РЎР‚Р С‘Р ВµР СР В°');
    })
    $(".price tr").on( "click", 'span',function() {
        if ( $(this).is( ".reverse" ) ) {
            $(this).parent('td').parent('tr').next().remove();
        } else {
            $('.map-row').remove();
            $(this).parent('td').parent('tr').after( "<tr class='map-row'><td id='map' colspan='4'></td></tr>");
            buf = $(this).parent('td').parent('tr').attr("data-placemark");
            buf_arr = buf.split(';');
            var yandexMap, mapPlacemarks = [];
            function mapInit() {
                for (var i = 0; i < buf_arr.length; i++) {
                    buf_subarr = buf_arr[i].split(',');
                    if (i==0) {
                        coord = [buf_subarr[0],buf_subarr[1]];
                    };
                    mapPlacemarks[i] = new ymaps.Placemark([buf_subarr[0],buf_subarr[1]], {
                        iconContent: 'Р С•РЎвЂљ '+buf_subarr[2]+' РІвЂљР…'
                    }, {
                        preset: 'islands#darkBlueStretchyIcon'
                    });
                };
                yandexMap = new ymaps.Map("map", {
                    center: coord,
                    zoom: 9,
                    controls: []
                });
                yandexMap.behaviors.disable("scrollZoom");
                for (var i = 0; i < mapPlacemarks.length; i++) {
                    yandexMap.geoObjects.add(mapPlacemarks[i]);
                };
            }
            ymaps.ready(mapInit);
        };
        $(this).toggleClass( "reverse" );
    });

    $(".search-site").submit(function (event) {
        event.preventDefault();
        window.open('https://yandex.ru/search/?text=url%3Ahttps%3A%2F%2Fblizkolom.ru*%20' + $(".search-site input[type=search]").val());
    });

    $('body').on('click', '.region', function () {
        $(this).toggleClass("not-opened");
        $(this).toggleClass("opened");
    });
});

//lazy-load images
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
//end

//Service Worker
if ("serviceWorker" in navigator) {
    if (navigator.serviceWorker.controller) {
        console.log("active service worker found, no need to register");
    } else {
        // Register the service worker
        navigator.serviceWorker
            .register("sw.js", {
                scope: "./"
            })
            .then(function (reg) {
                console.log("Service worker has been registered for scope: " + reg.scope);
            });
    }
}
//end
