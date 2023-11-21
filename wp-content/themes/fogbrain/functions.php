<?php

define('BHFE_URL',get_bloginfo('url'));
define( 'THEME_DIR', get_template_directory() );
define( 'THEME_URI', get_template_directory_uri() );

global $fogbrain_user_id;
$fogbrain_user_id = get_current_user_id();

function register_bhfe_scripts() {
	//Theme version
	$version = wp_get_theme()->get('Version');
	//Deliver minified css for staging/prod
	$bhfe_dir = BHFE_URL.'/wp-content/themes/fogbrain';
	//Main Stylesheet
	wp_enqueue_style( 'fogbrain-main', $bhfe_dir.'/css/main.css', false, $version, 'all' );
	wp_enqueue_style('bootstrap-grid','https://cdn.jsdelivr.net/gh/dmhendricks/bootstrap-grid-css@4.1.3/dist/css/bootstrap-grid.min.css', false, '1.0', 'all');

	/*wp_register_script('input-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js', array('jquery'),'',true);
	if(is_page('login')) {
		wp_enqueue_script('input-mask');
	}*/
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script('fogbrain-main', $bhfe_dir.'/js/site.js', array('jquery','jquery-ui-sortable'),'',true);
	global $fogbrain_user_id, $current_user;
	wp_localize_script( 'fogbrain-main', 'site_js', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'user_id' => $fogbrain_user_id,
		'user' => $current_user,
		'logout_link' => wp_logout_url('/login')
	));
	
	wp_enqueue_style('fog-fonts','https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
	
}
add_action( 'wp_enqueue_scripts', 'register_bhfe_scripts' );

add_action('wp_head','bhfe_early_head_customization');
function bhfe_early_head_customization() { 
	?>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="Fog Brain">
	<meta name="theme-color" content="#008080" />
	<?php 
	if ( is_singular( 'brain' ) ) {
        echo '<meta name="robots" content="noindex, nofollow">';
    } ?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<?php 
}

add_theme_support( 'title-tag','menus', 'editor-color-palette' );

register_nav_menu( 'main-menu',		__( 'Main Menu', 'bhfe' ) );
register_nav_menu( 'footer-menu',	__( 'Footer Menu', 'bhfe' ) );

/* ---------------------------------------------------------------------------
 * Main Menu
 * --------------------------------------------------------------------------- */
function bhfe_wp_nav_menu( $location = 'main-menu', $depth = 4 ) {	
	$args = array( 
		'container' 		=> 'false',
		'menu_class'		=> 'menu', 
		'theme_location'	=> $location,
		'depth' 			=> $depth,
		'echo' 				=> false,
	);
	echo wp_nav_menu( $args ); 
}

add_action( 'login_enqueue_scripts', 'bhfe_login_branding' );
function bhfe_login_branding() { ?>
	<style type="text/css">
		.wp-core-ui .button, .wp-core-ui .button-secondary {
			color: #191102!important;
		}
		body.login div#login h1 a {
			background-image: url('<?php echo THEME_URI; ?>/logo.png');
			background-size: contain;
			width: 100%;
		}
		.login form .input, .login form input[type=checkbox], .login input[type=text] {
			font-family: 'Arial';
		}
		.submit .button {
			background: #fff!important;
			box-shadow: none!important;
			text-shadow: none!important;
			font-family: 'Arial';
			color: #191102!important;
			letter-spacing: 0px;
			border: 1px solid #191102!important;
		}
		.submit .button:hover,
		.submit .button:focus,
		.submit .button:active {
			background-color: #191102!important;
			color: #fff!important;
		}
		body.login {
			background: #fff;
		}
		.login form {
			border-color: #191102!important;
			border-radius: 3px;
		}
		input[type="color"], input[type="date"], input[type="datetime-local"], input[type="datetime"], input[type="email"], input[type="month"], input[type="number"], input[type="password"], input[type="search"], input[type="tel"], input[type="text"], input[type="time"], input[type="url"], input[type="week"], select, textarea {
			border-color: #191102!important;
			border-radius: 3px;
		}
		.message {
			border-color: #ffb300 !important;
			background: #ffb300 !important;
			box-shadow: none !important;
			border-radius: 3px;
		}
		#nav, #backtoblog {text-align: center!important;}
		a {color: #191102!important; text-decoration: none;}
		a:hover,
		a:hover,
		a:active {
			color: #191102!important;
			text-decoration: underline!important;
		}
	</style><?php 
} 

add_filter( 'login_headerurl', 'bhfe_loginlogo_url' );
function bhfe_loginlogo_url($url) {
     return get_bloginfo('url');
}

