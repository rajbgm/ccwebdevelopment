<?php
// shortcode
$shortcode = get_field('shortcode') ?: '';
if ($shortcode) :
  echo $shortcode;
endif;
