<?php get_header(); ?>
<div class="container">
    <div class="row">
        <div class="col-9 col-md-9">
            <h1>Your Profile</h1>
        </div>
        <div class="col-12 col-lg-7">
            <?php global $current_user; ?>
            <div class="form-errors"></div>
            <form id="profile-form">
                <div class="profile-field">
                    <label>Name</label>
                    <input type="text" value="<?php echo $current_user->display_name; ?>" id="name" />
                </div>
                <div class="profile-field">
                    <label>Email</label>
                    <input type="text" value="<?php echo $current_user->user_email; ?>" id="email" />
                    <div class="action-error email-error"></div>
                </div>
                <div class="profile-field">
                    <?php $profile_page_id = get_user_meta($current_user->ID,'user_profile_page',true);
                    $profile_page = get_post($profile_page_id); ?>
                    <label>Profile URL</label>
                    <div class="desc"><?php echo get_bloginfo('url'); ?>/u/<span class="update-page-name-desc"><?php echo $profile_page->post_name; ?></span></div>
                    <div class="check-status is-success">
                        <input type="text" value="<?php echo $profile_page->post_name; ?>" id="profile-url" data-current-url="<?php echo $profile_page->post_name; ?>" />
                    </div>
                    <div class="action-error profile-page-name-error"></div>
                </div>
                <div class="profile-field">
                    <label>Share Code</label>
                    <div class="desc">If you want to share your page, set the code here. Keep in mind anyone with this code can access your reminders. Leaving the code blank will keep your page private.<br><?php echo get_bloginfo('url'); ?>/u/<span class="update-page-name-desc"><?php echo $profile_page->post_name; ?></span>/share/<span class="update-share-name"><?php echo get_post_meta($profile_page_id,'share_code',true); ?></div>
                    <input type="text" value="<?php echo get_post_meta($profile_page_id,'share_code',true) ?>" id="share-code" />
                    <div class="action-error share-code-error"></div>
                </div>
                <input type="submit" id="save-profile" value="Save Profile"  class="big-link" />
            </form>
        </div>
    </div>
</div>
<?php get_footer(); ?>