<?php
// text
$text = get_field('text') ?: '';
$class = str_replace('wp-block-acf-text', 'text', get_block_wrapper_attributes());
if ($text) :
  echo '<div '.$class.'>' . $text . '</div>';
endif;
