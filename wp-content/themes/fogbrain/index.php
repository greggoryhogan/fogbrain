<?php get_header(); ?>
<div class="container">
<?php if(have_posts()) {
    while(have_posts()) {
        the_post();
        ?>
        <div class="row">
            <div class="col-9 col-md-9">
                <?php echo '<h1>'.get_the_title().'</h1>'; ?>
            </div>
            <div class="col-12 col-md-9">
                <?php the_content(); ?>
            </div>
        </div><?php 
    }
} ?>
</div>
<?php get_footer(); ?>