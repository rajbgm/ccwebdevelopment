<?php
// content
$rich_text = get_field('rich_text') ?: '';
$class = str_replace('wp-block-acf-image', 'image', get_block_wrapper_attributes()) ?? false;
if ($rich_text) :
    echo '<div class="text ' . $class . '">' . $rich_text . '</div>';
endif;
