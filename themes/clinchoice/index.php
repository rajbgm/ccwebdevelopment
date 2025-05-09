<?php get_header(); ?>


<?php
$post_id = 158; // Replace with the actual Post ID
$post = get_post($post_id);

if ($post) {
  $post_content = apply_filters('the_content', $post->post_content);
  echo $post_content;
} else {
  echo 'Post not found';
}
?>

<?php
// category info
if (get_category(get_query_var('cat')) !== null) {
  $category = get_category(get_query_var('cat')) ?? false;
  $cat_id = $category->cat_ID  ?? false;
  $cat_name = $category->cat_name  ?? false;
  $cat_desc = $category->category_description  ?? false;
}

// pagination
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
?>

<section id="spotlight">
  <div class="wrapper">

    <h2>Resources</h2>

    <?php
    $args = array(
      'p' => 916,
      'posts_per_page' => 1
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

        $categories = get_the_category(get_the_ID());
        foreach ($categories as $category) {
          $category_name = $category->name;
          $category_image = $category->description;
        }

        // image
        if (get_the_post_thumbnail_url() != '') {
          $image = '<img src="' . get_the_post_thumbnail_url() . '" />';
          $image_class = 'post-image';
        } else {
          $image = $category_image;
          $image_class = 'category-image';
        }

        // link
        $download = get_field('download_file', get_the_ID()) ?? '';
        if ($download) {
          $link = 'javascript:;';
        } else {
          $link = get_the_permalink();
        }
    ?>

        <div class="flex has-gated">
          <div class="image">
            <?php echo $image ?>
          </div>
          <div class="text">
            <div class="content">
              <h3 class="category eyebrow-1"><?php echo $category_name; ?></h3>
              <h3 class="title blue-text"><?php the_title(); ?></h3>
              <p><?php echo wp_trim_words(get_the_content(), 20); ?></p>
              <a href="<?php echo $link ?>" class="button clear arrow more" <?php if ($download) {
                                                                              echo 'data-file="' . $download . '"';
                                                                            } ?>>Read More</a>
            </div>
          </div>
        </div>

    <?php
      endwhile;
    endif;
    ?>

  </div>
</section>

<section id="posts">
  <div class="wrapper">

    <div id="resources-filter" class="center">
      <div class="categories">
        <?php echo facetwp_display('facet', 'categories_list'); ?>
      </div>
    </div>

    <div id="resources-list" class="facetwp-template has-gated flex wrap">

      <?php
      if (have_posts()) :
        while (have_posts()) :
          the_post();

          // image
          if (get_the_post_thumbnail_url() != '') {
            $image = '<div class="image"><img src="' . get_the_post_thumbnail_url() . '" /></div>';
            $image_class = 'post-image';
          } else {
            $image = $category_image;
            $image_class = 'category-image';
          }

          // title
          $title = wp_trim_words(get_the_title(), 7);

          // category
          $categories = get_the_category();
          if (!empty($categories)) {
            $category_name = $categories[0]->name;
            $category_slug = $categories[0]->slug;
          }

          // excerpt
          $excerpt = wp_trim_words(get_the_content(), 20);
          if (get_the_excerpt()) {
            $excerpt = wp_trim_words(get_the_excerpt(), 18);
          }

          // link
          $download = get_field('download_file', get_the_ID()) ?? '';
          if ($download) {
            $link = 'javascript:;';
          } else {
            $link = get_the_permalink();
          }
      ?>

          <div class="post">
            <a href="<?php echo $link ?>" class="block-link has-buttons" <?php
                                                                          if ($download) {
                                                                            echo 'data-file="' . $download . '"';
                                                                          } ?>>
              <div class="image">
                <?php echo $image ?>
              </div>
              <div class="text">
                <h3 class="category eyebrow-2 teal-text"><?php echo $category_name ?></h3>
                <h3 class="title h3b blue-text"><?php echo $title ?></h3>
                <p class="p2b"><?php echo $excerpt ?></p>
                <span class="button clear arrow more">Read More</span>
              </div>
            </a>
          </div>

      <?php
        endwhile;
      endif;
      ?>

    </div>

    <div id="resources-pager" class="pager">
      <?php echo facetwp_display('facet', 'pager_resources'); ?>
    </div>
  </div>
</section>

<section id="featured-events" class="mobile">
  <div class="wrapper">

    <h2>Events</h2>

    <?php
    $args = array(
      'post_type' => 'events',
      'order' => 'ASC',
      'posts_per_page' => 4
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

        // id
        $id = get_the_id();

        // image
        if (get_the_post_thumbnail_url() != '') {
          $image = '<div class="image"><img src="' . get_the_post_thumbnail_url() . '" /></div>';
          $image_class = 'post-image';
        } else {
          $image = $category_image;
          $image_class = 'category-image';
        }

        // link
        $link = get_the_permalink();
    ?>

        <div class="post">
          <a href="<?php echo $link ?>" class="block-link has-buttons">
            <div class="image">
              <?php echo $image ?>
            </div>
            <div class="text">
              <h3 class="h3b blue-text"><?php the_title(); ?></h3>
            </div>
          </a>
        </div>

    <?php
      endwhile;
    endif;
    ?>

  </div>
</section>

<?php get_footer() ?>

<?php
// Define your parent page ID
$parent_id = 8;

// Set up the query arguments
$args = array(
    'post_type'      => 'page',
    'posts_per_page' => -1,
    'post_parent'    => $parent_id,
    'orderby'        => 'menu_order', // Optional: order by the page order
    'order'          => 'ASC'
);

// Query the child pages
$child_pages = new WP_Query($args);

// Start the loop
if ($child_pages->have_posts()) : ?>
    <ul class="child-pages-list">
        <?php while ($child_pages->have_posts()) : $child_pages->the_post(); ?>
            <li>
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                <?php the_excerpt(); // Optional: Display excerpt ?>
            </li>
        <?php endwhile; ?>
    </ul>
    <?php wp_reset_postdata(); // Always reset after a custom query ?>
<?php else : ?>
    <p>No child pages found.</p>
<?php endif; ?>
