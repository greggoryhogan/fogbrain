<?php 
$heading = get_sub_field( 'heading' );
$tag = get_sub_field('tag');
$size = get_sub_field('size');
$font_weight = get_sub_field('font_weight');
$heading_links_to = get_sub_field('heading_links_to'); ?>
<div class="flexible-content heading full-width">
    <?php if($heading != '') {
        echo '<'.$tag.' class="'.$size.' font-weight-'.$font_weight.'">';
        if($heading_links_to != '') {
            echo '<a href="'.$heading_links_to.'" title="'.$heading.'">';
        }
        echo bhfe_content_filters($heading);
        if($heading_links_to != '') {
            echo '</a>';
        }
        echo '</'.$tag.'>';
    } ?>
</div>
