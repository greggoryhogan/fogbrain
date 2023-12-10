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
	wp_register_style( 'fogbrain-flexible-content', $bhfe_dir.'/css/flexible-content.css', false, $version, 'all' );
	if(get_post_type() == 'page' && !is_front_page() && !is_page('login') && !is_page('profile')) {
		wp_enqueue_style('fogbrain-flexible-content');
	}
	wp_enqueue_style('bootstrap-grid',$bhfe_dir.'/css/bootstrap-grid.min.css', false, '1.0', 'all');

	/*wp_register_script('input-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.3.4/jquery.inputmask.bundle.min.js', array('jquery'),'',true);
	if(is_page('login')) {
		wp_enqueue_script('input-mask');
	}*/
	wp_register_style('jquery-ui-autocomplete','https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css',false,'1.13.2','all');
	if(get_post_type() == 'brain') {
		wp_enqueue_style('jquery-ui-autocomplete');
	}
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script('jquery-ui-core');
	// Enqueue jQuery UI autocomplete
	wp_enqueue_script('jquery-ui-autocomplete');
	wp_enqueue_script('touchpunch', $bhfe_dir.'/js/jquery.ui.touch-punch.min.js',array(),'',true);
	wp_enqueue_script('fogbrain-main', $bhfe_dir.'/js/site.js', array('jquery','jquery-ui-sortable','touchpunch', 'jquery-ui-autocomplete'),$version,true);
	global $fogbrain_user_id, $current_user;
	wp_localize_script( 'fogbrain-main', 'site_js', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'user_id' => $fogbrain_user_id,
		'user' => $current_user,
		'logout_link' => wp_logout_url('/login')
	));
	
	wp_enqueue_style('fog-fonts','https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,500;0,600;0,700;0,800;1,500;1,600;1,700;1,800&display=swap');
	
	wp_dequeue_style('wp-block-library');
	//wp_deregister_script( 'wp-polyfill' );
  	//wp_deregister_script( 'regenerator-runtime' );
}
add_action( 'wp_enqueue_scripts', 'register_bhfe_scripts' );

function remove_jquery_migrate( $scripts ) {
	if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
			$script = $scripts->registered['jquery'];
			if ( $script->deps ) { 
				$script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
		}
	}
}
add_action( 'wp_default_scripts', 'remove_jquery_migrate' );

