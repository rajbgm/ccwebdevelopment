<?php get_header() ?>

<?php
$post_id = 2336; // Replace with the actual Post ID
$post = get_post($post_id);

if ($post) {
  $post_content = apply_filters('the_content', $post->post_content);
  echo $post_content;
} else {
  echo 'Post not found';
}
?>

<?php
// pagination
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
?>

<section id="results" class="gradient-bg">
  <div class="wrapper">

    <div id="search-form">
      <form action="/" method="get">
        <input type="text" name="s" id="search" placeholder="Search" />
        <input type="submit" id="searchsubmit" value="Submit" />
      </form>
    </div>

    <div id="results-list">
      <h2 class="purple-text">Search Results for "<?php echo $s ?>"</h2>
      <?php
      $args = array(
        's' => $s,
        'post_type' => 'post',
        'posts_per_page' => 9,
        'paged' => $paged,
      );
      $query = new WP_Query($args);
      if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

          // link
          $link = get_the_permalink();

          // content
          $content = substr(strip_tags(get_the_content()), 0, 235);;

      ?>
          <div class="item">
            <a href="<?php echo $link ?>" class="block-link">
              <div class="text">
                <h3><?php the_title(); ?></h3>
                <p><?php echo $content; ?></p>
                <span class="button clear purple-text purple-arrow">More <svg xmlns="http://www.w3.org/2000/svg" width="14" height="11.078">
                    <defs>
                      <clipPath id="a">
                        <path d="M0 0h14v11.078H0z" data-name="Rectangle 70" />
                      </clipPath>
                    </defs>
                    <g clip-path="url(#a)" data-name="Group 63">
                      <path d="M13.721 4.86 9.182.321a.963.963 0 0 0-1.449 1.27.974.974 0 0 0 .087.089l2.894 2.9H.963a.964.964 0 1 0 0 1.927h9.75L7.821 9.4a.963.963 0 0 0 .567 1.679h.076a.963.963 0 0 0 .715-.319l4.541-4.541a.966.966 0 0 0 0-1.359" data-name="Path 135" />
                    </g>
                  </svg></span>
              </div>
            </a>
          </div>

      <?php
        endwhile;
      endif;
      ?>
    </div>

    <div id="search-pager" class="pager">
      <?php
      global $query;
      echo paginate_links(array(
        'base'         => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'total'        => $query->max_num_pages,
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
</section>

<?php get_footer() ?>