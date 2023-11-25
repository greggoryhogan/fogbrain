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
	wp_enqueue_style('jquery-ui-autocomplete','https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css',false,'1.13.2','all');
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script('jquery-ui-core');
	// Enqueue jQuery UI autocomplete
	wp_enqueue_script('jquery-ui-autocomplete');
	wp_enqueue_script('touchpunch', $bhfe_dir.'/js/jquery.ui.touch-punch.min.js',array(),'',true);
	wp_enqueue_script('fogbrain-main', $bhfe_dir.'/js/site.js', array('jquery','jquery-ui-sortable','touchpunch', 'jquery-ui-autocomplete'),'',true);
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
        return 31556926; //1 year in seconds
    } else {
		return 31556926; //1 year in seconds
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
			$message .= '<p style="text-align: center;">If you did not request this login code, you can ignore this email. Your login is only available through your email account.</p>';
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
				wp_set_auth_cookie( $user_id, true, is_ssl() );

				//create their post
				$post_args = array(
					'post_author' => $user_id,
					'post_title' => "$username's Foggy Brain",
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

add_action( 'wp_ajax_nopriv_update_reminder_categories', 'update_reminder_categories_callback' );
add_action( 'wp_ajax_update_reminder_categories', 'update_reminder_categories_callback' );
function update_reminder_categories_callback() {
	global $current_user;
	$saved = $_POST['save'];
	
	$profile_page_id = get_user_meta($current_user->ID,'user_profile_page',true);
	$saved_reminders = maybe_unserialize(get_post_meta($profile_page_id,'user_reminders',true));
	$new_save = array();
	//if(isset($saved_reminders["$category"])) {
		foreach($saved as $save) {
			$id = (int) $save['id'];
			$saved_reminders["$id"]["note"] = $save['note'];
			$saved_reminders["$id"]["tag"] =  $save['tag'];
			$new_save[] = $saved_reminders["$id"];
		}
		$saved_reminders = $new_save;
		update_post_meta($profile_page_id,'user_reminders',$saved_reminders);

		echo json_encode(
			array(
				'reminders' => process_gpt_reminders($new_save, $current_url->ID)
			)
		);
		
	//}
	wp_die();
}


function get_chat_gpt_response($prompt) {
	//set_time_limit(0);
	$url = 'https://api.openai.com/v1/chat/completions';


	$prompt_instructions = "Analyze the user input ## {$prompt} ##.

	Extract the date, if any, from the user input and assign it to the variable 'date.'

	Determine the tense of the user input and assign it to the variable 'tense'.

	Determine if user input contains information about a birthday and assign it to the bool variable 'is_birthday'.

	Determine if user input contains a singular first-person pronoun like 'I'.

	Find a complement (Predicate Nominative) in the user input and assign it to the variable 'complement'.

	If a complement was not found, find a location mentioned in the user input and assign it to the variable 'complement'.

	Remove any mention of a date in the user input and assign it to the variable 'phrase'.

	Return as a JSON object in this format:
	{
		'is_birthday' : is_birthday,
		'primary_subject' : {extracted_complete_subject},
		'about_me' : {contains_singular_first_person_pronoun},
		'date' : date,
		'phrase' : phrase,
		'tense': tense,
		'complement' : complement
	}";
	
	$data = array(
		'model' => 'gpt-3.5-turbo', //gpt-4, gpt-3.5-turbo
		'temperature' => (int) '.1',
		'messages' => array(
			array(
				'role' => 'system',
				'content' => 'You are a helpful assistant. Format the answer as a JSON object'
			),
			array(
				'role' => 'user',
				'content' => $prompt_instructions
			),
			
		)
	);

	//"Extract information from the following prompt - $prompt . Return an array. Key 'date' is the date. Key 'is_birthday' is a bool variable whethis this prompt is related to a birthday, born day, or date of birth. Key 'about_me' is a bool variable, true when the prompt is information about me and false when the information is about someone else. Key 'primary_subject' is who I am talking about in relation to me, from your perspective. Key 'primary_action' is the primary_subject of my prompt in relation to me, from your perspective."

	//$query_url = $url.'?'.http_build_query($data);
	$args = array(
		'headers'     => array(
			'Content-Type' => 'application/json',
			'Authorization' => 'Bearer ' . FOG_CHATGPT_KEY,
		),
		'timeout'     => '30',
		'compress' => true,
		'body' => json_encode($data),
	); 

	//rety until we get a response
	while(1) {
		$result = wp_remote_post( $url, $args );
		if(!is_wp_error( $result )) {
			if (!empty($result) && $result["response"]["code"] == 200) {
				break;
			}
		} 
	}
	
	
	if(!is_wp_error( $result )) {
		//print_r( $result );
		$jsonResponse = json_decode(wp_remote_retrieve_body($result), true);
		//return $jsonResponse;
		//echo '<pre>';
		if (isset($jsonResponse['choices']) && count($jsonResponse['choices']) > 0) {
			$choices = $jsonResponse['choices'][0]['message']['content'];
			if(!empty($choices)) {
				return array(
					'error' => 0,
					'message' => $choices
				);
			} else {
				return array(
					'error' => 1,
					'message' => wp_remote_retrieve_body($result)
				);
			}
		} else {
			return array(
				'error' => 1,
				'message' => wp_remote_retrieve_body($result)
			);
		}
		//echo '</pre>';
		
	} else {
		return array(
			'error' => 1,
			'message' => array($result->get_error_message())
		);
		//echo $result->get_error_message();
	}
}

add_action( 'wp_ajax_nopriv_add_gpt_reminder', 'add_gpt_reminder_callback' );
add_action( 'wp_ajax_add_gpt_reminder', 'add_gpt_reminder_callback' );
function add_gpt_reminder_callback() {
	global $current_user;
	$prompt = sanitize_text_field($_POST['prompt']);
	$note = sanitize_text_field($_POST['note']) ?? '';
	$public = sanitize_text_field($_POST['public']) ?? '';
	$tag = sanitize_text_field($_POST['tag']) ?? '';

	$response = get_chat_gpt_response($prompt);
	if($response['error'] == 1) {
		echo json_encode(
			array(
				'error' => 1,
				'message' => $response['message']
			)
		);
		wp_die();
	} else {
		$message = json_decode($response['message'],true);
		$profile_page_id = get_user_meta($current_user->ID,'user_profile_page',true);
		$saved_reminders = maybe_unserialize(get_post_meta($profile_page_id,'user_reminders',true));
		if(!is_array($saved_reminders)) {
			$saved_reminders = array();
		}
		$index = count($saved_reminders);
		$date = $message['date'];
		$is_birthday = $message['is_birthday'];
		$about_me = $message['about_me'];
		$phrase = $message['phrase'];
		$tense = $message['tense'];
		$primary_subject = $message['primary_subject'];
		$complement = $message['complement'];

		$new_reminder = array(
			'date' => $date,
			'is_birthday' => $is_birthday,
			'primary_subject' => $primary_subject,
			'about_me' => $about_me,
			'phrase' => $phrase,
			'tense' => $message['tense'],
			'complement' => $message['complement'],
			'note' => $note,
			'public' => $public,
			'tag' => $tag
		);

		$saved_reminders[] = $new_reminder;
		update_post_meta($profile_page_id,'user_reminders',$saved_reminders);
		$reminder = '<div class="reminder" data-id="'.$index.'"><div class="handle"></div><div class="reminder-content">';
		//$reminder .= '<pre>'.print_r($new_reminder,true).'</pre>';
		$reminder .= process_gpt_reminder($new_reminder);
		$reminder .= '</div><div class="delete"></div></div>';
		echo json_encode(
			array(
				'error' => 0,
				'reminder' => $reminder,
			)
		);
		wp_die();
	}

	wp_die();
}

function process_gpt_reminders($reminders, $author_id = null) {
	global $current_user;
	$user_timezone = get_user_meta($current_user->ID,'timezone',true);
	if($user_timezone == '') {
		$user_timezone = 'America/New_York';
	}
	$reminders_html = '';
	$is_my_page = true;
	if($author_id != null) {
		if($current_user->ID != $author_id) {
			$is_my_page = false;
		}
	}
	$tags = array();
	foreach($reminders as $index => $reminder) {
		if($reminder['tag'] != '') {
			if(!in_array($reminder['tag'],$tags)) {
				$tags[] = $reminder['tag'];
			}
		}
	}
	if(!empty($tags)) {
		sort($tags);
		$reminders_html .= '<div class="reminder-tags">';
		$reminders_html .= '<div class="tag-btn" data-tag="All">All</div>';
		foreach($tags as $tag) {
			$reminders_html .= '<div class="tag-btn" data-tag="'.$tag.'">'.$tag.'</div>';
		}
		$reminders_html .= '</div>';
	}
	
	foreach($reminders as $index => $reminder) {
		if($reminder['public'] == 'true' || $is_my_page) {
			//print_r($reminder);
			if($reminder['tag'] != '') {
				$tag = $reminder['tag'];
			} else {
				$tag = '';
			}
			$reminders_html .= '<div class="reminder" data-id="'.$index.'" data-tag="'.$tag.'">';
				$reminders_html .= '<div class="handle"></div>';
				$reminders_html .= '<div class="reminder-content">';
					$reminders_html .= process_gpt_reminder($reminder, $user_timezone);
				$reminders_html .= '</div>';
				$reminders_html .= '<div class="delete"></div>';
			$reminders_html .= '</div>';
		}
	}
	return $reminders_html;
}

function validDate($date, $format = 'Y-m-d') {
	if($date == '') {
		return false;
	}
	$normalized_date = date('Y-m-d', strtotime($date));
    $d = DateTime::createFromFormat($format, $normalized_date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
	return $d && $d->format($format) === $normalized_date;
}

function process_gpt_reminder($reminder, $timezone = false) {

	$return = '';
	
	$return .= '<div class="tags">';
		if($reminder['tag']) {
			$val = $reminder['tag'];
			$placeholder = '';
		} else {
			$val = '';
			$placeholder = 'placeholder';
		}
		$return .= '<input type="text" value="'.$reminder['tag'].'" placeholder="Tag" class="tag '.$placeholder.'" />';
		
	$return .= '</div>';
	$return .= '<div class="reminder-data">';
		$reminder_date = str_replace(',','',$reminder['date']);
		if(validDate($reminder_date)) {
			//$return .= '<pre>'.print_r($reminder,true).'</pre>';
			if($timezone === false) {
				$timezone = 'America/New_York';
			}
			$tz  = new DateTimeZone($timezone);
			$normalized_date = date('Y-m-d', strtotime($reminder_date));
			$time_calulation = DateTime::createFromFormat('Y-m-d', $normalized_date, $tz)->diff(new DateTime('now', $tz));
			
			if($time_calulation->y == 0) {
				if($time_calulation->m  > 0) {
					if($time_calulation->m == 1) {
						$time = "$time_calulation->m month";
					} else {
						$time = "$time_calulation->m months";
					}
				} else {
					$time = "$time_calulation->d days";
				}
			} else {
				$time = "$time_calulation->y years";
			}
			if($reminder['is_birthday']) {
				if($reminder['about_me']) {
					$return .= "I am <span>$time old</span>";
				} else {
					$subject = ucfirst($reminder['primary_subject']);
					$return .= "$subject";
					if(strpos($subject, ',') !== false) {
						$return .= ",";
					}
					$return .= " is <span>$time old</span>";
				}
			} else {
				$phrase = rtrim($reminder['phrase'], '.');
				if($reminder['tense'] == 'present perfect') {
					$phrase = rtrim($phrase, 'since');	
				}
				$return .= "$phrase";
				if(strpos($phrase,',')) {
					$return .= ',';
				}
				
				if($reminder['tense'] == 'future') {
					$return .= " in";
				}
				$return .= " <span>$time";
				if($reminder['tense'] == 'past') {
					$return .= " ago";
				}
				$return .= "</span>";
			}
			$return .= '<div class="detail">';
				/*$subject = ucfirst(str_replace(',','',$primary_subject));*/
				if($reminder['tag'] != '') {
					$tag = strtolower(str_replace(' ','-',$reminder['tag']));
					$return .= '<span class="tag-icon '.$tag.'"></span>';
				}
				/*if($reminder['is_birthday']) {
					if($reminder['about_me']) {
						$return .= "My";
					} else {
						$return .= "Their";
					}
					$return .= " birthday is ";
				}*/
				
				/*$parsed = date_parse($reminder['date']);
				$return .= print_r($parsed,true);
				if($parsed['day'] != '') {
					$return .= date('F jS, Y', strtotime($reminder['date']));
				} else {
					$return .= date('F, Y', strtotime($reminder['date']));
				}*/
				// Check if the input string contains a numeric value for the day
				if (preg_match('/(\d{1,2}\/\d{4}|\d{1,2}\/\d{1,2}\/\d{4}|\w+\s+\d{1,2},?\s+\d{4})/', $reminder_date, $matches)) {
					$return .= date('F jS, Y', strtotime($reminder_date)) .'<div class="clear"></div>';
				} else {
					$return .= date('F, Y', strtotime($reminder_date)) .'<div class="clear"></div>';
				}


				
				if($reminder['note'] !== '') {
					$note = $reminder['note'];
					$placeholder = '';
				} else {
					$note = '';
					$placeholder = 'placeholder';
				}
				$return .= '<div><input class="note '.$placeholder.'" placeholder="note" value="'.$note.'" /></div>';
			$return .= '</div>';
		} else {
			//$return .= '<pre>'.print_r($reminder,true).'</pre>';
			if(isset($reminder['complement'])) {
				$phrase = str_replace($reminder['complement'], '<span>'.$reminder['complement'].'</span>', $reminder['phrase']);
			} else {
				$phrase = $reminder['phrase'];
			}
			if(str_ends_with($phrase,'.')) {
				$phrase = rtrim($phrase, '.');
			}
			$return .= "$phrase";
			$return .= '<div class="detail">';
				if($reminder['tag'] != '') {
					$tag = strtolower(str_replace(' ','-',$reminder['tag']));
					$return .= '<span class="tag-icon '.$tag.'"></span>';
				}
				if($reminder['note'] !== '') {
					$note = $reminder['note'];
					$placeholder = '';
				} else {
					$note = '';
					$placeholder = 'placeholder';
				}
				$return .= '<div><input class="note '.$placeholder.'" placeholder="note" value="'.$note.'" /></div>';
			$return .= '</div>';

		}
	$return .= '</div>';
	return $return;
}

?>