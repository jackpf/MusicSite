// Audio JS
var currentPlaying = -1;

audiojs.events.ready(function() {
    var aj = audiojs.createAll({
        trackEnded: function() {
            $('.audiojs').each(function(index) {
                if (index == currentPlaying + 1) {
                    $(this).find('.play-pause').click();
                }
            });
        }
    });
    $('.audiojs .play-pause').on('click', function(){
        currentPlaying = $(this).parents('.audiojs').index('.audiojs');
        $.each(aj, function(index,val){
            if (index != currentPlaying && aj[index].playing) aj[index].pause();
        });
    });
});

// Search box
$('#search-link').click(function(e) {
    $box = $('#search-box');
    if (!$box.is(':visible')) {
        $box.slideDown();
        $box.children().first().focus();
        $(this).addClass('active');
    } else {
        $box.slideUp();
        $(this).removeClass('active');
    }

    e.preventDefault();
});

// Mobile menu
$('#mobile-menu-icon').click(function(e) {
    $('#mobile-menu').slideToggle();

    e.preventDefault();
});

// Other stuff
$(document).ready(function() {
    $(".fancybox").fancybox();

    $(".js-tooltip").tooltipster();

    $('nav li ul').hide().removeClass('fallback');
    $('nav li').hover(
        function () {
            $('ul', this).stop().slideDown(200);
        },
        function () {
            $('ul', this).stop().slideUp(200);
        }
    );

    $('.categories ul').hide().removeClass('fallback');
    $('.categories').hover(
        function () {
            $('ul', this).stop().fadeIn();
        },
        function () {
            $('ul', this).stop().fadeOut(200);
        }
    );
    var alreadyActive = false;
    $('.categories ul').hover(
        function() {
            var link = $('.categories a').first();
            alreadyActive = link.hasClass('active');
            link.addClass('active');
        },
        function() {
            if (!alreadyActive) {
                $('.categories a').first().removeClass('active');
            }
        }
    );
});

// Ajax stuff
$('.js-order-add').click(function(e) {
    if (!isLoggedIn) {
        return;
    } else {
        e.preventDefault();
    }

    $.get($(this).attr('href'))
        .done(function(data) {
            $('body').animate({
                scrollTop: 0
            }, 500, 'swing', function() {
                $('#basket-size').html(data.count);
            });
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            alert('Error adding item to order: ' + errorThrown);
        });
});

$('.js-favourite').click(function(e) {
    if (!isLoggedIn) {
        return;
    } else {
        e.preventDefault();
    }

    var $link = $(this);

    $.get($(this).attr('href'))
        .done(function(data) {
            if (!$link.hasClass('js-in-favourites')) {
                $link.html('Item added');
                setTimeout(function() {
                    $link.html('Remove from favourites');
                }, 2000);
            } else {
                $link.html('Item removed');
                setTimeout(function() {
                    $link.html('Add to favourites');
                }, 2000);
            }
            $link.toggleClass('js-in-favourites');
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            alert('Error favouriting item: ' + errorThrown);
        });
});

$('.js-favourite-delete').click(function(e) {
    if (!isLoggedIn) {
        return;
    } else {
        e.preventDefault();
    }

    var $link = $(this);

    $.get($(this).attr('href'))
        .done(function(data) {
            $link.parent().slideUp();
        })
        .fail(function(jqXHR, textStatus, errorThrown) {
            alert('Error favouriting item: ' + errorThrown);
        });
});

$('.js-expand-video').click(function(e) {
    if (!isLoggedIn) {
        return;
    } else {
        e.preventDefault();
    }

    $container = $('.image');
    $video = $('video');

    if (!$video.hasClass('expanded')) {
        $video.addClass('expanded');
        $container.addClass('container-expanded');
        $(this).html('-');
    } else {
        $video.removeClass('expanded');
        $container.removeClass('container-expanded');
        $(this).html('+');
    }
});