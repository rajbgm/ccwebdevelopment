<div class="wrapper">
  <div class="swiper">
    <div class="swiper-wrapper">
      <?php
      if (have_rows('testimonials')) :
        while (have_rows('testimonials')) : the_row();
          $quote = get_sub_field('quote');
          $source = get_sub_field('source');
      ?>
          <div class="swiper-slide">
            <div class="content">
              <p class="quote"><?php echo $quote ?></p>
              <p class="source p1b"><?php echo $source ?></p>
            </div>
          </div>
      <?php
        endwhile;
      endif;
      ?>
    </div>
    <div class="swiper-pagination"></div>
  </div>
</div>