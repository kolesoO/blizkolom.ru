$(document).ready(function(){

    $("body").on("click", ".fltr div:first-child", function(){
        $(this).toggleClass("opened");
        $(this).parent('.fltr').children(".fltr div:last-child").toggleClass("closed");
    });

    $('body').on('click', '.fltr .select a', function() {
        $(this).parent().toggleClass('slcted');
    });

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
    });

    $('.menu-btn').click(function() {
        $( "aside" ).toggleClass("disable");
        $( "html" ).toggleClass("no-scroll")
    });

    $('.info.left').click(function() {
        $( ".region" ).toggleClass("disable");
        $( "html" ).toggleClass("no-scroll")
    });

    $('.menu-smoke').click(function() {
        $( "aside" ).toggleClass("disable");
        $( "html" ).toggleClass("no-scroll")
    });

    $('.region-smoke').click(function() {
        $( ".region" ).toggleClass("disable");
        $( "html" ).toggleClass("no-scroll")
    });

    $('body').on('click', '.prc-cat', function() {
        $( this ).parent( ".price" ).children( ".prc-cat" ).each(function() {
            $(this).removeClass("selected");
            $($(this).attr('data-target')).addClass('closed');
        });

        $( this ).addClass("selected");
        $($(this).attr('data-target')).removeClass('closed');
    });

    $('body').on('click', '.btn-callback', function () {
        $(".callback-form").find('[name="company_id"]')
            .val($(this).attr('data-company_id'));

        $(".callback-form").find('[name="company_name"]')
            .val($(this).attr('data-company_name'))
            .attr('disabled', '');

        $(".callback-form").find('[name="company_id"]')[0].dispatchEvent(new Event('change'));
        $(".callback-form").find('[name="company_name"]')[0].dispatchEvent(new Event('change'));

        $(".callback-form").toggleClass("disable");
        $(".callback-form-back").toggleClass("disable");
        $(".message-cb").remove();
    });

    $('.callback-form img').click(function() {
        $(".callback-form").toggleClass("disable");
        $(".callback-form-back").toggleClass("disable");
        $('.btn-form').addClass("enable")
    });

    $('.callback-form-back').click(function() {
        $(".callback-form").toggleClass("disable");
        $(".callback-form-back").toggleClass("disable");
        $('.btn-form').addClass("enable")
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
            .register("/js/public/sw.js")
            .then(function (reg) {
                console.log("Service worker has been registered for scope: " + reg.scope);
            });
    }
}
//end
