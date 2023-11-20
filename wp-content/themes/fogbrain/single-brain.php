<?php get_header(); ?>
<div class="container">
<?php if(have_posts()) {
    while(have_posts()) {
        the_post();
        global $post, $current_user;
        $author_id = get_the_author_meta('ID');
        $time_zone = get_user_meta($current_user->ID,'timezone',true);
        date_default_timezone_set($time_zone);
        ?>
        <div class="row">
            <?php fog_error_notifications();  ?>
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
                <?php $share = get_post_meta($post->ID,'share_code',true);
                if($share != '' && $author_id == $current_user->ID) {
                    echo '<div class="share-link"><span id="share-link">'.get_permalink().'share/'.$share.'</span><div class="copy"></div><a href="/profile" class="edit"></div></div>';
                } ?>
            </div>
            <div class="col-12 col-md-9">
                <?php the_content(); ?>
            </div>
        </div><?php 
    }
} ?>
</div>
<?php get_footer(); ?>