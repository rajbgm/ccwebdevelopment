<?php
      // content
      $plain_text = get_field('text') ?: '';
      if ($plain_text):
          echo '<p>' . $plain_text . '</p>';
      endif;
?>