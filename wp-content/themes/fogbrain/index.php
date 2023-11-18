<?php get_header(); ?>
<div class="container">
<?php if(have_posts()) {
    while(have_posts()) {
        the_post();
        ?>
        <div class="row">
            <?php
                if(isset($_GET['registration'])) {
                    $error = sanitize_text_field($_GET['registration']);
                    if($error == 'successful') {
                        echo '<div class="col-12 col-md-8">';
                         echo '<p class="error-notice">Thanks for signing up! Any time you need to log in again, just use the email in your account. You can configure your account settings on your profile page.</p>';
                        echo '</div>';
                    }
                    
            }  ?>
            <div class="col-9 col-md-9">
                <?php echo '<h1>'.get_the_title().'</h1>'; ?>
            </div>
            <div class="col-12 col-md-9 overlay-bg">
                <?php the_content(); ?>
            </div>
        </div><?php 
    }
} ?>
</div>
<?php get_footer(); ?>