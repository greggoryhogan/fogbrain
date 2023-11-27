<?php 
$text = get_sub_field( 'text' );
$attribution = get_sub_field('attribution'); ?>
<div class="flexible-content blockquote">
    <?php 
    if($text != '') {
       echo $text;
    } ?>
</div>
