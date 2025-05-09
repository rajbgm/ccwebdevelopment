<?php
$featured_event = get_field('featured_event');
$featured_event_id = $featured_event->ID;
?>

<div id="spotlight">
  <div class="wrapper">

    <?php
    $args = array(
      'post_type' => 'events',
      'p' => $featured_event_id,
      'posts_per_page' => 1
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

        // date
        $date = get_field('display_date', $id);

        // city
        $city = get_field('city', $id);

        // country
        $country = get_field('country', $id);

        // country
        $link = get_the_permalink();
    ?>

        <div class="flex">
          <div class="image">
            <a href="<?php echo $link ?>" class="block-link <?php echo $image_class ?>"><?php echo $image ?></a>
          </div>
          <div class="text">
            <div class="content">
              <h3><?php the_title(); ?></h3>
              <h3 class="eyebrow-3">Date</h3>
              <p><?php echo $date ?></p>
              <h3 class="eyebrow-3">Location</h3>
              <p><strong><?php echo $city ?></strong><br><?php echo $country ?></p>
              <a href="<?php echo $link ?>" class="button clear arrow more">Find Out More</a>
            </div>
          </div>
        </div>

    <?php
      endwhile;
    endif;
    ?>

  </div>
</div>

<div id="resources-list">
  <div class="wrapper">


    <div class="flex wrap">

      <?php
      $today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
      $args = array(
        'post_type' => 'events',
        'posts_per_page' => 9,
        'post__not_in' => array($featured_event_id),
        'meta_key'          => 'starting_date',
        'orderby'           => 'meta_value',
        'order'             => 'ASC',
        'meta_query' => array(
          array(
            'key' => 'ending_date',
            'value' => $today,
            'type' => 'DATE',
            'compare' => '>',
          )
        )
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
            $image = '<div class="image"><img src="/wp-content/uploads/Clinchoice_Resource_Images_news.jpg" /></div>';
            $image_class = 'category-image';
          }

          // date
          $date = get_field('display_date', $id);

          // city
          $city = get_field('city', $id);

          // country
          $country = get_field('country', $id);

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
                <h3 class="eyebrow-3">Date</h3>
                <p><?php echo $date ?></p>
                <h3 class="eyebrow-3">Location</h3>
                <p><strong><?php echo $city ?></strong><br><?php echo $country ?></p>
                <span class="button clear arrow more">Find Out More</span>
              </div>
            </a>
          </div>

      <?php
        endwhile;
      endif;
      ?>

    </div>
  </div>
</div>