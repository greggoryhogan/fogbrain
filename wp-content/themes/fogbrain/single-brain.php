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
                <?php $user_avatar = get_user_meta($author_id,'user_avatar',true); 
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
                <?php echo '<h1 class="flex align-items-center">'.$image.get_the_author_meta('display_name');
                if($author_id == $current_user->ID) {
                    echo '<a href="/profile" class="edit name-edit"></a>';
                }
                echo '</h1>'; ?>
                <?php $share = get_post_meta($post->ID,'share_code',true);
                if($share != '' && $author_id == $current_user->ID) {
                    echo '<div class="share-link"><span id="share-link">'.get_permalink().'share/'.$share.'</span><span class="share-page-text">Share: </span><div class="copy"></div><a href="/profile" class="edit"></a></div>';
                } else if($share == '' && $author_id == $current_user->ID) {
                    echo '<div class="share-link not-active">Visibility: Hidden<a href="/profile" class="edit"></a></div>';
                } ?>
            </div>
            <div class="col-12 col-md-9">
                
                <div class="reminder-summary">
                    <?php if($author_id == $current_user->ID) {
                        echo '<div class="big-link" id="add-reminder">Add Reminder</div>';
                        echo '<div class="edit-reminders big-link has-cursor">Edit</div>';
                        echo '<div class="big-link done-editing">Finish Editing</div>';
                        echo '<div class="reminder-form">';
                            echo '<form id="reminder-form">';
                                //https://docs.gravityforms.com/adding-a-form-to-the-theme-file/#function-call
                                //gravity_form( 1, false, false ); //to do ajax, , false, null, true
                                //echo '<div class="error-notice"></div>';
                                echo '<p>Add your reminder. You can type things like &ldquo;My birthday is November 25, 1985&rdquo;, &ldquo;I have been married since 9/10/2021&rdquo; or &ldquo;We were married in Las Vegas.&rdquo; If you&rsquo;re using a date in your reminder, be sure to include at least the month and year.</p>';
                                echo '<p>For your security, do not add sensitive information such as social security or phone numbers.</p>';
                                echo '<input id="prompt" type="text" placeholder="Add your reminder" />';
                                echo '<div class="flex no-wrap reminder-fields">';
                                    echo '<input type="text" id="note" placeholder="An optional note about this reminder" />';
                                    echo '<input type="text" id="tag" placeholder="Category" class="flex-33" />';
                                echo '</div>';
                                echo '<label><input type="checkbox" id="public" checked="checked" /> This reminder is public</label>';
                                echo 'Public reminders will remain private if your page is not shared.<br>';
                                echo '<input type="submit" value="Add Reminder" class="big-link has-cursor" />';
                            echo '</form>';
                        echo '</div>'; 
                    } ?>
                    <div class="reminders">
                    <?php $reminders = maybe_unserialize(get_post_meta($post->ID,'user_reminders',true));
                    if($reminders) {
                        echo process_gpt_reminders($reminders, $author_id);
                    } ?>
                    </div>
                    <div class="big-link done-editing bottom-editor">Finish Editing</div>
                    <?php 
                    
                    ?>
                </div>
            </div>
        </div><?php 
    }
} ?>
</div>
<?php get_footer(); ?>