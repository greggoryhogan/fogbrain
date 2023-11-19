<?php get_header(); ?>
<div class="container">
<?php if(have_posts()) {
    while(have_posts()) {
        the_post();
        $author_id = get_the_author_meta('display_name');
        ?>
        <div class="row">
            <?php
                if(isset($_GET['registration'])) {
                    $error = sanitize_text_field($_GET['registration']);
                    if($error == 'successful') {
                        echo '<div class="col-12 col-md-8">';
                         echo '<p class="error-notice">Registration successful! Any time you need to log in again, just use the email in your account. You can configure your account settings on your <a href="/profile" title="profile">profile page</a>.</p>';
                        echo '</div>';
                    }
                    
            }  ?>
            <?php
                if(isset($_GET['login-action'])) {
                    $action = sanitize_text_field($_GET['login-action']);
                    if($action == 'returning-user') {
                        global $current_user;
                        echo '<div class="col-12 col-md-8">';
                            echo '<p class="error-notice">Welcome back, '.$current_user->display_name.'!</p>';
                        echo '</div>';
                    }
            }  ?>
            <div class="col-9 col-md-9">
                <?php echo '<h1>'.get_the_author_meta('display_name').'&rsquo;s Foggy Brain</h1>'; ?>
            </div>
            <div class="col-12 col-md-9">
                <?php the_content(); ?>
            </div>
        </div><?php 
    }
} ?>
</div>
<?php get_footer(); ?>