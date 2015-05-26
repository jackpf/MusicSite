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