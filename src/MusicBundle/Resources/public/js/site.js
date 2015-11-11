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

    e.preventDefault();
});