add_action('init','register_post_types');
function register_post_types() {
	$labels = array(
		"name" => __( "Brain", "" ),
		"singular_name" => __( "Brain", "flms" ),
		'all_items' => __( "Brains", "flms" ),
		'edit_item' => __( "Edit Brain", "flms" ),
		'update_item' => __( "Update Brain", "flms" ),
		'add_new' => __( "Add New Brain", "flms" ),
		'add_new_item' => __( "Add New Brain", "flms" ),
		'new_item_name' => __( "New Brain", "flms" ),
		'menu_name' => __( "Brains", "flms" ),
		'back_to_items' => __( "&laquo; All Brains", "flms" ),
		'not_found' => __( "No Brains found.", "flms" ),
		'not_found_in_trash' => __( "No Brains found in trash.", "flms" ),
	);
	$args = array(
		"label" => __( "Brain", "flms" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"has_archive" => true,
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "page",
		"map_meta_cap" => true,
		"query_var" => true,
		'hierarchical' => true,
		'has_archive' => false,
		"rewrite" => array( "slug" => 'u', ), //"with_front" => false 
		//"taxonomies" => array( "supplier" ),
	);
	register_post_type( "brain", $args );
}

/**
 * Add rewrite rules for course structure
 * This could probably be simplified, but it works!
 */
add_action('init','add_rewrite_rules');
function add_rewrite_rules() {
	add_rewrite_rule(
		"^u/([^/]+)/share/([^/]+)/?$",
		'index.php?brain=$matches[1]&share=$matches[2]',
		'top'
	);
}

add_filter( 'query_vars', 'add_version_query_var');
function add_version_query_var($query_vars) {
	$query_vars[] = 'share';
	return $query_vars;
}

add_action('template_redirect','check_login_page_for_user');
function check_login_page_for_user() {
	global $fogbrain_user_id, $post, $wp;
	if(is_page('login')) {
		if($fogbrain_user_id > 0) {
			$query = new WP_Query(array(
				'post_type' => 'brain',
				'posts_per_page' => 1,
				'author' =>  $fogbrain_user_id,
			));
		
			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					$redirect = get_permalink(get_the_ID());
					if(isset($_GET['access-error'])) {
						$redirect .= '?access-error='.sanitize_text_field( $_GET['access-error'] );
					}
					wp_redirect( $redirect );
					exit;
				}
			}	
			wp_reset_postdata();
		}	
	} else if(is_page('profile')) {
		if($fogbrain_user_id == 0) {
			wp_redirect( get_bloginfo('url').'/login/?logged-out=profile');
		}	
	} else if($post->post_type == 'brain') {
		$login_redirect = get_bloginfo('url').'/login?access-error=';
		//redirect users from viewing other peoples pages
	 	if($post->post_author != $fogbrain_user_id) {
			if(isset($wp->query_vars['share'])) {
				$share_code = $wp->query_vars['share'];
				$saved_share_code = get_post_meta($post->ID,'share_code',true);
				if($share_code != $saved_share_code) {
					wp_redirect($login_redirect.'invalid-code');	
				}
			} else {
				wp_redirect($login_redirect.'private');	
			}
			
		}
	}
}

/**
 * Extend wp login time to 1 year
 */
add_filter( 'auth_cookie_expiration', 'fog_auth_cookie_expiration_30_days', 10, 3 );
function fog_auth_cookie_expiration_30_days( $seconds, $user_id, $remember_me ) {
    if ( $remember_me ) {
        return 12 * MONTH_IN_SECONDS;
    }
    return $seconds;
}

/**
 * Set email content types to be html
 */
add_filter( 'wp_mail_content_type','fogbrain_email_types' );
function fogbrain_email_types(){
    return "text/html";
}

add_filter( 'wp_mail_from_name', 'my_mail_from_name' );
function my_mail_from_name( $name ) {
    return "Fog Brain";
}

add_action('after_setup_theme', 'remove_admin_bar');
function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}

