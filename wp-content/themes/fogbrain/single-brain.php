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
                                echo '<p>Add your reminder. You can type things like &ldquo;My birthday is November 25, 1985&rdquo;, &ldquo;I was married on 9/10/2021&rdquo; or &ldquo;My dog was born on 11/17/2017&rdquo;</p>';
                                echo '<input id="prompt" type="text" placeholder="Add your reminder" />';
                                echo '<input type="text" id="note" placeholder="An optional note about this reminder" />';
                                echo '<label><input type="checkbox" id="public" checked" /> This reminder is public</label>';
                                echo 'Public reminders will remain private if your page is not shared.<br>';
                                echo '<input type="submit" value="Add Reminder" class="big-link has-cursor" />';
                            echo '</form>';
                        echo '</div>'; 
                    } ?>
                    <div class="reminders">
                    <?php $reminders = maybe_unserialize(get_post_meta($post->ID,'user_reminders',true));
                    if($reminders) {
                        //$prompt = 'My birthday is 11/25/1985';
                        //$prompt = 'My girlfriend started work on September 25, 2021';
                        //$prompt = "Matt's girlfriend started work on September 25, 2021";
                        //$prompt = 'I got married on September 10 2021';
                        //$prompt = 'I got my dog 11/17/2021';
                        //$prompt = 'My dog was born 11/17/2021';
                        //get_chat_gpt_response($prompt);
                        //print_user_reminders($reminders, $author_id);
                        //echo '<pre>'.print_r($reminders,true).'</pre>';
                        process_gpt_reminders($reminders);
                    } ?>
                    </div>
                    <?php 
                    
                    ?>
                </div>
            </div>
        </div><?php 
    }
} ?>
</div>
<?php get_footer(); ?>