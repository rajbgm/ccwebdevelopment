
	<?php
	// image
	$image = get_field('image') ?? false;
	$image_mobile = get_field('image_mobile') ?? false;
	$image_link = get_field('image_link') ?? false;
	$class = str_replace('wp-block-acf-image', 'image', get_block_wrapper_attributes()) ?? false;
	echo '<div ' . $class . '>';
	if ($image_link) {
		$link_url = $image_link['url'];
		$link_title = $image_link['title'];
		$link_target = $image_link['target'] ? $image_link['target'] : '_self';
		echo '<a href="' . $link_url . '" target="' . $link_target . '">';
	}
	if ($image) :
		echo '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '" loading="lazy">';
	endif;
	if ($image_mobile) :
		echo '<img src="' . $image_mobile['url'] . '" alt="' . $image_mobile['alt'] . '" loading="lazy" class="mobile">';
	endif;
	if ($image_link) {
		echo '</a>';
	}
	echo '</div>'
	?>