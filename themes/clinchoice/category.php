<?php get_header() ?>

<?php
// category info
$category = get_category(get_query_var('cat'));
$cat_id = $category->cat_ID;
$cat_name = $category->cat_name;
$cat_desc = $category->category_description;

// pagination
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
?>

<section id="resources-hero">
  <div class="wrapper">
    <h1><?php echo get_field('resources_headline', 6) ?></h1>
    <p><?php echo get_field('resources_text', 6) ?></p>
  </div>
</section>

<section id="spotlight">
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


<section id="resources-list">
  <div class="wrapper">

    <div id="resources-filter">
      <?php
      $categories = get_categories([
        'taxonomy'     => 'category',
        'orderby'      => 'name',
        'order'        => 'ASC',
        'hide_empty'   => 1
      ]);
      if ($categories) {
        foreach ($categories as $cat_link) {
      ?>
          <a href="<?php echo get_category_link($cat_link->term_id); ?>" <?php if ($cat_id == $cat_link->term_id) { ?> class="current" <?php } ?>>
            <?php echo $cat_link->name; ?>
          </a>
      <?php
        }
      }
      ?>
    </div>

    <div class="flex max-3 min-1 wrap">
      <?php
      $args = array(
        'cat' => $cat_id,
        'posts_per_page' => 9,
        'paged' => $paged,
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

          // link
          $redirect = get_field_object('redirect');
          $video = '';
          $link = get_the_permalink();
          $target = '_self';
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
              $video = get_field('redirect_video');
            }
          }

      ?>
          <div class="item">
            <a href="<?php echo $link ?>" class="block-link has-buttons" target="<?php echo $target ?>" <?php if ($video != '') {
                                                                                                          echo 'data-src="' . $video . '"';
                                                                                                        } ?>>
              <?php echo $image ?>
              <div class="text">
                <p class="category"><?php echo $category ?> //</p>
                <h3><?php the_title(); ?></h3>
                <p><?php echo $excerpt; ?></p>
                <span class="button clear">Learn More</span>
              </div>
            </a>
          </div>

      <?php
        endwhile;
      endif;
      ?>
    </div>

    <div id="resources-pagination">

      <div class="paginate">
        <?php
        global $wp_query;
        echo paginate_links(array(
          'base'         => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
          'total'        => $wp_query->max_num_pages,
          'current'      => max(1, get_query_var('paged')),
          'format'       => '?paged=%#%',
          'show_all'     => true,
          'type'         => 'plain',
          'end_size'     => 1,
          'mid_size'     => 1,
          'prev_next'    => true,
          'prev_text'    => sprintf('<', __('Newer Posts', 'text-domain')),
          'next_text'    => sprintf('>', __('Older Posts', 'text-domain')),
          'add_args'     => true,
          'add_fragment' => '',
        ));
        ?>
      </div>
    </div>

  </div>
</section>

<div class="modal">
  <div class="modal-wrapper">
    <div class="close"></div>
    <div class="content">
      <iframe id="video" width="800" height="450" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>
  </div>
</div>

<?php get_footer() ?>