add_action( 'wp_ajax_nopriv_send_login_code', 'send_login_code_callback' );
add_action( 'wp_ajax_send_login_code', 'send_login_code_callback' );
function send_login_code_callback() {
	$email = sanitize_text_field($_POST['email']);
	$new_transient = false;
	while(!$new_transient) {
		$random_number = sprintf("%06d", mt_rand(1, 999999));
		$transient_string = get_transient( 'access-code-'.$random_number );
		if($transient_string === false) {
			set_transient( 'access-code-'.$random_number, $email, 10 * MINUTE_IN_SECONDS );
			$message = '<p style="text-align: center;">Your login code for Fog Brain is:</p>';
			$message .= '<h2 style="text-align: center; letter-spacing: 8px;">'.$random_number.'</h2>';
			$message .= '<p style="text-align: center;">This code is valid for 10 minutes. Please go back to the Fog Brain login page and enter your code.</p>';
			$message .= '<p style="text-align: center;">You can also click the link below to log in:<br><a href="'.get_bloginfo('url').'/login/?login-code='.$random_number.'">'.get_bloginfo('url').'/login/?login-code='.$random_number.'</a></p>';
			$message .= '<p style="text-align: center;">If you did not request this login code, you can ignore this email. Your page is only available through your email account.</p>';
			$message .= '<p style="text-align: center;">Thank you for using Fog Brain</p>';
			wp_mail($email,'Your Fog Brain login code',$message);
			$new_transient = true;
		}
	}
	wp_die();
}


add_action( 'wp_ajax_nopriv_check_login_code', 'check_login_code_callback' );
add_action( 'wp_ajax_check_login_code', 'check_login_code_callback' );
function check_login_code_callback() {
	$code = sanitize_text_field($_POST['login_code']);
	$transient = get_transient( 'access-code-'.$code );
	if($transient === false) {
		echo json_encode(
			array(
				'code_status' => 'invalid',
				'code' => $code,
			)
		);
		wp_die();
	} else {
		$user = get_user_by('email',$transient);
		if($user === false) {
			//new user
			$username = strstr($transient, '@', true);
			$user_args = array(
				'user_login' => $transient,
				'user_email' => $transient,
				'user_pass' => wp_generate_password(24, true ),
				'display_name' => $username
			);
			$user_id = wp_insert_user($user_args);
			if( is_wp_error( $user_id ) ) {
				echo json_encode(
					array(
						'code_status' => 'valid',
						'user_status' => 'error',
						'error' => $user_id->get_error_message()
					)
				);
				wp_die();
			} else {
				//Log them in
				wp_set_auth_cookie( $user_id, 1, is_ssl() );

				//create their post
				$post_args = array(
					'post_author' => $user_id,
					'post_title' => "$username",
					'post_type' => 'brain',
					'post_status' => 'publish'
				);
				$post_id = wp_insert_post($post_args);
				if( is_wp_error( $post_id ) ) {
					echo json_encode(
						array(
							'code_status' => 'valid',
							'user_status' => 'success',
							'post_status' => 'error',
							'error' => $user_id->get_error_message()
						)
					);
					wp_die();
				} else {
					add_user_meta($user_id,'user_profile_page',$post_id);
					delete_transient( 'access-code-'.$code );
					echo json_encode(
						array(
							'code_status' => 'valid',
							'user_status' => 'success',
							'post_status' => 'success',
							'user_url' => get_the_permalink($post_id).'?registration=successful'
						)
					);
					wp_die();
				}
			}
		} else {
			//user exists
			wp_set_auth_cookie( $user->ID, 1, is_ssl() );
			$args = array(
				'author' => $user->ID,
				'post_type' => 'brain',
			);
			$author_posts = new WP_Query( $args );
			if( $author_posts->have_posts() ) {
				while( $author_posts->have_posts() ) { 
					$author_posts->the_post();
					delete_transient( 'access-code-'.$code );
					echo json_encode(
						array(
							'code_status' => 'valid',
							'user_status' => 'success',
							'post_status' => 'success',
							'user_url' => get_the_permalink(get_the_ID()).'?login-action=returning-user'
						)
					);
				}
				wp_reset_postdata();
				wp_die();
			}
		}
	}
}

add_action( 'wp_ajax_nopriv_check_profile_url', 'check_profile_url_callback' );
add_action( 'wp_ajax_check_profile_url', 'check_profile_url_callback' );
function check_profile_url_callback() {
	$profile_url = sanitize_text_field( $_POST['profile_url'] );
	$current_url = sanitize_text_field( $_POST['current_url'] );
	if($profile_url == $current_url) {
		echo json_encode(
			array(
				'error' => 'false',
			)
		);
		wp_die();
	} else if($profile_url !== '') {
		$args = array(
			'pagename' => $profile_url,
			'post_type' => 'brain',
		);
		$author_posts = new WP_Query( $args );
		if( $author_posts->have_posts() ) {
			echo json_encode(
				array(
					'error' => 'That page name has been taken, please try another.',
					'title' => $slug
				)
			);
			wp_die();
			wp_reset_postdata();
		} else {
			echo json_encode(
				array(
					'error' => 'false',
				)
			);
			wp_die();
		}
	} else {
		echo json_encode(
			array(
				'error' => 'Please enter a valid profile page name',
			)
		);
		wp_die();
	}
}

