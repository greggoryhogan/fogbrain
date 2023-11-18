(function($) {
    //mobile nav toggle
    $('.nav-toggle').on('click', function() {
        if($('body').hasClass('is-active')) {
            setTimeout(function() {
                $('body').removeClass('is-active');
            },300)
        } else {
            $('body').addClass('is-active');
        }
        $('.nav-toggle, header, nav').toggleClass('is-active');
        console.log('toggle');
    });    

    $(document).on('click', 'body.is-active #Main', function() {
        setTimeout(function() {
                $('body').removeClass('is-active');
        }, 300);
        
        $('.nav-toggle, header, nav').removeClass('is-active');
    });    

})(jQuery); // Fully reference jQuery after this point.