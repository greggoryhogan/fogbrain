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

	wp_enqueue_script('fogbrain-main', $bhfe_dir.'/js/site.js', array('jquery'),'',true);
	global $fogbrain_user_id;
	wp_localize_script( 'fogbrain-main', 'site_js', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'user_id' => $fogbrain_user_id
	));
	
	wp_enqueue_style('fog-fonts','https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
	
}
add_action( 'wp_enqueue_scripts', 'register_bhfe_scripts' );

add_action('wp_head','bhfe_early_head_customization',5);
function bhfe_early_head_customization() { 
	?>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, viewport-fit=cover">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="apple-mobile-web-app-title" content="bhfe Repeating Arms">
	<meta name="theme-color" content="#fc9d00" />
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
		"name" => __( "User Page", "" ),
		"singular_name" => __( "User Page", "flms" ),
		'all_items' => __( "User Pages", "flms" ),
		'edit_item' => __( "Edit User Page", "flms" ),
		'update_item' => __( "Update User Page", "flms" ),
		'add_new' => __( "Add New User Page", "flms" ),
		'add_new_item' => __( "Add New User Page", "flms" ),
		'new_item_name' => __( "New User Page", "flms" ),
		'menu_name' => __( "User Pages", "flms" ),
		'back_to_items' => __( "&laquo; All User Pages", "flms" ),
		'not_found' => __( "No user pages found.", "flms" ),
		'not_found_in_trash' => __( "No user pages found in trash.", "flms" ),
	);
	$args = array(
		"label" => __( "User Page", "flms" ),
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
	register_post_type( "user-pages", $args );
}

/**
 * Add rewrite rules for course structure
 * This could probably be simplified, but it works!
 */
add_action('init','add_rewrite_rules');
function add_rewrite_rules() {
	add_rewrite_rule(
		"^u/([^/]+)/share/([^/]+)/?$",
		'index.php?user-pages=$matches[1]&share=$matches[2]',
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
				'post_type' => 'user-pages',
				'posts_per_page' => 1,
				'author' =>  $fogbrain_user_id,
			));
		
			if ($query->have_posts()) {
				while ($query->have_posts()) {
					$query->the_post();
					wp_redirect( get_permalink(get_the_ID()));
					exit;
				}
			}	
			wp_reset_postdata();
		}	
	} else if($post->post_type == 'user-pages') {
		$login_redirect = get_bloginfo('url').'/login?access-error=';
		//redirect users from viewing other peoples pages
	 	if($post->post_author != $fogbrain_user_id) {
			if(isset($wp->query_vars['share'])) {
				$share_code = $wp->query_vars['share'];
				$share_codes = maybe_unserialize(get_post_meta($post->ID,'share_codes',true));
				if(!is_array($share_codes)) {
					$share_codes = array();
				}
				if(!in_array($share_code,$share_codes)) {
					wp_redirect($login_redirect.'invalid-code');	
				}
			} else {
				wp_redirect($login_redirect.'private');	
			}
			
		}
	}
}

/**
 * Set email content types to be html
 */
add_filter( 'wp_mail_content_type','fogbrain_email_types' );
function fogbrain_email_types(){
    return "text/html";
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
			$message .= '<h2 style="text-align: center;">'.$random_number.'</h2>';
			$message .= '<p style="text-align: center;">This code is valid for 10 minutes. Please go back to the Fog Brain login page and enter your code.</p>';
			$message .= '<p style="text-align: center;">You can also click the link below to log in:<br><a href="'.get_bloginfo('url').'/login/?login-code='.$random_number.'">'.get_bloginfo('url').'/login/?login-code='.$random_number.'</a></p>';
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
					'post_title' => "$username's Brain",
					'post_type' => 'user-pages',
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
				'post_type' => 'user-pages',
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
							'user_url' => get_the_permalink(get_the_ID())
						)
					);
				}
				wp_reset_postdata();
				wp_die();
			}
		}
	}
}
?>