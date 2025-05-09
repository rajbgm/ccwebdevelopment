<?php get_header() ?>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
			<?php $color_code = get_field('color_code');echo $color_code;  ?>
				<?php the_content() ?>
			<?php endwhile; endif; 
			
			$author = get_queried_object(); // Current author object
$author_id = $author->ID;

$post_types = get_post_types(['public' => true], 'objects');

$published_post_types = [];

foreach ($post_types as $post_type) {
    // Count posts for this author + post type
    $count = new WP_Query([
        'post_type'      => $post_type->name,
        'author'         => $author_id,
        'post_status'    => 'publish',
        'posts_per_page' => 1, // We just need to check if at least 1 exists
    ]);

    if ($count->have_posts()) {
        $published_post_types[] = $post_type->labels->singular_name;
    }

    wp_reset_postdata();
}

// Display the result
if (!empty($published_post_types)) {
    echo '<h3>Post Types Published by ' . esc_html($author->display_name) . ':</h3>';
    echo '<ul>';
    foreach ($published_post_types as $post_type_name) {
        echo '<li>' . esc_html($post_type_name) . '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>This author has not published any posts yet.</p>';
}
?>

<?php get_footer() ?>