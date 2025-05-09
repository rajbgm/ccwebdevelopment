<?php
$headline = get_field('headline') ?? null;
$class = str_replace('wp-block-acf-headline', '', get_block_wrapper_attributes()) ?: '';
if ($headline) :
  echo '<div class="headline"><' . $headline['headline_tag'] . ' ' . $class . '>' . $headline['headline_content'] . '</' . $headline['headline_tag'] . '></div>';
endif;
