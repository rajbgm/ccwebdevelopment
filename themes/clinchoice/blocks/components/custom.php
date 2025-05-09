<?php
// post loop
$post_loop = get_field('custom') ?: '';
if ($post_loop) :
  get_template_part('partials/' . $post_loop);
endif;
?>