/**remove email for changed email notification, we have our own */
add_filter('send_email_change_email','__return_false');

add_action( 'wp_ajax_nopriv_save_profile', 'save_profile_callback' );
add_action( 'wp_ajax_save_profile', 'save_profile_callback' );
function save_profile_callback() {
	$display_name = sanitize_text_field( $_POST['display_name'] );
	$email = sanitize_email( $_POST['email'] );
	$profile_url = sanitize_text_field( $_POST['profile_url'] );
	$share_code = sanitize_text_field( $_POST['share_code'] );
	$timezone = sanitize_text_field( $_POST['timezone'] );
	$profile_image_id = sanitize_text_field($_POST['profile_image_id']);
	global $current_user;
	$current_email = $current_user->user_email;

	//delete old image if necessary
	if($profile_image_id == '') {
		//they deleted it
		$profile_image = get_user_meta($current_user->ID, 'user_avatar', true);
		if ($profile_image) {
			$profile_image = json_decode($profile_image);
			if (isset($profile_image->attachment_id)) {
				wp_delete_attachment($profile_image->attachment_id, true);
			}
		}
		delete_user_meta($current_user->ID, 'user_avatar');
	}
	if($current_email != $email) {
		update_user_meta($current_user->ID,'email_recovery',$current_email);
		$message = '<p style="text-align:center;">Your Fog Brain email has been updated to '.$email.'. If you did not perform this action, please follow the link below to recover your email.</p>';
		$message .= '<p style="text-align:center;"><a href="'.get_bloginfo('url').'?email-recovery='.$current_user->ID.'">'.get_bloginfo('url').'?email-recovery='.$current_user->ID.'</a></p>';
		$message .= '<p style="text-align: center;">If you requested this email address change, you can ignore this email.</p>';
		$message .= '<p style="text-align: center;">Thank you for using Fog Brain</p>';
		wp_mail($current_email, 'Your Fog Brain Email has been updated',$message);
	}
	wp_update_user( array( 'ID' => $current_user->ID, 'display_name' => $display_name, 'user_email' => $email ) );
	update_user_meta( $current_user->ID, 'timezone', $timezone );
	$profile_page_id = get_user_meta($current_user->ID,'user_profile_page',true);
	$args = array(
		'ID'           => $profile_page_id,
		'post_name' => $profile_url,
		'post_title' => "$display_name's Foggy Brain",
	);
	wp_update_post( $args );
	update_post_meta($profile_page_id,'share_code',$share_code);

	

	echo json_encode(
		array(
			'page_url' => get_permalink($profile_page_id).'?profile=updated',
		)
	);
	wp_die();
}

/** upload user avatar, thanks to gdarko - https://gist.github.com/gdarko/f858686eae428c1e56076e5e47b1b6d4 */
add_action( 'wp_ajax_nopriv_update_user_avatar', 'update_user_avatar_callback' );
add_action( 'wp_ajax_update_user_avatar', 'update_user_avatar_callback' );
function update_user_avatar_callback() {
	global $current_user;
	$f = 0;
    $_FILES[$f] = $_FILES[0];
    
    $user = new WP_User(get_current_user_id());
    $json['status'] = 'error';
  
    //Check if the file is available && the user is logged in
    if (!empty($_FILES[$f]) && $user->ID > 0) {
      
        $json = array();
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
       
        //Handle the media upload using WordPress helper functions
        $attachment_id = media_handle_upload($f, 0);
        $json['aid']   = $attachment_id;
        
        //If error
        if (is_wp_error($attachment_id)) {
            $json['error'] = "Error.";
        } else {
            //delete current
            $profile_image = get_user_meta($current_user->ID, 'user_avatar', true);
            if ($profile_image) {
                $profile_image = json_decode($profile_image);
                if (isset($profile_image->attachment_id)) {
                    wp_delete_attachment($profile_image->attachment_id, true);
                }
            }
            
            //Generate attachment in the media library
            $attachment_file_path = get_attached_file($attachment_id);
            $data                 = wp_generate_attachment_metadata($attachment_id, $attachment_file_path);
            
            //Get the attachment entry in media library
            $image_full_attributes  = wp_get_attachment_image_src($attachment_id, 'full');
            $image_thumb_attributes = wp_get_attachment_image_src($attachment_id, 'smallthumb');
            
            $arr = array(
                'attachment_id' => $attachment_id,
                'url' => $image_full_attributes[0],
                'thumb' => $image_thumb_attributes[0]
            );
            
            //Save the image in the user metadata
            update_user_meta($current_user->ID, 'user_avatar', json_encode($arr));
            
            $json['src']    = $arr['thumb'];
			$json['attachment_id'] = $attachment_id;
            $json['status'] = 'ok';
        }
    }
    //Output the json
    die(json_encode($json));
}


