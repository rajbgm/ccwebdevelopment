<section id="slider" class="banner slider">

<?php if (have_rows('slides')): ?>
    <div class="swiper mySwiper fullwidth">
        <div class="swiper-wrapper">
            <?php while (have_rows('slides')): the_row();
                $image = get_sub_field('image');
                $title = get_sub_field('title');
                $description = get_sub_field('description');
            ?>
            <div class="swiper-slide">
                <div style="background-image:url(<?= esc_url($image['url']); ?>); height:400px; display:flex; justify-content:center; align-items:center;">
                    <div class="wrapper">
                        <div class="headline">
                            <h3 class="text-orange"><?= esc_html($title); ?></h3>
                            <p class="text-white"><?= esc_html($description); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="swiper-pagination"></div>
        <!-- <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div> -->
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.mySwiper', {
        loop: true,
    //     autoplay: {
    //   delay: 3000, // 3 seconds between slides
    //   disableOnInteraction: false, // keep autoplay running after user interactions
    // },
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },
        //navigation: {
        //    nextEl: '.swiper-button-next',
        //    prevEl: '.swiper-button-prev'
      //  }
    });
});
</script>

<div class="wp-block-group clear-buttons is-layout-constrained wp-block-group-is-layout-constrained solution_home">
    <div class="wp-block-columns block-links is-layout-flex wp-container-core-columns-is-layout-1 wp-block-columns-is-layout-flex equal-columns flex-mobile">

    <?php
    $selected_post_type = get_field('solution_select');

    if ($selected_post_type && is_object($selected_post_type)) {
        $post_type_slug = $selected_post_type->name;

        $args = [
            'post_type' => $post_type_slug,
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) : 
            while ($query->have_posts()) : $query->the_post(); 
            
                $color_code = get_field('color_code', get_the_ID()); 
                $title = get_field('home_title', get_the_ID());
                $description = get_field('home_description', get_the_ID());
                $url = get_permalink();
            ?>
                <div class="wp-block-column is-layout-flow wp-block-column-is-layout-flow solution_color <?php echo esc_attr($color_code); ?>">
                    <a href="#" class="button home-link">
                        <p class="name"><?php the_title(); ?></p>
                    <p class="desription hide"><?php echo $description; ?></p>
                    <p class="slug hide"><?php echo $url; ?></p>
                    <p class="color_code hide"><?php echo $color_code; ?></p>
                </a>
                    
                </div>
            <?php endwhile;

            wp_reset_postdata(); 
        else : ?>
            <p>No published posts found for <?= esc_html($selected_post_type->label); ?>.</p>
        <?php endif;
    }
    ?>

    </div>
</div>

<div class="modal">
  <div class="modal-wrapper">
    <div class="close"></div>
    <div class="content">
      <div class="flex">        
        <div class="solution_text">
          <h3 class="h3a"></h3>
          <p></p>
          <a class="button read-more-v3 text-white" href="">Read More</a>          
        </div>
      </div>
    </div>
  </div>
</div>

</section>
