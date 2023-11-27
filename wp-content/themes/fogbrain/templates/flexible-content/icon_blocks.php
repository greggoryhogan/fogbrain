<?php 
$alignment = get_sub_field('alignment');
$columns = get_sub_field('columns');
$column_bg = get_sub_field('columns_background_color');
$heading_tag = get_sub_field('heading_tag');
$heading_size = get_sub_field('heading_size');
$font_weight = get_sub_field('font_weight');
if( have_rows('blocks') ):
    echo '<div class="flexible-content icon-blocks align-'.$alignment.' columns-'.$columns.'">';
    while ( have_rows('blocks') ) : the_row();
        $link = get_sub_field('button');
        echo '<div class="icon-block';
        if($link) {
            echo ' has-link';
        }
        echo '" style="background-color: '.$column_bg.'">';
            if( $link ): 
                $link_url = $link['url'];
                $link_title = $link['title'];
                $link_target = $link['target'] ? $link['target'] : '_self';
                echo '<a class="block-link" href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'" title="'.bhfe_content_filters(esc_html( $link_title ),false).'"></a>';
            endif;
            $icon_type = get_sub_field('icon_type');
            if($icon_type == 'image') {
                $image = get_sub_field('image');
                echo '<div class="icon-image">';
                    echo wp_get_attachment_image( $image, 'large' );
                echo '</div>';
            } else {
                $icon = get_sub_field('icon');
                $icon_size = get_sub_field('icon_size');
                $icon_color = get_sub_field('icon_color');
                echo '<div class="icon-image">';
                    echo featherIcon($icon,null,$icon_size,$icon_color);
                echo '</div>';
            }
            echo '<div class="icon-block-content">';
                $heading = get_sub_field('heading');
                echo '<'.$heading_tag.' class="font-'.$heading_size.' font-weight-'.$font_weight.'">'.$heading.'</'.$heading_tag.'>';
                $text = get_sub_field('text');
                echo $text;
                if( $link ) {
                    if($link_title != '') {
                        echo '<a class="btn" href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'" title="'.esc_html( $link_title ).'">'.bhfe_content_filters(esc_html( $link_title )).'</a>';
                    }
                }
            echo '</div>';
        echo '</div>';
    endwhile;
    echo '</div>';
endif;