(function($) {
    //mobile nav toggle
    $('.nav-toggle').on('click', function() {
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

})(jQuery); // Fully reference jQuery after this point.