add_action('init','check_for_email_recovery');
function check_for_email_recovery() {
	if(isset($_GET['email-recovery'])) {
		$user_id = sanitize_text_field( $_GET['email-recovery'] );
		if($user_id != '' && $user_id > 0) {
			//log the user out everywhere
			$previous_email = get_user_meta($user_id,'email_recovery',true);
			if($previous_email) {
				$sessions = WP_Session_Tokens::get_instance($user_id);
				$sessions->destroy_all();
				wp_update_user( array( 'ID' => $user_id, 'user_email' => $previous_email ) );
				delete_user_meta($user_id,'email_recovery');
				wp_redirect( get_bloginfo('url').'/login/?action=email_recovered');
			}
		}
	}
}

function fog_error_notifications() {
	if(isset($_GET['login-action'])) {
		$action = sanitize_text_field($_GET['login-action']);
		if($action == 'returning-user') {
			global $current_user;
			echo '<div class="col-12 col-md-8">';
				echo '<p class="error-notice">Welcome back, '.$current_user->display_name.'!<span class="close-notification"></span></p>';
			echo '</div>';
		}
	}
	if(isset($_GET['access-error'])) {
		echo '<div class="col-12 col-md-8">';
			$error = sanitize_text_field($_GET['access-error']);
			$message = 'You don&rsquo;t have access to that person&rsquo;s reminders.';
			if($error == 'invalid-code') {
				$message .= ' If you were given a share code, check with the user to confirm their profile is set to public.';
			} else {
				$message .= ' If you were trying to access your own page, please log in below.';
			}
			echo '<p class="error-notice">'.$message.'<span class="close-notification"></span></p>';
		echo '</div>';
	}
	if(isset($_GET['logged-out'])) {
		$error = sanitize_text_field($_GET['logged-out']);
		if($error == 'profile') {
			echo '<div class="col-12 col-md-8">';
				echo '<p class="error-notice">Please log in to access your profile. <span class="close-notification"></span></p>';
			echo '</div>';
		}
	}
	if(isset($_GET['action'])) {
		$error = sanitize_text_field($_GET['action']);
		if($error == 'email_recovered') {
			echo '<div class="col-12 col-md-8">';
				echo '<p class="error-notice">Your email has been recovered. Please log in again using the recent recovery email address.</p>';
			echo '</div>';
		}
	} 
	if(isset($_GET['registration'])) {
		$error = sanitize_text_field($_GET['registration']);
		if($error == 'successful') {
			echo '<div class="col-12 col-md-8">';
				echo '<p class="error-notice">Registration successful! Any time you need to log in again, just use the email in your account. You can configure your account settings on your <a href="/profile" title="profile">profile page</a>.<span class="close-notification"></span></p>';
			echo '</div>';
		}
	} else if(isset($_GET['profile'])) {
		$action = sanitize_text_field($_GET['profile']);
		if($action == 'updated') {
			echo '<div class="col-12 col-md-8">';
				echo '<p class="error-notice">Profile updated!<span class="close-notification"></span></p>';
			echo '</div>';
		}
		
	} 
}


add_filter( 'gform_pre_render_1', 'populate_events' ); //WI Special Order Form
add_action( 'gform_admin_pre_render_1', 'populate_events' );
function populate_events( $form ) {
	//wp_enqueue_script('firearm-dropdown'); //We not longer do this
    foreach ( $form['fields'] as &$field ) {
        if ( $field->type != 'select' || strpos( $field->cssClass, 'event-types' ) === false ) {
            continue;
        }
		$my = false;
		if(strpos( $field->cssClass, 'primary' ) !== false) {
			$my = true;
		}
        $field->placeholder = 'specify an event';
		$field->choices = get_event_choices($my);
	}
    return $form;
}

add_filter( 'gform_pre_render_1', 'populate_relationships' ); //WI Special Order Form
add_action( 'gform_admin_pre_render_1', 'populate_relationships' );
function populate_relationships( $form ) {
	//wp_enqueue_script('firearm-dropdown'); //We not longer do this
    foreach ( $form['fields'] as &$field ) {
        if ( $field->type != 'select' || strpos( $field->cssClass, 'relationship-types' ) === false ) {
            continue;
        }
        $field->placeholder = 'specify the relationship';
		$my = false;
		if(strpos( $field->cssClass, 'primary' ) !== false) {
			$my = true;
		}
		$field->choices = get_relationship_choices($my);
	}
    return $form;
}
/**
 * Function to get rifle choices for dropdown, pulled from frontend and wp-admin
 */
