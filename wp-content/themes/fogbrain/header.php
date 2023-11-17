<!DOCTYPE html>
<?php if (!defined('ABSPATH')) exit; ?>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php wp_head(); ?>
</head>
<body <?php body_class('bootstrap-wrapper'); ?>>
<header class="site-header">
    <div class="container">
        <?php if(is_home() || is_front_page()) { ?><h1><?php } ?>
            <a href="/" title="Home" class="header-title"><span>Fog</span> Brain</a>
        <?php if(is_home() || is_front_page()) { ?></h1><?php } ?>
    </div>

</header>
<main role="main" id="Main">