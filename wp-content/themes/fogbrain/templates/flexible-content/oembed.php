<?php 
$embed_url = get_sub_field('embed_url');
$aspect_ratio= get_sub_field('aspect_ratio');
echo '<div class="flexible-content full-width">';
    echo '<div class="responsive-video" style="padding-bottom: '.$aspect_ratio.'%;">';
        echo $embed_url;
    echo '</div>';    
echo '</div>';