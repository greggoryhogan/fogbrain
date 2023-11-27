<?php 
$heading = get_sub_field( 'heading' );
$subheading = get_sub_field('subheading');
$link = get_sub_field('cta_button'); 
$background_image = get_sub_field('background_image'); ?>
<div class="flexible-content hero">
    <?php if($heading != '') {
        echo '<h1 class="font-biggest">';
        echo bhfe_content_filters($heading);
        echo '</h1>';
    } ?>
    <?php if($subheading != '') {
        echo '<h2 class="font-normal">';
        echo bhfe_content_filters($subheading);
        echo '</h2>';
    } ?>
    <?php 
    if( $link ): 
        $link_url = $link['url'];
        $link_title = $link['title'];
        $link_target = $link['target'] ? $link['target'] : '_self';
        echo '<a class="btn" href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'" title="'.esc_html( $link_title ).'">'.bhfe_content_filters(esc_html( $link_title )).'</a>';
    endif; ?>
</div>
