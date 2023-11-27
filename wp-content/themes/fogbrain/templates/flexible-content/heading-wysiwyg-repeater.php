<?php 
if(have_rows('sections')) {
    echo '<div class="flexible-content heading-wysiwyg-repeater">';
        while(have_rows('sections')) {
            the_row();
            $heading = get_sub_field( 'heading' );
            $tag = get_sub_field('tag');
            $size = get_sub_field('size');
            $font_weight = get_sub_field('font_weight');
            $content = get_sub_field('content');
            $link = get_sub_field('button');
            if( $link ): 
                $link_url = $link['url'];
                $link_title = $link['title'];
                $link_target = $link['target'] ? $link['target'] : '_self';
            endif; ?>
            <div class="heading-w-content">
                <?php if($heading != '') {
                     echo '<'.$tag.' class="'.$size.' font-weight-'.$font_weight.'">';
                     if( $link ): 
                        echo '<a href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'">';
                    endif;
                    echo bhfe_content_filters($heading);
                    if( $link ): 
                        echo '</a>';
                    endif;
                    echo '</'.$tag.'>';
                }
                if($content != '') {
                    echo $content;
                }
                echo '<div class="flexible-content cta-buttons columns-auto">';
                    if( $link ): 
                        echo '<a class="btn" href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'" title="'.esc_html( $link_title ).'">'.bhfe_content_filters(esc_html( $link_title )).'</a>';
                    endif;
                echo '</div>';
                ?>
            </div><?php 
        }
    echo '</div>';
}