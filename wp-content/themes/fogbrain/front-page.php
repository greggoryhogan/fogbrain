<?php get_header(); ?>
<div class="container">
<?php if(is_user_logged_in()) {

} else {
    ?>
    <div class="row">
        <div class="col-9 col-md-9">
            <p>Save those pesky dates you keep forgetting because, let&rsquo;s face it, you&rsquo;re getting old.</p>
        </div>
        <div class="col-11 col-md-9 overlay-bg">
            <p>It looks like you aren&rsquo;t registered or signed in. We don&rsquo;t use passwords here, <!--you&rsquo;ll just forget that too, so--> enter your email below to get a passcode to login or register. P.S. We&rsquo;re new, check your spam folder.</p>
        </div>
        <div class="col-12 col-md-9">
            <form id="login" class="flex">
                <input type="email" value="" placeholder="email@example.com" id="email" />
                <input type="submit" value="Send" />
            </form>
        </div>
    </div>
    <?php 
}
?>
</div>
<?php get_footer(); ?>