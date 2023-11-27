<?php 
$tag = get_sub_field('tag');
$size = get_sub_field('size');
$font_weight = get_sub_field('font_weight');
$remove_column_gap_on_mobile = get_sub_field('remove_column_gap_on_mobile');
if(have_rows('columns')) {
    echo '<div class="flexible-content flexible-grid row">';
        while(have_rows('columns')) {
            the_row();
            $column_width = get_sub_field('column_width');
            $border = get_sub_field('border');
            echo '<div class="flexible-column col-md-'.$column_width.'">';
                if($border == 'show-border') {
                    echo '<div class="show-border">';
                }
                if(have_rows('rows')) {
                    while(have_rows('rows')) {
                        the_row();
                        $heading = get_sub_field( 'heading' );
                        $content = get_sub_field('content'); ?>
                        <div class="row-content">
                            <?php if($heading != '') {
                                echo '<'.$tag.' class="'.$size.' font-weight-'.$font_weight.'">'.bhfe_content_filters($heading).'</'.$tag.'>';
                            }
                            if($content != '') {
                                echo $content;
                            }
                            echo '<div class="flexible-content cta-buttons columns-auto">';
                                $link = get_sub_field('button');
                                if( $link ): 
                                    $link_url = $link['url'];
                                    $link_title = $link['title'];
                                    $link_target = $link['target'] ? $link['target'] : '_self';
                                    echo '<a class="btn" href="'.esc_url( $link_url ).'" target="'.esc_attr( $link_target ).'" title="'.esc_html( $link_title ).'">'.bhfe_content_filters(esc_html( $link_title )).'</a>';
                                endif;
                            echo '</div>';
                            ?>
                        </div><?php 
                    }
                }
                if($border == 'show-border') {
                    echo '</div>';
                }
            echo '</div>';
        }
    echo '</div>';
}