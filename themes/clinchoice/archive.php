<?php get_header(); ?>


<?php
$post_id = 158;
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
      'posts_per_page' => 1
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

        $categories = get_categories();
        foreach ($categories as $category) {
          $category_name = $category->name;
          $category_image = $category->description;
        }

        // link
        $link = get_the_permalink();

        // image
        if (get_the_post_thumbnail_url() != '') {
          $image = '<div class="image"><img src="' . get_the_post_thumbnail_url() . '" /></div>';
          $image_class = 'post-image';
        } else {
          $image = $category_image;
          $image_class = 'category-image';
        }
    ?>

        <div class="flex">
          <div class="image">
            <a href="<?php echo $link ?>" class="block-link <?php echo $image_class ?>"><?php echo $image ?></a>
          </div>
          <div class="text">
            <div class="content">
              <h3 class="category eyebrow-1"><?php echo $category_name; ?></h3>
              <h3><?php the_title(); ?></h3>
              <p><?php echo wp_trim_words(get_the_content(), 20); ?></p>
              <a href="<?php echo $link ?>" class="button clear arrow more">Read More</a>
            </div>
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

    <div id="resources-filter" class="center">
      <div class="categories">
        <a href="/insights" class="button bluegreen teal-hover no-arrow current">All Resources</a>
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
            <a href="<?php echo get_category_link($cat_link->term_id); ?>" class="button bluegreen teal-hover no-arrow">
              <?php echo $cat_link->name; ?>
            </a>
        <?php
          }
        }
        ?>
      </div>
    </div>

    <div class="flex wrap">
      <?php
      $args = array(
        'posts_per_page' => 9,
        'paged' => $paged
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

          // category
          $categories = get_the_category();
          if (!empty($categories)) {
            $category_name = $categories[0]->name;
            $category_slug = $categories[0]->slug;
          }

          // title
          $title = wp_trim_words(get_the_title(), 7);

          // excerpt
          $excerpt = wp_trim_words(get_the_content(), 20);
          if (get_the_excerpt()) {
            $excerpt = wp_trim_words(get_the_excerpt(), 18);
          }

          // link
          $link = get_the_permalink();
          $target = '_self';


      ?>
          <div class="post">
            <a href="<?php echo $link ?>" class="block-link has-buttons" target="<?php echo $target ?>">
              <div class="image">
                <?php echo $image ?>
              </div>
              <div class="text">
                <h3 class="category eyebrow-2 teal-text"><?php echo $category_name ?><h3>
                    <h3 class="title h3b"><?php echo $title ?></h3>
                    <p class="p2b"><?php echo $excerpt; ?></p>
                    <span class="button clear arrow more">Read More</span>
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
          'prev_text'    => sprintf('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="11.078" id="svg-arrow-white"><defs><clipPath id="arrow-white"><path d="M0 0h14v11.078H0z" data-name="Rectangle 70"/></clipPath></defs><g clip-path="url(#arrow-white)" data-name="Group 63"><path fill="#7A97AB" d="M13.721 4.86 9.182.321a.963.963 0 0 0-1.449 1.27.974.974 0 0 0 .087.089l2.894 2.9H.963a.964.964 0 1 0 0 1.927h9.75L7.821 9.4a.963.963 0 0 0 .567 1.679h.076a.963.963 0 0 0 .715-.319l4.541-4.541a.966.966 0 0 0 0-1.359" data-name="Path 135"/></g></svg>', __('Newer Posts', 'text-domain')),
          'next_text'    => sprintf('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="11.078" id="svg-arrow-white"><defs><clipPath id="arrow-white"><path d="M0 0h14v11.078H0z" data-name="Rectangle 70"/></clipPath></defs><g clip-path="url(#arrow-white)" data-name="Group 63"><path fill="#7A97AB" d="M13.721 4.86 9.182.321a.963.963 0 0 0-1.449 1.27.974.974 0 0 0 .087.089l2.894 2.9H.963a.964.964 0 1 0 0 1.927h9.75L7.821 9.4a.963.963 0 0 0 .567 1.679h.076a.963.963 0 0 0 .715-.319l4.541-4.541a.966.966 0 0 0 0-1.359" data-name="Path 135"/></g></svg>', __('Older Posts', 'text-domain')),
          'add_args'     => true,
          'add_fragment' => '',
        ));
        ?>
      </div>
    </div>

  </div>
</section>

<?php get_footer() ?>