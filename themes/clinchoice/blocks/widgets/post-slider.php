<?php

/**
 * Featured Insights Slider Template
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
?>

<div id="resources-swiper" class="carousel has-gated">
  <div class="swiper resources-list">
    <div class="swiper-wrapper">

      <?php
      $args = array(
        'posts_per_page' => 6
      );
      $query = new WP_Query($args);
      if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

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

          <a href="<?php echo $link ?>" class="swiper-slide card card-v block-link has-buttons"<?php
                                                                          if ($download) {
                                                                            echo 'data-file="' . $download . '"';
                                                                          } ?>>
            <div class="image">
              <?php echo $image ?>
            </div>
            <div class="content">
              <h3 class="category eyebrow-2"><?php echo $category_name ?></h3>
              <h3 class="title h3b"><?php echo $title ?></h3>
              <p class="excerpt p2b"><?php echo $excerpt ?></p>
              <span class="button clear light arrow">Read More</span>
            </div>
          </a>

      <?php
        endwhile;
      endif;
      ?>

    </div>
    <div class="swiper-pagination"></div>
  </div>

  <div class="controls">
    <div class="swiper-button-next-posts"></div>
    <div class="swiper-button-prev-posts"></div>
  </div>
</div>