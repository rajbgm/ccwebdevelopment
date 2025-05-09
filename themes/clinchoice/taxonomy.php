<?php get_header() ?>

<?php
$cat_id = get_queried_object_id();

// pagination
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
?>

<section id="resources-list">
  <div class="wrapper">

    <div id="resources-filter">
      <?php
      $categories = get_categories([
        'taxonomy'     => 'xxx',
        'type'         => 'yyy',
        'orderby'      => 'name',
        'order'        => 'ASC'
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
        'post_type' => 'yyy',
        'tax_query' => array(
          array(
            'taxonomy' => 'xxx',
            'field' => 'term_id',
            'terms' => $cat_id,
          )
        ),
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
          $terms = get_the_terms($post->ID, 'stories');
          if ($terms && !is_wp_error($terms)) :
            $tslugs_arr = array();
            foreach ($terms as $term) {
              $tslugs_arr[] = $term->slug;
            }
            $category = join(" ", $tslugs_arr);
          endif;

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
                <p><?php echo $trimmed_content = wp_trim_words(get_the_content(), 20); ?></p>
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

      <div class="pageinate">
        <?php
        global $wp_query;
        echo paginate_links(array(
          'base'         => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
          'total'        => $wp_query->max_num_pages,
          'current'      => max(1, get_query_var('paged')),
          'format'       => '?paged=%#%',
          'show_all'     => false,
          'type'         => 'plain',
          'end_size'     => 2,
          'mid_size'     => 2,
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