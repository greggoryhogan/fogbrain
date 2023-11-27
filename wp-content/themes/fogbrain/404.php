<?php get_header(); ?>
<div class="container">
    <div class="row">   
        <div class="col-9 col-md-9">
            <?php echo '<h1>Uh oh!</h1>'; ?>
        </div>
        <div class="col-12 col-md-9">
            <?php echo '<h2 style="margin-bottom: 15px;">The page you&rsquo;re looking for doesn&rsquo;t exist!</h2>'; ?>
            <p style="margin-bottom: 60px;">If you were given a share code, the user may have changed their profile url or updated the share code.</p>
            <a href="<?php echo get_bloginfo('url'); ?>" class="big-link has-cursor has-arrow" style="display: inline-block;">Head Home</a>
        </div>
    </div>
</div>
<?php get_footer(); ?>