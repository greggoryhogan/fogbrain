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

    function checkForEmptyDetails() {
        $('.reminders .reminder .detail').each(function() {
            if($(this).find('.note').text() == '' && ($(this).find('.tag').hasClass('placeholder') || $(this).find('.tag').hasClass('none')) && $(this).text() == '') {
                $(this).addClass('empty');
            } else {
                $(this).removeClass('empty');
            }
        });
    }

    if($('.reminders').length) {
        if(!$('.reminders .reminder').length) {
            $('.edit-reminders').hide();
            $('.noreminders').show();
        } else {
            checkForEmptyDetails();
        }
    }

    
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
            $('.homepage-conditional-login-link').text("Go to "+site_js.user.data.display_name+"'s Reminders");
        }
    }

    //Update nav items
    
    if(user_id > 0) {
        $('.menu .login-link a').text('My Reminders');
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
            var profile_image_id = $('#profile_image_id').val();
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
                    'profile_image_id' : profile_image_id,
                }, success: function( data ) {
                    var response = JSON.parse(data);
                    if(response.page_url !== false) {
                        //$('.form-errors').html('<div class="error-notice">Profile updated!</div>');
                        window.location.href = response.page_url;
                    } else {
                        window.scrollTo(0, 0);
                        $('.form-errors').html('<div class="error-notice">There was an error saving your profile. Please reload and try again.</div>');
                    }
                }
            });
        }
       
    });

    //avatar
    $('#avatar').on('change', function(event) {
        $('#profile_image').addClass('is-loading');
		files = event.target.files;
		var cont = $(this).attr('data-cont');
		var name = $(this).attr('name');

		var data = new FormData();
		$.each(files, function(key, value)
		{
			data.append(key, value);
		});

		data.append('action', 'update_user_avatar' );
		data.append('type', $(this).data('type'));

		//$(cont).html('<img src="/assets/images/preloader.gif" />');

		$.ajax({
            url: site_js.ajax_url,
			type: "POST",             // Type of request to be send, called as method
			data: data, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(data) {
                $('#profile_image').removeClass('is-loading');
				var response = JSON.parse(data);
                if(response.status == 'ok') {
                    $('.avatar-error').removeClass('is-active').text('');
                    $('#profile_image').html('<img src="'+response.src+'" />');
                    $('#profile_image_id').val(response.attachment_id);
                    $('.delete-profile-photo').addClass('is-active');
                } else {
                    $('.avatar-error').addClass('is-active').text('There was an error uploading your image');
                }
			},
			error: function(jqXHR, textStatus, errorThrown) {
				// Handle errors here
				console.log('ERRORS: ' + textStatus);
                $('#profile_image').removeClass('is-loading');
				$('.avatar-error').addClass('is-active').html('There was an error uploading your image');
			}
		});
	
	});

    //delete profile pic
    $(document).on('click','.delete-profile-photo',function() {
        $('#profile_image').html('');
        $('#profile_image_id').val('');
        $(this).removeClass('is-active');
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
        
    });

    $(document).on('click','.close-notification',function() {
        $(this).parent().fadeOut().remove();
    });

    $(document).on('click','#add-reminder',function() {
        if($('.reminder-form').hasClass('is-active')) {
            $('.reminder-form').removeClass('is-active');
            $(this).text('Add Reminder');
            
            
            if($( '#tag' ).autocomplete( "instance" ) !== undefined) {
                $( '#tag' ).autocomplete( "destroy" );
            }
            if(!$('.reminders .reminder').length) {
                $('.noreminders').show();
            } else {
                $('.edit-reminders').show();
            }
            //window.location.href = window.location.href;
        } else {
            $('.tag-btn[data-tag="All"]').trigger('click');
            $('.reminder-form').addClass('is-active');
            $('.edit-reminders').hide();
            $(this).text('Cancel');
            if(!$('.reminders .reminder').length) {
                $('.noreminders').hide();
            }
        }
    });

    $(document).on('change','#input_1_1',function() {
        $('#gform_1').attr('data-type',$('#input_1_1').val());
    });

    //show form on page load if there are errrors
    if($('.gform_validation_errors').length) {
        $('#add-reminder').trigger('click');
    }

    var sortList = '';
    $(document).on('click','.edit-reminders',function() {
        $('.hidden-reminder').removeClass('hidden-reminder').addClass('showing-reminder');
        $('.tag-btn.is-active').removeClass('is-active');
        $( '.reminder-summary' ).addClass('is-editing');
        if($('.reminders .reminder').length < 5) {
            $('.done-editing.bottom-editor').hide();
        }
        $( '.reminder-summary .reminders .note').each(function() {
            if($(this).find('a').length) {
                $(this).find('a').each(function() {
                    var href = $(this).attr('href');
                    $(this).replaceWith(function() { 
                        return href;
                    });
                });
            }
        });
        $( '.reminder-summary .reminders .note').attr('contenteditable',true);
        $( '.reminder-summary .reminders' ).sortable({ disabled: false, handle: '.handle', update: function(event, ui) {
            sortList = $(this).sortable('toArray', {attribute: 'data-id'});    
          } 
        });
    });

    $(document).on('click','.done-editing',function() {
        $( '.reminder-summary .reminders' ).sortable('disable');
        $('.tag').autocomplete({source: []});
        var save = [];
        $( '.reminder-summary .reminder').each(function() {
            if(!$(this).hasClass('to-be-removed')) {
                var public = $(this).find('.public-val').is(':checked');
                save.push({
                    id : $(this).attr('data-id'),
                    tag : $(this).find('.tag').val(),
                    note : $(this).find('.note').text(),
                    public : public
                });
            }
        });
        $.ajax({
            url: site_js.ajax_url,
            type: 'post',
            data: {
                'action': 'update_reminder_categories',
                'save' : save,
            }, success: function( data ) {
                response = JSON.parse(data);
                $('.reminders').html(response.reminders);
                $( '.reminder-summary' ).removeClass('is-editing');
                $( '.reminders .reminder').each(function() {
                    $(this).addClass('showing-reminder');
                });
                $( '.reminder-summary .reminders .note').attr('contenteditable',false);
                if(!$('.reminders .reminder').length) {
                    $('.noreminders').show();
                } else {
                    checkForEmptyDetails();
                }
                //process_gpt_reminders
                
            }
        });
    });

    $(document).on('click','.reminder .delete',function() {
        $(this).parent().toggleClass('to-be-removed');
    });


    //chatgpt reminder form
    $(document).on('submit','#reminder-form',function(e) {
        e.preventDefault();
        $('#reminder-form .error-notice').remove();
        var prompt = $('#prompt').val();
        var note = $('#note').val();
        var tag = $('#tag').val();
        var public = $("#public").is(':checked');
        if(prompt == '') {
            $('#reminder-form').prepend('<div class="error-notice is-active">Please provide a reminder.</div>');
        } else {
            $('#reminder-form').addClass('is-loading')
            $.ajax({
                url: site_js.ajax_url,
                type: 'post',
                data: {
                    'action': 'add_gpt_reminder',
                    'prompt' : prompt,
                    'note' : note,
                    'public' : public,
                    'tag' : tag
                    
                }, success: function( data ) {
                    response = JSON.parse(data);
                    $('#reminder-form').removeClass('is-loading')
                    if(response.error == 1) {
                        console.log(response);
                        $('#reminder-form').prepend('<div class="error-notice is-active">'+response.message+'</div>');
                        $('#reminder-form').prepend('<div class="error-notice is-active">There was an error adding your reminder. Please try again.</div>');
                        
                    } else {
                        $('.reminder-form').removeClass('is-active');
                        $('#add-reminder').text('Add Reminder');
                        $('.edit-reminders').show();
                        $('#prompt').val('')
                        $('#note').val('');
                        $('#tag').val('');
                        $('.noreminders').hide();
                        $('.reminders').append(response.reminder);
                        //console.log(response);
                        $('.reminders .reminder:last-of-type').addClass('showing-reminder');
                        checkForEmptyDetails();
                    }
                }
            });
        }
       
    });

    $(document).on('click','.tag.placeholder',function() {
        $(this).find('input').val('');
    });


    $(document).on('click','.tag-btn', function() {
        var tag = $(this).attr('data-tag');
        if(tag == 'All') {
            $('.hidden-reminder').removeClass('hidden-reminder').addClass('showing-reminder');
            $('.is-active').removeClass('is-active');
        } else {
            $('.is-active').removeClass('is-active');
            $(this).addClass('is-active');
            $('.reminder').not('[data-tag="'+tag+'"]').removeClass('showing-reminder').addClass('hidden-reminder');
            $('.reminder[data-tag="'+tag+'"]').removeClass('hidden-reminder').addClass('showing-reminder');
        }
    });


    var availableTags = [
        "Accomplishments",
        "Birthdays",
        "Children",
        "Friends & Family",
        "Fun Facts",
        "Hobbies",
        "Misc",
        "Pets",
        "Special Events",
        "Travel",
        "None"
      ];
    //Add exams to cpt
    //var categorySelect = $('.tag');
    $('body').on('click', '.tag', function () {
        if ($(this).hasClass('ui-autocomplete-input')) {
            $(this).autocomplete('destroy')
        }
        $(this).autocomplete({
            source: availableTags,
            minLength: 0,
            position: {
                my: "left+0 top-3",
            },
            classes: {
                "ui-autocomplete": 'existing-reminder-select',
            },
            select: function(event, ui) {
                // Clear the input field
                $(this).val( ui.item.value );
                if ($(this).hasClass('ui-autocomplete-input')) {
                    $(this).autocomplete('destroy')
                }
                return false;
            }
        });
        $(this).val('');
        $(this).autocomplete('search', $(this).val())
    }).on('focus', '.tag', function () {
        if ($(this).hasClass('ui-autocomplete-input')) {
            $(this).autocomplete('destroy')
        }
        $(this).autocomplete({
            source: availableTags,
            minLength: 0,
            position: {
                my: "left+0 top-3",
            },
            classes: {
                "ui-autocomplete": 'existing-reminder-select',
            },
            select: function(event, ui) {
                // Clear the input field
                $(this).val( ui.item.value );
                if ($(this).hasClass('ui-autocomplete-input')) {
                    $(this).autocomplete('destroy')
                }
                return false;
            }
        });
        $(this).val('');
        $(this).autocomplete('search', $(this).val())
    });
    
    $('body').on('click', '#tag', function () {
        if ($(this).hasClass('ui-autocomplete-input')) {
            $(this).autocomplete('destroy')
        }
        $(this).autocomplete({
            source: availableTags,
            minLength: 0,
            classes: {
                "ui-autocomplete": 'add-reminder-select',
            },
            position: {
                my: "left+0 top-10",
            },
            select: function(event, ui) {
                // Clear the input field
                $(this).val( ui.item.value );
                if ($(this).hasClass('ui-autocomplete-input')) {
                    $(this).autocomplete('destroy')
                }
                return false;
            }
        });
        $(this).val('');
        $(this).autocomplete('search', $(this).val())
    }).on('focus', '#tag', function () {
        if ($(this).hasClass('ui-autocomplete-input')) {
            $(this).autocomplete('destroy')
        }
        $(this).autocomplete({
            source: availableTags,
            minLength: 0,
            classes: {
                "ui-autocomplete": 'add-reminder-select',
            },
            position: {
                my: "left+0 top-10",
            },
            select: function(event, ui) {
                // Clear the input field
                $(this).val( ui.item.value );
                if ($(this).hasClass('ui-autocomplete-input')) {
                    $(this).autocomplete('destroy')
                }
                return false;
            }
        });
        $(this).val('');
        $(this).autocomplete('search', $(this).val())
    });

    $('.accordion__item .accordion__title').on('click',function() {
        $(this).parent().toggleClass('is-open').toggleClass('is-closed');
    });

    $(document).on('click','#delete-my-account',function(e) {
        e.preventDefault();
        $('#delete-my-account').hide();
        $.ajax({
            url: site_js.ajax_url,
            type: 'post',
            data: {
                'action': 'delete_account_email'
            }, success: function( data ) {
                $('#delete-request-confirmation').html('We&rsquo;re sorry to see you go! Please check your email for the confirmation link.')
            }
        });
    });

    if($('.reminders').length) {
        var timer = 0;
        $('.reminders').find('.reminder').each(function() {
            var $this = $(this);
            setTimeout(function() {
                $this.addClass('showing-reminder');
            }, timer);
            timer += 100;
            
        });
    }

    $('#reminder-form .feather-info').on('click',function() {
        $(this).toggleClass('is-active');
        $('.reminder-support').toggleClass('is-active');
    });

})(jQuery); // Fully reference jQuery after this point.