<?php 
$wysiwyg_content = get_sub_field('content');
echo '<div class="flexible-content wysiwyg">';
    echo '<div class="wysiwyg-content">';
        echo do_shortcode($wysiwyg_content);
    echo '</div>';
echo '</div>';
    