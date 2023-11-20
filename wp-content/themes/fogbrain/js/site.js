(function($) {

    //variables
    var user_id = site_js.user_id;

    //mobile nav toggle
    $('.nav-container').on('click', function() {
        /*if($('body').hasClass('is-active')) {
            setTimeout(function() {
                $('body').removeClass('is-active');
            },300)
        } else {
            $('body').addClass('is-active');
        }*/
        $('.nav-toggle, header, nav').toggleClass('is-active');
        console.log('toggle');
    });    

    /*$(document).on('click', 'body.is-active #Main', function() {
        setTimeout(function() {
                $('body').removeClass('is-active');
        }, 300);
        
        $('.nav-toggle, header, nav').removeClass('is-active');
    });*/
    
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    function onlyLettersAndNumbers(str) {
        return /^[A-Za-z0-9-]*$/.test(str);
    }

    function getParameterByName(name) {
        name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regexS = "[\\?&]" + name + "=([^&#]*)";
        var regex = new RegExp(regexS);
        var results = regex.exec(window.location.search);
        if(results == null)
        return "";
        else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
    }  

    
    $(document).on('submit','#login',function(e) {
        e.preventDefault();
        $('.action-login .action-errors').html('');
        var email = $('#login-email').val();
        if(!isEmail(email)) {
            $('.action-login .action-errors').append('<p>Please provide a valid email</p>');
        } else {
            $('#emailsentto').text(email);
            $('.action-login').fadeOut();
            $('.error-notice').remove();
            setTimeout(() => {
                $('#login-code-input').val('');
                $('.action-processing').fadeIn();
            }, 500);
            
            $.ajax({
                url: site_js.ajax_url,
                type: 'post',
                data: {
                    'action': 'send_login_code',
                    'email' : email,
                    
                }, success: function( data ) {
                    $('.action-processing').fadeOut();
                    setTimeout(() => {
                        $('.action-code').addClass('is-active').fadeIn();
                    }, 300);
                }
            });
        }
       
    });

    $(document).on('click','.backtologin',function() {
        $('.action-code').fadeOut();
        $('.action-code').removeClass('is-active').fadeOut();
        setTimeout(() => {
            $('.action-login').addClass('is-active').fadeIn();
        }, 500);
    });

    $(document).on('submit','#login-code',function(e) {
        e.preventDefault();
        $('.action-code .action-errors').html('');
        var code = $('#login-code-input').val();
        if(code == '') {
            $('.action-code .action-errors').append('<p>Please provide a valid code or <span class="backtologin has-cursor">go back to enter your email</span>.</p>');
        } else {
            $.ajax({
                url: site_js.ajax_url,
                type: 'post',
                data: {
                    'action': 'check_login_code',
                    'login_code' : code,
                    
                }, success: function( data ) {
                    var response = JSON.parse(data);
                    if(response.code_status == 'invalid') {
                        $('.action-code .action-errors').html('<p>Your code was invalid. Please provide a valid code or <span class="backtologin has-cursor">go back to enter your email</span>.</p>');
                    } else {
                        if(response.user_status == 'error') {
                            $('.action-code .action-errors').html('<p>Your code was valid but we encountered an error. '+response.user_status+'</p>');
                        } else {
                            if(response.post_status == 'error') {
                                $('.action-code .action-errors').html('<p>Your account was created by we encountered an error. '+response.post_status+'</p>');
                            } else {
                                $('.action-code .action-errors').html('<p>Success, logging you in...</p>');
                                window.location.href = response.user_url;
                            }
                        }
                    }
                }
            });
        }
       
    });


    //Autosubmit form when code is in url
    if (getParameterByName('login-code').length > 0) {
        $('#login-code').trigger('submit');
    }

    if($('.homepage-conditional-login-link').length) {
        if(user_id > 0) {
            $('.homepage-conditional-login-link').text("Go to "+site_js.user.data.display_name+"'s Brain");
        }
    }

    //Update nav items
    
    if(user_id > 0) {
        $('.menu .login-link a').text('My Brain');
        $('.menu .logout-link a').attr('href',site_js.logout_link);
    } else {
        $('.menu .logged-in-only').remove();
        $('.menu .logout-link').remove();
    }

    //profile field page name ajax
    //setup before functions
    var url_timer; 
    var email_timer;                //timer identifier
    var typing_timeout = 1000;  //time in ms, 5 seconds for example
    
    //on keyup, start the countdown
    $('#email').on('keyup', function () {
        clearTimeout(email_timer);
        email_timer = setTimeout(check_email_validity, typing_timeout);
    });
    //on keydown, clear the countdown 
    $('#email').on('keydown', function () {
        clearTimeout(email_timer);
    });

    function check_email_validity() {
        var email = $('#email').val();
        if(!isEmail(email)) {
            $('.email-error').addClass('is-active').text('Please enter a valid email');
            $('#profile-form').addClass('has-errors');
        } else {
            $('.email-error').removeClass('is-active').text('');
            $('#profile-form').removeClass('has-errors');
        }
    }

    //on keyup, start the countdown
    $('#profile-url').on('keyup', function () {
        clearTimeout(url_timer);
        url_timer = setTimeout(check_the_profile_url, typing_timeout);
    });
    //on keydown, clear the countdown 
    $('#profile-url').on('keydown', function () {
        clearTimeout(url_timer);
    });

    //user is "finished typing," do something
    function check_the_profile_url () {
        //do something
        $('#profile-url').parent().removeClass('is-success').removeClass('is-error').addClass('is-loading');
        $('.profile-page-name-error').removeClass('is-active').text('');
        var profile_url = $('#profile-url').val().toLowerCase();
        var current_url = $('#profile-url').attr('data-current-url');
        if(onlyLettersAndNumbers(profile_url)) {
            $.ajax({
                url: site_js.ajax_url,
                type: 'post',
                data: {
                    'action': 'check_profile_url',
                    'profile_url' : profile_url,
                    'current_url' : current_url,
                    
                }, success: function( data ) {
                    var response = JSON.parse(data);
                    if(response.error !== 'false') {
                        $('#profile-url').parent().removeClass('is-loading').addClass('is-error');
                        $('.profile-page-name-error').addClass('is-active').text(response.error);
                        $('#profile-form').addClass('has-errors');

                    } else {
                        $('#profile-url').parent().removeClass('is-loading').addClass('is-success');
                        $('#profile-url').addClass('success');
                        $('#profile-form').removeClass('has-errors');
                        $('.update-page-name-desc').text(profile_url);
                    }
                }
            });
        } else {
            $('#profile-url').parent().removeClass('is-loading').addClass('is-error');
            $('.profile-page-name-error').addClass('is-active').text('Use only letters and numbers');
            $('#profile-form').addClass('has-errors');
        }
    }

    
    $('#share-code').bind('keyup', function(e) {
        //on letter number
        if(onlyLettersAndNumbers($('#share-code').val())) {
            $('#profile-form').removeClass('has-errors');
            $('.share-code-error').removeClass('is-active');
            $('.update-share-name').text($('#share-code').val());
        } else {
            $('#profile-form').addClass('has-errors');
            $('.share-code-error').addClass('is-active').text('Use only letters and numbers');
        }
    });

    $(document).on('submit','#profile-form',function(e) {
        e.preventDefault();
        if($(this).hasClass('has-errors')) {
            $('.form-errors').html('<div class="error-notice">There are errors in your profile.</div>');
        } else {
            var display_name = $('#name').val();
            var email = $('#email').val();
            var profile_url = $('#profile-url').val();
            var share_code = $('#share-code').val();
            var timezone = $('#timezone').val();
            $.ajax({
                url: site_js.ajax_url,
                type: 'post',
                data: {
                    'action': 'save_profile',
                    'display_name' : display_name,
                    'email' : email,
                    'profile_url' : profile_url,
                    'share_code' : share_code,
                    'timezone' : timezone,
                }, success: function( data ) {
                    window.scrollTo(0, 0);
                    var response = JSON.parse(data);
                    if(response.page_url !== false) {
                        //$('.form-errors').html('<div class="error-notice">Profile updated!</div>');
                        window.location.href = response.page_url;
                    } else {
                        $('.form-errors').html('<div class="error-notice">There was an error saving your profile. Please reload and try again.</div>');
                    }
                }
            });
        }
       
    });

    //copy share link
    $(document).on('click','.share-link .copy', function() {
        $(this).addClass('copied');
        setTimeout(function() {
            $('.share-link .copy').removeClass('copied');
        },100);
        var text_to_copy = $('#share-link').text();
        console.log(text_to_copy);
        if (!navigator.clipboard){
            var text_to_copy = $('#share-link').text();
            var tempTextarea = $('<textarea>');
            $('body').append(tempTextarea);
            tempTextarea.val(text_to_copy).select();
            document.execCommand('copy');
            tempTextarea.remove();
        } else{
            navigator.clipboard.writeText(text_to_copy);
        } 
        
    })


})(jQuery); // Fully reference jQuery after this point.