(function($) {
    //mobile nav toggle
    $('.nav-toggle').click(function() {
        $('.nav-toggle, header, nav').toggleClass('is-active');
        console.log('toggle');
    });    

})(jQuery); // Fully reference jQuery after this point.