function get_event_choices($my) {
	$event_choices = array();
	if($my) {
		$event_choices[] = array( 'text' => 'My birthday', 'value' => 'birthday');
		$event_choices[] = array( 'text' => 'My wedding', 'value' => 'wedding');
		$event_choices[] = array( 'text' => 'Other', 'value' => 'other');
		/*$event_choices[] = array( 'text' => 'My high school graduation', 'value' => 'hs-graduation');
		$event_choices[] = array( 'text' => 'My college graduation', 'value' => 'college-graduation');*/
	} else {
		$event_choices[] = array( 'text' => 'birthday', 'value' => 'birthday');
		$event_choices[] = array( 'text' => 'wedding', 'value' => 'wedding');
		$event_choices[] = array( 'text' => 'other', 'value' => 'other');
	}
	return $event_choices;
}

function get_relationship_choices($my) {
	$my_text = '';
	if($my) {
		$my_text = 'My ';
	}
	$relationship_choices = array();
	$relationship_choices[] = array( 'text' => $my_text.'wife', 'value' => 'wife');
	$relationship_choices[] = array( 'text' => $my_text.'husband', 'value' => 'husband');
	$relationship_choices[] = array( 'text' => $my_text.'spouse', 'value' => 'spouse');
	$relationship_choices[] = array( 'text' => $my_text.'friend', 'value' => 'friend');
	$relationship_choices[] = array( 'text' => $my_text.'girlfriend', 'value' => 'girlfriend');
	$relationship_choices[] = array( 'text' => $my_text.'boyfriend', 'value' => 'boyfriend');
	$relationship_choices[] = array( 'text' => $my_text.'mother', 'value' => 'mother');
	$relationship_choices[] = array( 'text' => $my_text.'father', 'value' => 'father');
	$relationship_choices[] = array( 'text' => $my_text.'parent', 'value' => 'parent');
	$relationship_choices[] = array( 'text' => $my_text.'grandmother', 'value' => 'grandmother');
	$relationship_choices[] = array( 'text' => $my_text.'grandfather', 'value' => 'grandfather');
	$relationship_choices[] = array( 'text' => $my_text.'grandparent', 'value' => 'grandparent');
	$relationship_choices[] = array( 'text' => $my_text.'nephew', 'value' => 'nephew');
	$relationship_choices[] = array( 'text' => $my_text.'niece', 'value' => 'niece');
	$relationship_choices[] = array( 'text' => $my_text.'cousin', 'value' => 'cousin');
	$relationship_choices[] = array( 'text' => $my_text.'aunt', 'value' => 'aunt');
	$relationship_choices[] = array( 'text' => $my_text.'uncle', 'value' => 'uncle');
	$relationship_choices[] = array( 'text' => $my_text.'dog', 'value' => 'dog');
	$relationship_choices[] = array( 'text' => $my_text.'cat', 'value' => 'cat');
	$relationship_choices[] = array( 'text' => $my_text.'pet', 'value' => 'pet');
	return $relationship_choices;
}

/** create reminder and delete entry */
add_action( 'gform_after_submission_1', 'create_reminder', 10, 2 );
function create_reminder( $entry, $form ) {
	global $current_user;
	$entry_id = $entry['id'];
	$relationship = $entry['1'];
	if($relationship == 'myself') {
		$event = $entry['4']; // or 19
	} else {
		$event = $entry['19']; // or 19
	}
	$date = $entry['10'];
	$notes = $entry['11'];
	$primary_relation = $entry['12'] ?? '';
	$primary_relation_name = $entry['13'] ?? '';
	$secondary_relation = $entry['15'] ?? '';
	$secondary_relation_name = $entry['17'] ?? '';
	$other = $entry['20'] ?? '';
	$iswas = $entry['21'] ?? '';
	$profile_page_id = get_user_meta($current_user->ID,'user_profile_page',true);
	$saved_reminders = maybe_unserialize(get_post_meta($profile_page_id,'reminders',true));
	if(!is_array($saved_reminders)) {
		$saved_reminders = array();
	}
	if(!isset($saved_reminders["{$relationship}"])) {
		$saved_reminders["{$relationship}"] = array();
	}
	if(!in_array($entry['id'],$saved_reminders["{$relationship}"])) {
		//new reminders
		$saved_reminders["{$relationship}"]["{$entry_id}"] = array(
			'event' => $event, 
			'date' => $date, 
			'iswas' => $iswas, 
			'primary_relation' => $primary_relation,  
			'primary_relation_name' => $primary_relation_name, 
			'secondary_relation' => $secondary_relation,  
			'secondary_relation_name' => $secondary_relation_name, 
			'other' => $other, 
			'note' => $notes
		);
	} else {
		//update the reminder
	}
	update_post_meta($profile_page_id,'reminders',$saved_reminders);
	//delete entry
    GFAPI::delete_entry( $entry['id'] );
	
}

