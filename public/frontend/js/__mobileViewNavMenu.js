// Mobile Menuveiw

(function () {
    $body = $('body');
    var $ltn__utilizeToggle = $('.ltn__utilize-toggle'),
        $ltn__utilize = $('.ltn__utilize'),
        $ltn__utilizeOverlay = $('.ltn__utilize-overlay'),
        $mobileMenuToggle = $('.mobile-menu-toggle');
    $ltn__utilizeToggle.on('click', function (e) {
        e.preventDefault();
        var $this = $(this),
            $target = $this.attr('href');
        $body.addClass('ltn__utilize-open');
        $($target).addClass('ltn__utilize-open');
        $ltn__utilizeOverlay.fadeIn();
        if ($this.parent().hasClass('mobile-menu-toggle')) {
            $this.addClass('close');
        }
    });
    $('.ltn__utilize-close, .ltn__utilize-overlay').on('click', function (e) {
        e.preventDefault();
        $body.removeClass('ltn__utilize-open');
        $ltn__utilize.removeClass('ltn__utilize-open');
        $ltn__utilizeOverlay.fadeOut();
        $mobileMenuToggle.find('a').removeClass('close');
    });
})();
