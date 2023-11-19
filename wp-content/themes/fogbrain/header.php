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
            <div class="row">
                <div class="col-9">
                    <?php if(is_home() || is_front_page()) { ?><h1><?php } ?>
                        <a href="/" title="Home" class="header-title"><span>Fog</span> Brain</a>
                    <?php if(is_home() || is_front_page()) { ?></h1><?php } ?>
                </div>
                <div class="col-3">
                    <div class="nav-container">
                        <button class="nav-toggle">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button><!-- #primary-mobile-menu -->
                    </div>
                </div>
            </div>
    </div>
</header>
<nav>
    <div class="container">
        <?php bhfe_wp_nav_menu(); ?>
    </div>
</nav>
<main role="main" id="Main">
    <div class="content">