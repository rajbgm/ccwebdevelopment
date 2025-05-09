<div class="wrapper">
  <div class="flex">
    <?php
    $args = array(
      'post_type' => 'leadership',
      'posts_per_page' => -1
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

        // slug
        $slug = get_post_field('post_name', get_post());

        // image
        $post_thumbnail_url = get_the_post_thumbnail_url() ?? false;
        $image = $post_thumbnail_url ? $post_thumbnail_url : '/wp-content/uploads/fpo.png';

        // job title
        $job_title = get_field('job_title', get_the_ID()) ?? false;
    ?>
        <a href="#" class="item block-link modal-link">
          <div class="image">
            <img src="<?php echo $image ?>" alt="<?php the_title() ?>" />
          </div>
          <div class="text">
            <p class="name orange-text"><?php the_title() ?></p>
            <p class="role"><?php echo $job_title ?></p>
          </div>
          <div class="bio">
            <?php the_content() ?>
          </div>
        </a>
    <?php endwhile;
    endif; ?>
  </div>
</div>

<div class="modal">
  <div class="modal-wrapper">
    <div class="close"></div>
    <div class="content">
      <div class="flex">
        <div class="image"><img /></div>
        <div class="text">
          <h2></h2>
          <h3 class="h3a"></h3>
          <div class="details"></div>
        </div>
      </div>
    </div>
  </div>
</div>