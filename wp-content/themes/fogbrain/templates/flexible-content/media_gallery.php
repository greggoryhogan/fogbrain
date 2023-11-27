<?php 
$columns = get_sub_field('columns');
if(have_rows('media')) {
    echo '<div class="flexible-content media-gallery columns-'.$columns.'">';
        while(have_rows('media')) {
            the_row();
            $type = get_sub_field('type');
            $caption = get_sub_field('caption');
            echo '<div class="type-'.$type.'">';
                if($type == 'image') {
                    $image = get_sub_field('image');
                    $image_size = get_sub_field('image_size');
                    $link = get_sub_field('link');
                    if( $link ): 
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        echo '<a href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'">';
                    endif;
                    echo wp_get_attachment_image( $image, $image_size );
                    if($link):
                        echo '</a>';
                    endif;

                } else {
                    $embed_url = get_sub_field('oembed');
                    $aspect_ratio= get_sub_field('aspect_ratio');
                    echo '<div class="responsive-video" style="padding-bottom: '.$aspect_ratio.'%;">';
                        echo $embed_url;
                    echo '</div>';    
                }
                if($caption != '') {
                    echo '<p>'.$caption.'</p>';
                }
            echo '</div>';
        }
    echo '</div>';
}