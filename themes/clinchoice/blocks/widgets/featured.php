<section id="featured">
  <div class="wrapper">

    <?php
    $args = array(
      'cat' => $cat_id,
      'posts_per_page' => 1
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

        // link
        $link = get_the_permalink();
        $target = '_self';
        $redirect = get_field_object('redirect');
        if ($redirect) {
          $source = $redirect['value'];
          if ($source == 'file') {
            $link = get_field('redirect_file');
            $target = '_blank';
          }
          if ($source == 'url') {
            $link = get_field('redirect_url');
            $target = '_blank';
          }
          if ($source == 'video') {
            $link = '#video';
            $target = '_self';
          }
        }
        $excerpt = wp_trim_words(get_the_content(), 20);
        if (get_the_excerpt()) {
          $excerpt = get_the_excerpt();
        }
    ?>

        <div class="flex">
          <div class="image col-1">
            <a href="<?php echo $link ?>" class="block-link" target="<?php echo $target; ?>" style="background-image:url(<?php echo get_the_post_thumbnail_url() ?>)"><span>Spotlight Newest Content</span></a>
          </div>
          <div class="text col-2">
            <p class="category"><?php echo $cat_name; ?> //</p>
            <h3><?php the_title(); ?></h3>
            <p><?php echo wp_trim_words(get_the_content(), 20); ?></p>
            <a href="<?php echo $link ?>" target="<?php echo $target; ?>" class="button clear">Learn More</a>
          </div>
        </div>

    <?php
      endwhile;
    endif;
    ?>

  </div>
</section>