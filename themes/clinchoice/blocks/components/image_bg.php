<?php
$image_bg = get_field('image_bg') ?: '';
$image_bg_mobile = get_field('image_bg_mobile') ?: $image_bg;
?>
<div class="bg" style="--bg-image-desktop:url(<?php echo $image_bg ?>);--bg-image-mobile:url(<?php echo $image_bg_mobile ?>);"></div>