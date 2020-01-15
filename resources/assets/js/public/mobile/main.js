$(document).ready(function(){

    $("body").on("click", ".fltr div:first-child", function(){
        $(this).toggleClass("opened");
        $(this).parent('.fltr').children(".fltr div:last-child").toggleClass("closed");
    });
    var ViewHeight = 3*Math.max(document.documentElement.clientHeight, window.innerHeight || 0);
    $(window).scroll(function() {
        if ($(this).scrollTop() > window.innerHeight) {
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

})