function print_user_reminders($reminders, $author_id) {
	global $current_user;
	$user_timezone = get_user_meta($current_user->ID,'timezone',true);
	if($user_timezone == '') {
		$user_timezone = 'America/New_York';
	}
	$tz  = new DateTimeZone($user_timezone);
	foreach($reminders as $category => $reminder_items) {
		if(!empty($reminder_items)) {
			echo '<div class="reminder-category '.$category.'">';
				echo '<h2>';
				if($category == 'myself') {
					echo 'About Me';
				} else if($category == 'primary') {
					echo '<span>Primary<span class="desktop-only"> Relationships</span></span>';
				} else if($category == 'secondary') {
					echo '<span>Secondary<span class="desktop-only"> Relationships</span></span>';
				}
				if($current_user->ID == $author_id) {
					echo '<div class="edit" data-category="'.$category.'"></div>';
				}
				echo '</h2>';
				echo '<div class="reminders">';
					foreach($reminder_items as $index => $reminder) {
						echo '<div class="reminder" data-id="'.$index.'">';
							echo '<div class="handle"></div>';
							echo '<div class="reminder-content">';
								$time_calulation = DateTime::createFromFormat('Y-m-d', $reminder['date'], $tz)->diff(new DateTime('now', $tz));
								if($time_calulation->invert == 1) {
									//past
									$time = $time_calulation->days;
									$future = true;
								} else {
									$time = $time_calulation->y;
									$future = false;
								}
								//$time = DateTime::createFromFormat('Y-m-d', $reminder['date'], $tz)->diff(new DateTime('now', $tz))->y;
								switch ($reminder['event']) {
									case 'birthday':
										if($category == 'myself') {
											echo "I am ";
										} else if ($category == 'primary') {
											if($reminder['primary_relation_name'] != '') {
												echo $reminder['primary_relation_name'] .' ';
												if($reminder['iswas'] != 'n/a') {
													echo $reminder['iswas'].' ';
												}
											}
										} else if ($category == 'secondary') {
											if($reminder['primary_relation_name'] != '') {
												echo $reminder['primary_relation_name'];
												echo  '&rsquo;s ';
											}
											echo $reminder['secondary_relation'].', ';
											echo $reminder['secondary_relation_name'] .', ';
											if($reminder['iswas'] != 'n/a') {
												echo $reminder['iswas'].' ';
											}
										}
										if($reminder['iswas'] == 'was') {
											echo ' born ';
											if($time == 0) {
												if($time_calulation->m > 0) {
													if($time_calulation->m == 1) {
														echo "<span>$time_calulation->m month ago</span>.";
													} else {
														echo "<span>$time_calulation->m months ago</span>.";
													}
												} else {
													echo "<span>$test->d days ago</span>.";
												}
											} else {
												echo "<span>$time years ago</span>.";
											}
										} else {
											if($time == 0) {
												if($time_calulation->m > 0) {
													if($time_calulation->m == 1) {
														echo "<span>$time_calulation->m month old</span>.";
													} else {
														echo "<span>$time_calulation->m months old</span>.";
													}
												} else {
													echo "<span>$test->d days old</span>.";
												}
											} else {
												echo "<span>$time years old</span>.";
											}
										}
										break;
									case 'anniversary':
										if($category == 'myself') {
											echo "I&rsquo;ve been married for ";
										} else {
											echo "They&rsquo;ve been married for ";
										}
										echo "<span>$time years</span>.";
										break;
									case 'wedding':
										if($future) {
											if($category == 'myself') {
												echo "I will ";
											} else if ($category == 'primary') {
												echo $reminder['primary_relation_name'] .' will ';
											} else if ($category == 'secondary') {
												echo $reminder['primary_relation_name'].'&rsquo; ';
												echo $reminder['secondary_relation'].', ';
												echo $reminder['secondary_relation_name'] .', will ';
											}
											echo " be married in <span>$time days</span>.";
											
										} else {
											if($category == 'myself') {
												echo "I&rsquo;ve been married for ";
											} else if ($category == 'primary') {
												echo $reminder['primary_relation_name'] .' has been married for ';
											} else if ($category == 'secondary') {
												echo $reminder['primary_relation_name'] .'&rsquo; ';
												echo $reminder['secondary_relation'].', ';
												echo $reminder['secondary_relation_name'] .', has been married for ';
											}
											
											if($time == 0) {
												if($time_calulation->m > 0) {
													if($time_calulation->m == 1) {
														echo "<span>$time_calulation->m month</span>.";
													} else {
														echo "<span>$time_calulation->m months</span>.";
													}
												} else {
													echo "<span>$test->d days</span>.";
												}
											} else {
												echo "<span>$time years</span>.";
											}
											
										}
										break;
									default:
										if($category == 'myself') {
											//echo "I am ";
										} else if ($category == 'primary') {
											if($reminder['primary_relation_name'] != '') {
												echo $reminder['primary_relation_name'] .'&rsquo;s ';
											}
										} else if ($category == 'secondary') {
											if($reminder['primary_relation_name'] != '') {
												echo $reminder['primary_relation_name'];
												echo  '&rsquo;s ';
											}
											echo $reminder['secondary_relation'].', ';
											echo $reminder['secondary_relation_name'] .', ';
											if($reminder['iswas'] != 'n/a') {
												echo $reminder['iswas'];
											}
										}
										if($future) {
											echo $reminder['other'] ." is in <span>$time days</span>.";
										} else {
											echo $reminder['other'] .' ';
											if($time == 0) {
												if($time_calulation->m > 0) {
													if($time_calulation->m == 1) {
														echo "<span>$time_calulation->m month ago</span>.";
													} else {
														echo "<span>$time_calulation->m months ago</span>.";
													}
												} else {
													echo "<span>$time_calulation->d days ago</span>.";
												}
											} else {
												echo "<span>$time years ago</span>.";
											}
											
										}
								}
								echo '<div class="detail">';
									if($category == 'myself') {
										if($reminder['event'] != 'other') {
											$person = 'My ';
										} else {
											$person = '';
										}
									} else {
										$person = 'Their ';
									}
									if($reminder['event'] != 'other') {
										echo $person.$reminder['event'];
										if($reminder['iswas'] != 'n/a') {
											echo ' '. $reminder['iswas'].' ';
										}
										echo date('M d, Y', strtotime($reminder['date']));
									} else {
										if($future) {
											echo $person.$reminder['other'];
											if($reminder['iswas'] != 'n/a') {
												echo ' '.$reminder['iswas'].' ';
											} else {
												echo ' ';
											}
											echo date('M d, Y', strtotime($reminder['date']));
										} else {
											echo $person.$reminder['other'];
											if($reminder['iswas'] != 'n/a') {
												echo ' '.$reminder['iswas'].' ';
											} else {
												echo ' ';
											}
											echo date('M d, Y', strtotime($reminder['date']));
										}
										
									}
									if(isset($reminder['note'])) {
										if($reminder['note'] != '') {
											echo '<br>Note: '.$reminder['note'];
										}
									}
								echo '</div>';
							echo '</div>';
							echo '<div class="delete"></div>';
						echo '</div>';
					}
				echo '</div>';
				echo '<div class="big-link done-editing" data-category="'.$category.'">Finish Editing</div>';
			echo '</div>';
		}
	}
}

add_action( 'wp_ajax_nopriv_update_reminder_categories', 'update_reminder_categories_callback' );
add_action( 'wp_ajax_update_reminder_categories', 'update_reminder_categories_callback' );
function update_reminder_categories_callback() {
	global $current_user;
	$saved = $_POST['save'];
	if($saved == '') {
		$sanitized_saved_ids = array();
	} else {
		$sanitized_saved_ids = array_map( 'intval', $saved );
	}
	
	$category = sanitize_text_field( $_POST['category'] );
	$profile_page_id = get_user_meta($current_user->ID,'user_profile_page',true);
	$saved_reminders = maybe_unserialize(get_post_meta($profile_page_id,'reminders',true));
	$new_save = array();
	if(isset($saved_reminders["$category"])) {
		foreach($sanitized_saved_ids as $key) {
			$new_save[] = $saved_reminders["$category"]["$key"];
		}
		$saved_reminders["$category"] = $new_save;
		update_post_meta($profile_page_id,'reminders',$saved_reminders);
	}
	wp_die();
}
?>