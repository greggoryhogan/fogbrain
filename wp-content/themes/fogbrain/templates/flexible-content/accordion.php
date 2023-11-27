<?php 
$heading_size = get_sub_field('heading_size');
if( have_rows('accordion_items') ):
    echo '<div class="flexible-content accordion">';
        while ( have_rows('accordion_items') ) : the_row();
            $heading = get_sub_field('heading');
            $content = get_sub_field('content');
            $default_state = get_sub_field('default_state');
            echo '<div class="accordion__item is-'.$default_state.'">';
                echo '<div class="accordion__title '.$heading_size.' bodoni">'.$heading.featherIcon('chevron-up','','25').'</div>';
                echo '<div class="accordion__content">'.bhfe_content_filters($content).'</div>';
            echo '</div>';
        endwhile;
    echo '</div>';
endif;