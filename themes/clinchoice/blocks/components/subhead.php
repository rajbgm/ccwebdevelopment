<?php
// subhead
$subhead = get_field('subhead') ?: '';
if ($subhead) :
  echo '<div class="subhead"><'.$subhead['subhead_tag'].'>' . $subhead['subhead_content'] . '</'.$subhead['subhead_tag'].'></div>';
endif;
