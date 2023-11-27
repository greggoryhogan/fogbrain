<?php 
$shortcode = get_sub_field('shortcode');
echo '<div class="flexible-content shortcode full-width">';
    if($shortcode == '[ldgr-group-code-registration-form]' && !is_user_logged_in()) {
        echo '<p class="text-center"><em>You must be logged in to register for a group. Please <a href="'.get_bloginfo('url').'/login/">log in</a> or <a href="'.get_bloginfo('url').'/register/" data-type="page" data-id="'.get_the_ID().'">register</a>.</em></p>';
    } else {
        echo do_shortcode($shortcode);
    }
echo '</div>';