add_action('wp_head','bhfe_early_head_customization');
function bhfe_early_head_customization() { 
	?>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="Fog Brain">
	<meta name="theme-color" content="#008080" />
	<link rel='shortcut icon' href="<?php echo THEME_URI; ?>/img/fav.png" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-ES9293SV9X"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'G-ES9293SV9X');
	</script>

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
		if(isset($_GET['delete-account'])) {
			if(isset($_GET['email']) && isset($_GET['wpnonce'])) {
				$email = sanitize_text_field(urldecode($_GET['email']));
				$nonce = sanitize_text_field($_GET['wpnonce']);
				if(wp_verify_nonce( $nonce, 'email_'.$email )) {
					//required for deleting user
					require_once(ABSPATH.'wp-admin/includes/user.php');
					$user = get_user_by('email',$email);
					//get their page and delete it
					$profile_page_id = get_user_meta($user->ID,'user_profile_page',true);
					wp_delete_post($profile_page_id, true);
					//log them out
					$sessions = WP_Session_Tokens::get_instance($user->ID);
					$sessions->destroy_all();
					//delete user
					wp_delete_user($user->ID);
					wp_redirect( get_bloginfo('url').'/login/?account-deleted=1');
					exit;
				} else {
					wp_nonce_ays('Your deletion code was invalid.');
				}
			} 
		}
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
			exit;
		}	
	} else if(is_search()) {
		wp_redirect(get_bloginfo('url'));	
		exit;
	} else if(isset($post)) {
		if($post->post_type == 'brain') {
			$login_redirect = get_bloginfo('url').'/login?access-error=';
			//redirect users from viewing other peoples pages
			if($post->post_author != $fogbrain_user_id) {
				if(!current_user_can('administrator')) {
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

add_filter( 'wp_mail_from', 'my_send_from' );
function my_send_from( $original_email_address ) {
    return 'notifications@myfogbrain.com';
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
			wp_mail('hello@mynameisgregg.com','Fog Brain Code Request','Damn, it really happened?');
			set_transient( 'access-code-'.$random_number, $email, 20 * MINUTE_IN_SECONDS );
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
			//notify me
			wp_mail('hello@mynameisgregg.com','Fog Brain Signup','Damn, it really happened?');
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
		'post_title' => "u/$profile_url",
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
	if(isset($_GET['account-deleted'])) {
		echo '<div class="col-12 col-md-8">';
			echo '<p class="error-notice">Your account has been deleted.<span class="close-notification"></span></p>';
		echo '</div>';
	}
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
				//$message .= ' If you were trying to access your own page, please log in below.';
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
			$saved_reminders["$id"]["note"] = sanitize_text_field($save['note']);
			$saved_reminders["$id"]["tag"] =  sanitize_text_field($save['tag']);
			$saved_reminders["$id"]["public"] =  sanitize_text_field($save['public']);
			$new_save[] = $saved_reminders["$id"];
		}
		$saved_reminders = $new_save;
		update_post_meta($profile_page_id,'user_reminders',$saved_reminders);

		echo json_encode(
			array(
				'reminders' => process_gpt_reminders($new_save, $current_user->ID)
			)
		);
		
	//}
	wp_die();
}


function get_chat_gpt_response($prompt) {
	//set_time_limit(0);
	$url = 'https://api.openai.com/v1/chat/completions';


	$prompt_instructions = "Analyze the user input ## {$prompt} ##.

	Extract a single date including the month day and year, if any, from the user input and assign it to the variable 'date.'. If a second date is found or the date has a span of time like 'to' or '-', assign it to the variable 'date_2'.

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
		'date_2' : date_2,
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
		$date_2 = $message['date_2'];
		$is_birthday = $message['is_birthday'];
		$about_me = $message['about_me'];
		$phrase = $message['phrase'];
		$tense = $message['tense'];
		$primary_subject = $message['primary_subject'];
		$complement = $message['complement'];

		$user_timezone = get_user_meta($current_user->ID,'timezone',true);
		if($user_timezone == '') {
			$user_timezone = 'America/New_York';
		}
		$timezone  = new DateTimeZone($user_timezone);

		$diffDays = 10;
		if($tense == 'future' || $tense == 'present') {
			$today = new DateTime("today");
			$reminder_date = str_replace(',','',$date);
			$normalized_date = date('Y-m-d', strtotime($reminder_date));
			$reminder_date_time = DateTime::createFromFormat('Y-m-d', $normalized_date, $timezone);
			if($reminder_date_time < $today) {
				//event is in the past
				$tense = 'past';
				$phrase = str_replace('is','was',$phrase);
				$phrase = str_replace('am','was',$phrase);
				$phrase = str_replace('are','were',$phrase);
				if($primary_subject == 'I') {
					$phrase = str_replace('will be','was',$phrase);
				} else {
					$phrase = str_replace('will be','were',$phrase);
				}
				
			}
		}
		if(strpos($phrase,'have been') !== false) {
			$tense = 'present perfect';
		}
		if(strpos($date,' to ') !== false) {
			$dates = explode(' to ',$date);
			$date = $dates[0];
			$date_2 = $dates[1];
		} else if(strpos($date,' - ') !== false) {
			$dates = explode(' - ',$date);
			$date = $dates[0];
			$date_2 = $dates[1];
		}
		
		$new_reminder = array(
			'date' => $date,
			'date_2' => $date_2,
			'is_birthday' => $is_birthday,
			'primary_subject' => $primary_subject,
			'about_me' => $about_me,
			'phrase' => $phrase,
			'tense' => $tense,
			'complement' => $message['complement'],
			'note' => $note,
			'public' => $public,
			'tag' => $tag
		);

		$saved_reminders[] = $new_reminder;
		update_post_meta($profile_page_id,'user_reminders',$saved_reminders);
		$reminder = '<div class="reminder" data-id="'.$index.'"><div class="handle"></div><div class="reminder-content">';
		//$reminder .= '<pre>'.print_r($new_reminder,true).'</pre>';
		$reminder .= process_gpt_reminder($new_reminder, $timezone, true);
		$reminder .= '</div><div class="delete"></div></div>';
		echo json_encode(
			array(
				'error' => 0,
				'reminder' => $reminder,
				'days' => $diffDays
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
	$timezone  = new DateTimeZone($user_timezone);
	$is_my_page = true;
	if($author_id != null) {
		if($current_user->ID != $author_id) {
			$is_my_page = false;
		}
	}
	//check if future event has passed and update reminders if necessary
	if($author_id != null) {
		$profile_page_id = get_user_meta($author_id,'user_profile_page',true);
		$needs_update = false;
		$hasupcoming = false;
		$upcoming_reminders = array();
		$today = strtotime(date('Y-m-d'));
		$upcoming_date = strtotime(date('Y-m-d') . ' +1 month');
		$replacements = array(
			'spring',
			'fall',
			'winter',
			'summer',
			'autumn',
			'monsoon',
			'st',
			'th',
			'nd'
		);
		foreach($reminders as $index => $reminder) {
			$reminder_date = str_replace(',','',$reminder['date']);
			$reminder_date = str_ireplace($replacements,'',$reminder_date);
			$reminder_date = trim($reminder_date);
			if(strpos($reminder_date,'-') !== false || strpos($reminder_date,'/') !== false || strpos($reminder_date,' ') !== false) {
				$normalized_date = date('Y-m-d', strtotime($reminder_date));
				$day = date('d', strtotime($reminder_date));
				$month = date('m', strtotime($reminder_date));
				$upcoming_occurrance = strtotime(date("Y-$month-$day"));
				if($upcoming_occurrance >= $today && $upcoming_occurrance <= $upcoming_date) {
					if($reminder['public'] == 'true' || $is_my_page || current_user_can('administrator')) {
						$hasupcoming = true;
						$upcoming_reminders[] =  "$index";
					}
				}
			} 

			if($reminder['tense'] == 'future' || $reminder['tense'] == 'present') {
				$today = new DateTime("today");
				$reminder_date = str_replace(',','',$reminder['date']);
				$normalized_date = date('Y-m-d', strtotime($reminder_date));
				$reminder_date_time = DateTime::createFromFormat('Y-m-d', $normalized_date, $timezone);
				$reminder_time = $reminder_date_time->setTime( 0, 0, 0 ); // set time part to midnight, in order to prevent partial comparison
				$diff = $today->diff( $reminder_time );
				$diffDays = (integer)$diff->format( "%R%a" );
				if($diffDays < 0) {
					//event is now in the past, update it
					$reminder['tense'] = 'past';
					$reminder['phrase'] = str_replace('is','was',$reminder['phrase']);
					$reminder['phrase'] = str_replace('am','was',$reminder['phrase']);
					$reminder['phrase'] = str_replace('are','were',$reminder['phrase']);
					if($reminder['primary_subject'] == 'I') {
						$reminder['phrase'] = str_replace('will be','was',$reminder['phrase']);
					} else {
						$reminder['phrase'] = str_replace('will be','were',$reminder['phrase']);
					}
					$needs_update = true;
					$reminders["$index"] = $reminder;
				} else {
					if($reminder['public'] == 'true' || $is_my_page || current_user_can('administrator')) {
						$hasupcoming = true;
						$upcoming_reminders[] =  "$index";
					}
				}
			}

		}
		if($needs_update) {
			update_post_meta($profile_page_id,'user_reminders',$reminders);
		}
	}

	$reminders_html = '';
	
	$tags = array();
	foreach($reminders as $index => $reminder) {
		if($reminder['tag'] != '' && $reminder['tag'] != 'None' && ($reminder['public'] == 'true' || $is_my_page)) {
			if(!in_array($reminder['tag'],$tags)) {
				$tags[] = $reminder['tag'];
			}
		}
		if($hasupcoming && !in_array('Upcoming',$tags)) {
			$tags[] = 'Upcoming';
		}
	}
	$reminders_html .= '<div class="reminder-options">';
		if(!empty($tags)) {
			sort($tags);
			$reminders_html .= '<div class="reminder-tags">';
			$reminders_html .= '<div class="tag-btn" data-tag="All">All</div>';
			foreach($tags as $tag) {
				$reminders_html .= '<div class="tag-btn" data-tag="'.$tag.'" onclick="gtag(\'event\',\'filter_reminders\');">'.$tag.'</div>';
			}
			$reminders_html .= '</div>';
		}
		/*$reminders_html .= '<div class="sort">';
			$reminders_html .= 'Sort by: ';
			$reminders_html .= '<select id="reminder-sort">';
				$reminders_html .= '<option value="custom">Default</option>';
				$reminders_html .= '<option value="month-asc">Month (ASC)</option>';
				$reminders_html .= '<option value="month-desc">Month (DESC)</option>';
				$reminders_html .= '<option value="year-asc">Year (ASC)</option>';
				$reminders_html .= '<option value="year-desc">Year (DESC)</option>';
			$reminders_html .= '</select>';
		$reminders_html .= '</div>';*/
		$reminders_html .= '</div>';
	$privates = 0;
	$reminders_html .= '<div class="all-reminders">';
		foreach($reminders as $index => $reminder) {
			if($reminder['public'] == 'true' || $is_my_page || current_user_can('administrator')) {
				//print_r($reminder);
				if($reminder['tag'] != '') {
					$tag = $reminder['tag'];
				} else {
					$tag = '';
				}
				if(in_array($index,$upcoming_reminders)) {
					$data_upcoming = "true";
				} else {
					$data_upcoming = "false";
				}
				$reminders_html .= '<div class="reminder" data-id="'.$index.'" data-tag="'.$tag.'" data-upcoming="'.$data_upcoming.'">';
					if($is_my_page) {	
						$reminders_html .= '<div class="handle"></div>';
					}
					$reminders_html .= '<div class="reminder-content">';
						$reminders_html .= process_gpt_reminder($reminder, $timezone, $is_my_page);
					$reminders_html .= '</div>';
					if($is_my_page) {
						$reminders_html .= '<div class="delete"></div>';
					}
				$reminders_html .= '</div>';
			}
			if($reminder['public'] != 'true' && !$is_my_page) {
				$privates++;
			}
		}
	$reminders_html .= '</div>';
	if($privates > 0) {
		$reminders_html .= '<div class="reminder">';
		if($privates == 1) {
			$reminders_html .= '<p class="private-reminder-ct">And <span>'.$privates.'</span> other private reminder.</p>';
		} else {
			$reminders_html .= '<p class="private-reminder-ct">And <span>'.$privates.'</span> other private reminders.</p>';
		}
		$reminders_html .= '</div>';
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

function process_gpt_reminder($reminder, $timezone = false, $is_my_page = false) {
	$replacements = array(
		'spring',
		'fall',
		'winter',
		'summer',
		'autumn',
		'monsoon',
		'st',
		'th',
		'nd'
	);
	$return = '';
	//$return .= print_r($reminder,true);
	$return .= '<div class="reminder-data">';
		$reminder_date = str_replace(',','',$reminder['date']);
		$reminder_date = str_ireplace($replacements,'',$reminder_date);
		$reminder_date = trim($reminder_date);
		$date_2 = '';
		$justyear = false;
		if(isset($reminder['date_2'])) {
			$date_2 = $reminder['date_2'];
			$date_2 = str_ireplace($replacements,'',$date_2);
			$date_2 = trim($date_2);
		}
		if(validDate($reminder_date)) {
			if($timezone === false) {
				$timezone  = new DateTimeZone('America/New_York');
			}
			//$return .= $reminder['tense'];
			$now = new DateTime('today', $timezone);
			if(strpos($reminder_date,'-') !== false || strpos($reminder_date,'/') !== false || strpos($reminder_date,' ') !== false) {
				$normalized_date = date('Y-m-d', strtotime($reminder_date));
				$month = date('m', strtotime($reminder_date));
				$year = date('Y', strtotime($reminder_date));
			} else {
				$justyear = true;
				$normalized_date = date('Y-m-d', strtotime('1-1-'.$reminder_date));
				$month = date('m', strtotime('1-1-'.$reminder_date));
				$year = date('Y', strtotime('1-1-'.$reminder_date));
			}
			$month = date('m', strtotime($reminder_date));
			$reminder_date_time = DateTime::createFromFormat('Y-m-d', $normalized_date, $timezone);
			$time_calulation = $reminder_date_time->diff($now);

			if($date_2 != '') {
				if(strpos($date_2,'-') !== false || strpos($date_2,'/') !== false || strpos($date_2,' ') !== false) {
					$normalized_date_to = date('Y-m-d', strtotime($date_2));
					$month = date('m', strtotime($date_2));
					$year = date('Y', strtotime($date_2));
				} else {
					$justyear = true;
					$normalized_date_to = date('Y-m-d', strtotime('1-1-'.$date_2));
					$month = date('m', strtotime('1-1-'.$date_2));
					$year = date('Y', strtotime('1-1-'.$date_2));
				}
				
				//$now = new DateTime('now', $timezone);
				$reminder_date_time_to = DateTime::createFromFormat('Y-m-d', $normalized_date_to, $timezone);
				$time_calulation = $reminder_date_time->diff($reminder_date_time_to);
			}
			
			$month_output = '';
			if(!$justyear) {
				$month_output = 'data-month="'.$month.'"';
			}
			
			
			//$return .= '<pre>'.print_r($time_calulation,true).'</pre>';
			$time = get_timespan($time_calulation, $reminder);
			$return .= '<div class="phrase" '.$month_output.' data-year="'.$year.'">';
				if($reminder['tag'] == 'Birthdays') { //if($reminder['is_birthday']) {
					if($reminder['about_me']) {
						$return .= "I am <span>$time old</span>";
					} else {
						$subject = ucfirst($reminder['primary_subject']);
						$return .= "$subject";
						if(strpos($subject, ',') !== false) {
							$return .= ",";
						}
						if($date_2 != '') {
							$return .= " was ";
						} else {
							$return .= " is ";
						}
						
						$return .= "<span>$time old</span>";
					}
				} else if($date_2 != '') {
					$phrase = rtrim($reminder['phrase'], '.');
					if(substr($phrase, -2) == 'to') {
						$phrase = rtrim($phrase, "to");	
					}
					$phrase = str_replace('from','',str_replace('-','',$phrase));
					
					$return .= $phrase;
					if($reminder['tense'] == 'past') {
						$return .= " for";
					}
					$return .= " <span>$time";
					$return .= "</span>";
				} else {
					$phrase = rtrim($reminder['phrase'], '.');
					if($reminder['tense'] == 'present perfect' || $reminder['tense'] == 'present perfect continuous') {
						$phrase = rtrim($phrase, 'since');	
					}
					if($reminder['tense'] == 'past') {
						if(substr($phrase, -3) == ' in') {
							$phrase = rtrim($phrase, " in");	
						}
					}
					$return .= "$phrase";
					if(strpos($phrase,',')) {
						$return .= ',';
					}
					
					if(($reminder['tense'] == 'future' || $reminder['tense'] == 'present') && $time != 'today') {
						$return .= " in";
					}
					$return .= " <span>$time";
					if($reminder['tense'] == 'past') {
						$return .= " ago";
					}
					$return .= "</span>";
				}
			$return .= '</div>';
			$return .= '<div class="detail">';
				/*$subject = ucfirst(str_replace(',','',$primary_subject));*/
				if($reminder['tag'] != '' && $reminder['tag'] != 'None') {
					$tag = strtolower(str_replace(' ','-',str_replace(' & ','-',$reminder['tag'])));
					//if($tag == 'birthdays') {
						$return .= '<span class="tag-icon '.$tag.'"></span>';
					//}
				}
				
				// Check if the input string contains a numeric value for the day
				$return .= '<div class="detail-date">';
				if($justyear) {
					$return .= $reminder['date'];
				} else if (preg_match('/(\d{1,2}\/\d{4}|\d{1,2}\/\d{1,2}\/\d{4}|\w+\s+\d{1,2},?\s+\d{4})/', $reminder_date, $matches)) {
					$return .= date('F jS, Y', strtotime($reminder_date));
				} else {
					$return .= date('F, Y', strtotime($reminder_date));
				}
				if($date_2 != '') {
					if($justyear) {
						$return .= ' - '.$reminder['date_2'];
					} else if (preg_match('/(\d{1,2}\/\d{4}|\d{1,2}\/\d{1,2}\/\d{4}|\w+\s+\d{1,2},?\s+\d{4})/', $date_2, $matches)) {
						$return .= ' - '. date('F jS, Y', strtotime($date_2));
					} else {
						$return .= ' - '.date('F, Y', strtotime($date_2));
					}	
					$reminder_date_time = DateTime::createFromFormat('Y-m-d', $normalized_date_to, $timezone);
					$time_calulation_2 = $reminder_date_time->diff($now);
					$time_2 = get_timespan($time_calulation_2, $reminder);
					$return .= ', '.$time_2 .' ago';
				}
				$return .= '</div>';

				
				if($reminder['note'] !== '') {
					$note = $reminder['note'];
					preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $note, $match);
					if(!empty($match[0])) {
						$linkct = 0;
						foreach($match[0] as $link) {
							$url = parse_url($link); 
							$base_url = trim($url['host'], '/');
							if($linkct > 0) {
								$note = str_replace($link,' | <a href="'.$link.'" target="_blank">'.$base_url.'</a>',$note);
							} else {
								$note = str_replace($link,'<a href="'.$link.'" target="_blank">'.$base_url.'</a>',$note);
							}
							
							$linkct++;
						}
					} 
					$placeholder = '';
					$sep = '&nbsp;-&nbsp;';
				} else {
					$note = '';
					$sep = ' ';
					$placeholder = 'placeholder';
				}
				//$return .= '<div class="sep">'.$sep.'</div>';
				$return .= '<div class="tags">';
					if($reminder['tag']) {
						$val = $reminder['tag'];
						$placeholder = ' '.strtolower(str_replace(' ','-',str_replace(' & ','-',$reminder['tag'])));
					} else {
						$val = '';
						$placeholder = 'placeholder';
					}
					$return .= '<input type="text" value="'.$reminder['tag'].'" placeholder="Category" class="tag existing '.$placeholder.'" />';
					
				$return .= '</div>';
				$return .= '<div class="note">'.$note.'</div>';
				if($reminder['tag'] == 'Sobriety') {
					$sober = '';
					if($time_calulation->y > 0) {
						if($time_calulation->y == 1) {
							$sober .= $time_calulation->y .' year';
						} else {
							$sober .= $time_calulation->y .' years';
						}
					}
					if($time_calulation->m > 0) {
						if($sober != '') {
							$sober .= ', ';
						}
						if($time_calulation->m == 1) {
							$sober .= $time_calulation->m .' month';
						} else {
							$sober .= $time_calulation->m .' months';
						}
					}
					if($time_calulation->m > 0) {
						if($sober != '') {
							$sober .= ', ';
						}
						if($time_calulation->d == 1) {
							$sober .= $time_calulation->d .' day';
						} else {
							$sober .= $time_calulation->d .' days';
						}
					}
					if($sober != '') {
						$return .= "&nbsp;&dash;&nbsp;$sober";
					}
				}
				$public = $reminder['public'];
				if($public == 'false') {
					$return .= '<div class="public-notice">&nbsp;&dash;&nbsp;<strong>PRIVATE</strong></div>';
				}
			$return .= '</div>';
		} else {
			//$return .= '<pre>'.print_r($reminder,true).'</pre>';
			$return .= '<div class="phrase" data-month="1" data-year="1">';
				if(isset($reminder['complement'])) {
					$phrase = str_replace($reminder['complement'], '<span>'.$reminder['complement'].'</span>', $reminder['phrase']);
				} else {
					$phrase = $reminder['phrase'];
				}
				if(str_ends_with($phrase,'.')) {
					$phrase = rtrim($phrase, '.');
				}
				$return .= "$phrase";
			$return .= '</div>';
			$return .= '<div class="detail">';
				$return .= '<div class="tags">';
					if($reminder['tag']) {
						$val = $reminder['tag'];
						$placeholder = ' '.strtolower(str_replace(' ','-',str_replace(' & ','-',$reminder['tag'])));
					} else {
						$val = '';
						$placeholder = 'placeholder';
					}
					$return .= '<input type="text" value="'.$reminder['tag'].'" placeholder="Category" class="tag existing '.$placeholder.'" />';
					
				$return .= '</div>';
				if($reminder['tag'] != '' && $reminder['tag'] != 'None') {
					$tag = strtolower(str_replace(' ','-',str_replace(' & ','-',$reminder['tag'])));
					//if($tag == 'birthdays') {
						$return .= '<span class="tag-icon '.$tag.'"></span>';
					//}
				}
				if($reminder['note'] !== '') {
					$note = $reminder['note'];
					$placeholder = '';
				} else {
					$note = '';
					$placeholder = '';
				}
				$return .= '<div class="note nodate '.$placeholder.'">'.$note.'</div>';
				$public = $reminder['public'];
				if($public == 'false') {
					$return .= '<div class="public-notice">';
					if($note != '') {
						$return .= '&nbsp;&dash;&nbsp;';
					}
					$return .= '<strong>PRIVATE</strong></div>';
				}
			$return .= '</div>';
			
		}
		if($is_my_page) {
			$return .= '<div class="public">';
				$public = $reminder['public'];
				if($public == 'true') {
					$checked = 'checked="checked"';
				} else {
					$checked = '';
				}
				$return .= '<label><input type="checkbox" class="public-val" '.$checked.' /> This reminder is public</label>';
			$return .= '</div>';
		}
		
	$return .= '</div>';
	return $return;
}

function get_timespan($time_calulation, $reminder) {
	if($time_calulation->y == 0) {
		if($time_calulation->m  > 0) {
			if($time_calulation->m == 1) {
				$time = "$time_calulation->m month";
			} else {
				$time = "$time_calulation->m months";
			}
		} else {
			if($time_calulation->d == 0 && $time_calulation->invert == 1) {
				$time = "today";
			} else if($time_calulation->d == 0 && $time_calulation->invert == 0) {
				$time = "1 day";
			} else if($time_calulation->d == 1) {
				$time = "$time_calulation->d day";
			} else {
				$time = "$time_calulation->d days";
			}
		}
	} else {
		if($time_calulation->y == 1) {
			if($reminder['tag'] == 'Birthdays') {
				//baby
				$months = $time_calulation->m + 12;
				$time = "$months months";
			} else {
				/*$months = round(($time_calulation->m / 12) * 2) / 2;
				if($months != 0) {
					$months = ltrim($months,'0');
					$time = "$time_calulation->y$months years";
				} else {
					$time = "$time_calulation->y year";
				}*/
				//$time = "$time_calulation->y year";
				$months = $time_calulation->m;
				$time = "$time_calulation->y year";
				if($months == 1) {
					$time .= ", $months month";
				} else {
					$time .= ", $months months";
				}
			}
		} else {
			if($reminder['is_birthday']) {
				$time = "$time_calulation->y years";
			} else {
				/*$months = round(($time_calulation->m / 12) * 2) / 2;
				if($months != 0) {
					$months = ltrim($months,'0');
					$time = "$time_calulation->y$months years";
				} else {
					$time = "$time_calulation->y years";
				}*/
				$time = "$time_calulation->y years";
			}
		}
	}
	return $time;
}

/**
 * ACF Save JSON Dir
 */
add_filter('acf/settings/save_json', 'bhfe_acf_save_point');
function bhfe_acf_save_point( $path ) {
    $path = THEME_DIR . '/acf-json';
    return $path;
}
/**
 * ACF Load JSON Dir
 */
add_filter('acf/settings/load_json', 'bhfe_acf_load_point');
function bhfe_acf_load_point( $paths ) {
    // remove original path (optional)
    unset($paths[0]);
    $paths[] = THEME_DIR . '/acf-json';
    return $paths;
}


/**
 * Add hook to add shortcodes to the content during save. This is a hack to make the has_shortcode($post->content) work with other plugins
 */
add_action('save_post','append_to_bhfe_post_content');
function append_to_bhfe_post_content($post_id){
    global $post; 
    if(function_exists('get_field')) {
        $field_name = 'flexible_content';
        //iterate each flexible section
        if ( have_rows( $field_name, $post_id ) ) {
            $content = '';
            //$rowct = 0;
            while(have_rows( $field_name, $post_id )) {
                the_row();	
                //++$rowct;
                $row_layout = get_row_layout();
                if($row_layout == 'heading') { // && $rowct > 1
                    $heading = get_sub_field('heading');
                    $tag = get_sub_field('tag');
                    $content .= '<'.$tag.'>'.$heading.'</'.$tag.'>';
                }
                if($row_layout == 'list') {
                    $list_ordering = get_sub_field('list_ordering');
                    if( have_rows('list_items') ):
                        $content .= '<'.$list_ordering.'>';
                            while ( have_rows('list_items') ) : the_row();
                                $content .= '<li>';
                                    $label = get_sub_field('label');
                                    $text = get_sub_field('text');
                                    if($label != '') {
                                        $content .= $label.' ';
                                    }
                                    if($text != '') {
                                        $content .= $text;
                                    }
                                $content .= '</li>';
                            endwhile;
                        $content .= '</'.$list_ordering.'>';
                    
                    endif;
                }
                if($row_layout == 'heading-wysiwyg-repeater') {
                    if(have_rows('sections')) {
                        
                        while(have_rows('sections')) {
                            the_row();
                            $heading = get_sub_field( 'heading' );
                            $tag = get_sub_field('tag');
                            $contents = get_sub_field('content'); 
                            
                            if($heading != '') {
                                $content .= '<'.$tag.'>'.$heading.'</'.$tag.'>';
                            }
                            if($contents != '') {
                                $content .= $contents;
                            }
                            
                        }
                        
                    }
                }
                if($row_layout == 'two_column_content' || $row_layout == 'three_column_content') {
                    $heading_type = get_sub_field('heading_type');
                    $content .= '<'.$heading_type.'>'.get_sub_field( 'heading' ).'</'.$heading_type.'>';
                    $content .= get_sub_field('content');
                    $content .= '<'.$heading_type.'>'.get_sub_field( 'heading_2' ).'</'.$heading_type.'>';
                    $content .= get_sub_field('content_2');
                    if($row_layout == 'three_column_content') {
                        $content .= '<'.$heading_type.'>'.get_sub_field( 'heading_3' ).'</'.$heading_type.'>';
                        $content .= get_sub_field('content_3');
                    }
                }
                if($row_layout == 'wysiwyg') {
                    $content .= get_sub_field('content');
                }
                if($row_layout == 'shortcode') {
                    $content .= get_sub_field('shortcode');
                }
            }
            if($content == '') {
                $content = '<!--'.print_r(get_post_meta($post_id),true).'-->';
            }
            $post = get_post( $post_id );
            $post->post_content = $content;
            //Add excerpt if it isn't set
            /*if(!has_excerpt($post_id)) {
                $post->post_excerpt = substr(strip_tags($content), 0, 100);
            }*/
            remove_action('save_post','append_to_bhfe_post_content');
            wp_update_post( $post );
            add_action('save_post','append_to_bhfe_post_content');
        }
    }    
}

/** Filter input content */
function bhfe_content_filters($content,$span = true) {
    if(!$span) {
        return do_shortcode(str_replace('^',' ',$content));
    } else {
        return do_shortcode('<span class="br">'.str_replace('^','</span><span class="br">',$content) .'</span>');
    }
}
add_filter('bhfe_content','bhfe_content_filters');

/**
 * Simple shortcode to add spacing to inputs / text areas
 */
add_shortcode('space','space_shortcode');
function space_shortcode($atts) {
    $atts = shortcode_atts( array(
        'height' => '1rem',
        'display' => '',
    ), $atts );
    return '<span class="fog-spacer '.$atts['display'].'" style="height:'.$atts['height'].'"></span>';
}

add_filter('the_content','bhfe_content_filter',99,1);
function bhfe_content_filter($content) {
    global $post;
    ob_start();
    if(function_exists('get_field')) {
        $field_name = 'flexible_content';
        $flexible_post_id = get_the_ID();
        //iterate each flexible section
        if ( have_rows( $field_name, $flexible_post_id ) ) {			
            $band = 0;
            while ( have_rows( $field_name, $flexible_post_id ) ) : the_row();	
                ++$band;
                $row_layout = get_row_layout();
                $block_color_scheme = get_sub_field('color_scheme');
                $button_style = get_sub_field('button_style');
                $first_button_color = get_sub_field('first_button_color');
                $text_alignment = get_sub_field('text_alignment');
                $band_id = get_sub_field('band_id');
                echo '<section id="bhfe-content-band-'.$band.'" class="bhfe-section '.$row_layout.' block-style-'.$block_color_scheme.' button-style-'.$button_style.' button-alternating-'.$first_button_color.' text-alignment-'.$text_alignment.'">';
                        
					if($band_id != '') {
						echo '<div id="'.$band_id.'" tabindex="-1"></div>';
					}
					$filename = THEME_DIR.'/templates/flexible-content/' . get_row_layout().'.php';
					if(file_exists($filename)) {
						include($filename);
					}   
                echo '</section>';
            endwhile;                
        } else {
            $post_type = get_post_type();
            echo '<section class="'.$post_type.'">';
                echo '<div class="container container__normal block-style-dark text-alignment-left">';
                    echo '<div class="container-content">';
                        echo $content;
                    echo '</div>';
                echo '</div>';
            echo '</section>';
        }
    } else {
        echo '<section class="default">';
            echo '<div class="container container__normal block-style-dark text-alignment-left">';
                echo '<div class="container-content">';
                    echo $content;
                echo '</div>';
            echo '</div>';
        echo '</section>';
    }
    return ob_get_clean();
}

/**
 * Add flexible content band paddings to css in header
 */
add_action('wp_head','bhfe_acf_header_css');
function bhfe_acf_header_css() {
	$flexible_post_types = array('post','page','product');
	$post_type = get_post_type();
	if(in_array($post_type,$flexible_post_types) && function_exists('get_field')) {
		$acf_field_name = 'flexible_content';
		$acf_post_id = get_the_ID();
		//iterate each flexible section
		if ( have_rows( $acf_field_name, $acf_post_id ) ) :			
			$band = 0;
			$desktop_css = '';
			$tablet_css = '';
			$mobile_css = '';
			$globals = '';
			while ( have_rows( $acf_field_name, $acf_post_id ) ) : the_row();	
				++$band;
				$row_layout = get_row_layout();
                
				//Padding
				if(get_sub_field('desktop_padding') != '2rem 0rem')
					$desktop_css .= '#wise-content-band-'.$band.'{padding:'.get_sub_field('desktop_padding').';}';
				if(get_sub_field('tablet_padding') != '2rem 0rem')
					$tablet_css .= '#wise-content-band-'.$band.'{padding:'.get_sub_field('tablet_padding').';}';
				if(get_sub_field('mobile_padding') != '2rem 0rem')
					$mobile_css .= '#wise-content-band-'.$band.'{padding:'.get_sub_field('mobile_padding').';}';
			
                //Margins
				if(get_sub_field('desktop_margin') != '0rem')
					$desktop_css .= '#wise-content-band-'.$band.'{margin:'.get_sub_field('desktop_margin').';}';
				if(get_sub_field('tablet_margin') != '0rem')
                	$tablet_css .= '#wise-content-band-'.$band.'{margin:'.get_sub_field('tablet_margin').';}';
				if(get_sub_field('mobile_margin') != '0rem')
                	$mobile_css .= '#wise-content-band-'.$band.'{margin:'.get_sub_field('mobile_margin').';}';
			endwhile;
			?>
			<style type="text/css">
				<?php echo $globals; ?>
				@media all and (min-width: 73rem) {<?php echo $desktop_css; ?>}
				@media all and (max-width: 73rem) and (min-width: 48rem) { <?php echo $tablet_css; ?> } 
				@media all and (max-width: 48rem) { <?php echo $mobile_css; ?> }
			</style><?php 
		endif;
	}
}

function featherIcon($icon,$classes = NULL, $size = NULL, $color = NULL, $background = NULL) {
    $extras = '';
    if($color != null) {
        $extras .= 'color: '.$color.';';
    }
    if($background != null) {
        $extras .= 'background-color: '.$background.';';
    }
    ob_start(); 
    include('includes/feather/'.$icon.'.svg');
    $icon_url = ob_get_clean();
    $icon_html = '<span class="feather-icon '.$classes.'"';
    if($size) {
        $icon_html .= ' style="width:'.$size.'px;height:'.$size.'px;padding-bottom:0;font-size:'.$size.'px;'.$extras.'"';
    }
    $icon_html .= ' role="presentation">'.$icon_url.'</span>';
    return $icon_html;
} 


add_action( 'wp_ajax_nopriv_delete_account_email', 'delete_account_email_callback' );
add_action( 'wp_ajax_delete_account_email', 'delete_account_email_callback' );
function delete_account_email_callback() {
	global $current_user;
	$email = $current_user->user_email;
	$nonce = wp_create_nonce( 'email_'.$email );
	$delete_url = get_bloginfo('url').'/login?delete-account=1&email='.urlencode($email).'&wpnonce='.$nonce;
	//$delete_url = wp_nonce_url( $deletion_url, 'email_'.$email );
	
	$message = '<p style="text-align: center;">Someone requested your Fog Brain account be deleted.</p>';
	$message .= '<p style="text-align: center;">If you did not request this action, you can ignore this email. If you would like to continue deleting your account, click the link below.</p>';
	$message .= '<p style="text-align: center;">All of your reminders and personal data will be permanently deleted, immediately. Please save any data you want to keep before clicking the link; the data is not recoverable.</p>';
	$message .= '<p style="text-align: center;"><a href="'.$delete_url.'">'.$delete_url.'</a></p>';
	$message .= '<p style="text-align: center;">Thank you for using Fog Brain</p>';
	wp_mail($email,'Fog Brain account deletion',$message);

	wp_die();
}

function fog_block_wp_admin() {
	if ( is_admin() && ! current_user_can( 'administrator' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		wp_redirect( get_bloginfo('url') .'/login' );
		exit;
	}
}
add_action( 'admin_init', 'fog_block_wp_admin' );
?>