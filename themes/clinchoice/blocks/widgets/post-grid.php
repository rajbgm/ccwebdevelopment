<?php

/**
 * Post Grid Template
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
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

<section id="resources-list">
  <div class="wrapper">

   <h2>  <?php echo get_field('widget_title', get_the_ID()); ?></h2>

    <div class="flex max-3 min-1 wrap">
      <?php
      
      $args = array(
        'posts_per_page' => 3,
        'paged' => $paged
      );
      $query = new WP_Query($args);
      if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

          // image
          if (get_the_post_thumbnail_url() != '') {
            $image = '<div class="image"><img src="' . get_the_post_thumbnail_url() . '" /></div>';
          } else {
            $image = '';
          }

          // category
          $categories = get_the_category();
          if (!empty($categories)) {
            $category = $categories[0]->name;
          }

          // excerpt
          $excerpt = wp_trim_words(get_the_content(), 20);
          if (get_the_excerpt()) {
            $excerpt = wp_trim_words(get_the_excerpt(), 18);
          }

          
          $link = get_the_permalink();
          $target = '_self';
         

      ?>
          <div class="item">
            <div class="item-deatils">
            <a href="<?php echo $link ?>" class="block-link has-buttons" target="<?php echo $target ?>">
              <?php echo $image ?>
              <div class="text">
                <p class="category"><?php echo $category ?> //</p>
                <h3><?php the_title(); ?></h3>
                <p><?php echo $excerpt; ?></p>
                <span class="button clear">Learn More</span>
              </div>
            </a>
        </div>
          </div>

      <?php
        endwhile;
      endif;
      ?>
    </div>

    <div id="resources-pagination">

     
    </div>

  </div>
</section>
