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
            <div class="col-12 col-md-9">
                <?php $user_avatar = get_user_meta($current_user->ID,'user_avatar',true); 
                    if($user_avatar != '') {
                        $profile_img	= @json_decode($user_avatar);
                        $image = '<div id="profile_image">'.wp_get_attachment_image($profile_img->attachment_id,'thumbnail').'</div>';
                        $saved_profile_img_id = $profile_img->attachment_id;
                        $has_image = true;
                    } else {
                        $has_image = false;
                        $image = '';
                        $saved_profile_img_id = '';
                    }
                ?>
                <?php echo '<h1 class="flex align-items-center">'.$image.get_the_author_meta('display_name').'</h1>'; ?>
                <?php $share = get_post_meta($post->ID,'share_code',true);
                if($share != '' && $author_id == $current_user->ID) {
                    echo '<div class="share-link"><span id="share-link">'.get_permalink().'share/'.$share.'</span><span class="share-page-text">Share: </span><div class="copy"></div><a href="/profile" class="edit"></a></div>';
                } else if($share == '' && $author_id == $current_user->ID) {
                    echo '<div class="share-link not-active">Visibility: Hidden<a href="/profile" class="edit"></a></div>';
                } ?>
            </div>
            <div class="col-12 col-md-9">
                <?php if($author_id == $current_user->ID) {
                    echo '<div class="big-link" id="add-reminder">Add Reminder</div>';
                    echo '<div class="reminder-form">';
                        //https://docs.gravityforms.com/adding-a-form-to-the-theme-file/#function-call
                        gravity_form( 1, false, false ); //to do ajax, , false, null, true
                    echo '</div>'; 
                } ?>
                
                <?php $reminders = maybe_unserialize(get_post_meta($post->ID,'reminders',true));
                if($reminders) {
                    print_user_reminders($reminders, $author_id);
                    //echo '<pre>'.print_r($reminders,true).'</pre>';
                    
                } ?>
            
            </div>
        </div><?php 
    }
} ?>
</div>
<?php get_footer(); ?>