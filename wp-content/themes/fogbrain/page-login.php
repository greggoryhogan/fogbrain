<?php get_header(); ?>
<div class="container">
    <div class="row">
        <?php
        if(isset($_GET['access-error'])) {
                echo '<div class="col-12 col-md-8">';
                    $error = sanitize_text_field($_GET['access-error']);
                    $message = 'You don&rsquo;t have access to that person&rsquo;s reminders.';
                    if($error == 'invalid-code') {
                        $message .= ' Check your share code with the user; it&rsquo;s possible they gave you the wrong code or deleted it.';
                    } else {
                        $message .= ' If you were trying to access your own page, please log in below.';
                    }
                    echo '<p class="error-notice">'.$message.'</p>';
                echo '</div>';
        } else if(isset($_GET['logged-out'])) {
            $error = sanitize_text_field($_GET['logged-out']);
            if($error == 'profile') {
                echo '<div class="col-12 col-md-8">';
                    echo '<p class="error-notice">Please log in to access your profile.</p>';
                echo '</div>';
            }
        } else if(isset($_GET['action'])) {
            $error = sanitize_text_field($_GET['action']);
            if($error == 'email_recovered') {
                echo '<div class="col-12 col-md-8">';
                    echo '<p class="error-notice">Your email has been recovered. Please log in again using the recent recovery email address.</p>';
                echo '</div>';
            }
        }  ?>
        <div class="col-12 col-md-9">
            <?php if(isset($_GET['login-code'])) {
                $login_active = '';
                $code_active = 'is-active';
                $login_code = sanitize_text_field( $_GET['login-code'] );
            } else {
                $login_active = 'is-active';
                $code_active = '';
                $login_code = '';
            } ?>
            <div class="login-actions action-login <?php echo $login_active; ?>">
                <p>It looks like you aren&rsquo;t registered or signed in. Don&rsquo;t worry, it&rsquo;s all the same process.</p>
                <p>We don&rsquo;t use passwords here, <!--you&rsquo;ll just forget that too. E-->enter your email below and you&rsquo;ll be sent a code to log in.</p>
                <p>P.S. We&rsquo;re new - check your spam folder.</p>
                <form id="login" class="flex">
                    <input type="email" value="" placeholder="email@example.com" id="login-email" />
                    <input type="submit" value="Send" class="big-link" />
                </form>
                <div class="action-errors"></div>
            </div>
            <div class="login-actions action-code <?php echo $code_active; ?>">
                <p>A code has been sent to your email, <span id="emailsentto"></span>.<br><span class="backtologin has-cursor">Make a mistake entering your email?</span></p>
                <p>P.S. We&rsquo;re new, check your spam folder.</p>
                <form id="login-code" class="flex">
                    <input type="text" value="<?php echo $login_code; ?>" placeholder="000000" id="login-code-input" autocomplete="off" />
                    <input type="submit" value="Enter" class="big-link" />
                </form>
                <div class="action-errors"></div>
            </div>
        </div>
       
    </div>
</div>
<?php get_footer(); ?>