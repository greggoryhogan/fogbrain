<?php 
$list_ordering = get_sub_field('list_ordering');
$list_style = get_sub_field('list_style');
if( have_rows('list_items') ):
    echo '<div class="flexible-content list">';
        echo '<'.$list_ordering.' class="'.$list_style.'">';
            while ( have_rows('list_items') ) : the_row();
                echo '<li>';
                    $label = get_sub_field('label');
                    $text = get_sub_field('text');
                    $link = get_sub_field('link');
                    if($label != '') {
                        echo '<span class="list-label">'.$label.'</span>';
                    }
                    if($text != '') {
                        echo '<span class="list-item">'.$text.'</span>';
                    }
                    $link = get_sub_field('link');
                    if( $link ): 
                        $link_url = $link['url'];
                        $link_title = $link['title'];
                        $link_target = $link['target'] ? $link['target'] : '_self';
                        echo '<span class="list-link"><a class="btn btn-small" href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'" title="'.esc_html( $link_title ).'">'.bhfe_content_filters(esc_html( $link_title )).'</a></span>';
                    endif;
                echo '</li>';
            endwhile;
        echo '</'.$list_ordering.'>';
    echo '</div>';
endif;