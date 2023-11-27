<?php 
$eyebrow = get_sub_field('eyebrow');
$gallery = get_sub_field('gallery');
$display = get_sub_field('display'); //grid, flexible
$columns = get_sub_field('columns');
$hover_effect = get_sub_field('hover_effect');
echo '<div class="flexible-content gallery">';
    if($eyebrow != '') {
        echo '<div class="eyebrow">'.$eyebrow.'</div>';
    }
    if(!empty($gallery)) {
        //print_r($gallery);
        echo '<div class="gallery-content display-'.$display.' columns-'.$columns.' hover-'.$hover_effect.'">';
            foreach($gallery as $image) {
                $link = get_field('gallery_link',$image['ID']);
                echo '<div>';
                if($link) {
                    $link_url = $link['url'];
                    $link_title = $link['title'];
                    $link_target = $link['target'] ? $link['target'] : '_self';
                    echo '<a href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'">';
                        echo wp_get_attachment_image( $image['ID'], 'full' );
                    echo '</a>';
                } else {
                    echo wp_get_attachment_image( $image['ID'], 'full' );
                }
                echo '</div>';
            }
        echo '</div>';
    }
echo '</div>';