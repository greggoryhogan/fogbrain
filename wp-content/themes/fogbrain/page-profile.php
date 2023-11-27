<?php get_header(); 
//get_user_meta($current_user->ID,'user_profile_page','35');
?>
<div class="container">
    <div class="row">
        <div class="col-9 col-md-9">
            <h1>Your Profile</h1>
        </div>
        <div class="col-12 col-lg-7">
            <?php global $current_user; ?>
            <div class="form-errors"></div>
            <form id="profile-form">
                <div class="profile-field avatar">
                    <?php $user_avatar = get_user_meta($current_user->ID,'user_avatar',true); 
                    if($user_avatar != '') {
                        $profile_img	= @json_decode($user_avatar);
                        $image = wp_get_attachment_image($profile_img->attachment_id,'thumbnail');
                        $saved_profile_img_id = $profile_img->attachment_id;
                        $has_image = true;
                    } else {
                        $has_image = false;
                        $image = '';
                        $saved_profile_img_id = '';
                    }
                    ?>
                    
                        <div id="profile_image"><?php echo $image; ?></div>
                        <div class="flex-1">
                            <label>Profile Photo <div class="delete-profile-photo <?php if($has_image) echo 'is-active'; ?>">Remove</div></label>
                            <div class="desc">Displayed on your reminders page</div>
                            <input type="file" value="<?php echo $current_user->display_name; ?>" id="avatar" data-cont="#profile_image" data-name="" />
                            <input type="hidden" value="<?php echo $saved_profile_img_id; ?>" id="profile_image_id" />
                        </div>
                    
                    <div class="action-error avatar-error"></div>
                </div>
                <div class="profile-field">
                    <label>Name</label>
                    <div class="desc">The display name for your page</div>
                    <input type="text" value="<?php echo $current_user->display_name; ?>" id="name" />
                </div>
                <div class="profile-field">
                    <label>Email</label>
                    <div class="desc">Login emails will be sent to this email</div>
                    <input type="text" value="<?php echo $current_user->user_email; ?>" id="email" />
                    <div class="action-error email-error"></div>
                </div>
                <div class="profile-field">
                    <?php $profile_page_id = get_user_meta($current_user->ID,'user_profile_page',true);
                    $profile_page = get_post($profile_page_id); ?>
                    <label>Profile URL</label>
                    <div class="desc"><span class="desktop-only"><?php echo get_bloginfo('url'); ?></span><span>/u/</span><span class="update-page-name-desc"><?php echo $profile_page->post_name; ?></span></div>
                    <div class="check-status is-success">
                        <input type="text" value="<?php echo $profile_page->post_name; ?>" id="profile-url" data-current-url="<?php echo $profile_page->post_name; ?>" />
                    </div>
                    <div class="action-error profile-page-name-error"></div>
                </div>
                <div class="profile-field">
                    <label>Share Code</label>
                    <div class="desc">If you want to make your page public and share your reminders, set the code here. 
                        Anyone with this code can access your reminders. 
                        <br><em>Leaving the code blank will keep your page private.</em>
                        <br><span class="desktop-only"><?php echo get_bloginfo('url'); ?></span><span>/u/</span><span class="update-page-name-desc"><?php echo $profile_page->post_name; ?></span><span>/share/</span><span class="update-share-name"><?php echo get_post_meta($profile_page_id,'share_code',true); ?></div>
                    <input type="text" value="<?php echo get_post_meta($profile_page_id,'share_code',true) ?>" id="share-code" />
                    <div class="action-error share-code-error"></div>
                </div>
                <div class="profile-field">
                    <label>Time Zone</label>
                    <div class="desc">Dates and reminders will localize to your time zone. <br>Choose a time zone closest to your location.</div>
                    <?php 
                    $user_timezone = get_user_meta($current_user->ID,'timezone',true);
                    if($user_timezone == '') {
                        $user_timezone = 'America/New_York';
                    }
                    $str = file_get_contents(THEME_DIR.'/assets/timezone.json');
                    $json = json_decode($str, true); 
                    if($str) {
                        echo '<select id="timezone">';
                            foreach($json as $timezone) {
                                echo '<option value="'.$timezone.'"';
                                    if($timezone == $user_timezone) {
                                        echo ' selected';
                                    }
                                echo '>'.str_replace('/',' / ', str_replace('_',' ',$timezone)).'</option>';
                            }
                        echo '</select>';
                    }
                    ?>
                    <div class="action-error email-error"></div>
                </div>
                <input type="submit" id="save-profile" value="Save Profile"  class="big-link" />
            </form>

            <div class="danger-zone gray-bg">
                <h3>Danger Zone</h3>
                <p><strong>Delete Your Account</strong><br>
                If you&rsquo;d like to delete your account, click the link below. A confirmation will be sent to your email to delete your account.</p>
                <p>All of your reminders and personal data will be permanently deleted, immediately. Please save any data you&rsquo;d like to keep before requesting an account deletion; the data is not recoverable.</p>
                <div id="delete-my-account">Delete my account</div>
                <div id="delete-request-confirmation"></div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>