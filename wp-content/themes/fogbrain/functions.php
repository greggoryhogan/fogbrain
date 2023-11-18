<?php

define('BHFE_URL',get_bloginfo('url'));
define( 'THEME_DIR', get_template_directory() );
define( 'THEME_URI', get_template_directory_uri() );

function register_bhfe_scripts() {
	//Theme version
	$version = wp_get_theme()->get('Version');
	//Deliver minified css for staging/prod
	$bhfe_dir = BHFE_URL.'/wp-content/themes/fogbrain';
	//Main Stylesheet
	wp_enqueue_style( 'bhfe-main', $bhfe_dir.'/css/main.css', false, $version, 'all' );
	wp_enqueue_style('bootstrap-grid','https://cdn.jsdelivr.net/gh/dmhendricks/bootstrap-grid-css@4.1.3/dist/css/bootstrap-grid.min.css', false, '1.0', 'all');
	wp_enqueue_script('bhfe-main', $bhfe_dir.'/js/site.js', array('jquery'),'',true);
	
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
?>