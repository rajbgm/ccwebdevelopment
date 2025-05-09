<div class="expertise-home">


    <div class="therapeutic-areas">
      <div class="overview">

  <?php
  $expertise1 = get_field('therapeutic-areas');
  if ($expertise1):
    $headine = $expertise1['headline'];
    $intro_text = $expertise1['intro_text'];
    $main_link = $expertise1['page_link'];
    $main_link_url = $main_link['url'] ?? null;
    $main_link_title = $main_link['title'] ?? null;
  endif;
  ?>
        <div class="content">
          <div class="headline">
            <h2><?php echo $headine; ?></h2>
          </div>
          <div class="text">
            <p><?php echo $intro_text; ?></p>
          </div>
          <div class="buttons">
            <div class="buttons"><a href="<?php echo $main_link_url ?>" target="" class="button"><span><?php echo $main_link_title ?></span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="11.078">
                  <defs>
                    <clipPath id="a">
                      <path d="M0 0h14v11.078H0z" data-name="Rectangle 70"></path>
                    </clipPath>
                  </defs>
                  <g clip-path="url(#a)" data-name="Group 63">
                    <path d="M13.721 4.86 9.182.321a.963.963 0 0 0-1.449 1.27.974.974 0 0 0 .087.089l2.894 2.9H.963a.964.964 0 1 0 0 1.927h9.75L7.821 9.4a.963.963 0 0 0 .567 1.679h.076a.963.963 0 0 0 .715-.319l4.541-4.541a.966.966 0 0 0 0-1.359" data-name="Path 135"></path>
                  </g>
                </svg></a></div>
          </div>
        </div>
      </div>
      <div class="icon-list">
        <div class="swiper mySwiper1">
          <div class="swiper-wrapper">
            <?php
            //$test = $expertise1['icon_list'];
            if (have_rows('therapeutic-areas')): while (have_rows('therapeutic-areas')) : the_row();
                if (have_rows('icon_list')): while (have_rows('icon_list')) : the_row();
                    $link = get_sub_field('page_link');
                    $link_url = $link['url'] ?? null;
                    $link_title = $link['title'] ?? null;
                    $image = get_sub_field('icon');
            ?>
                    <div class="swiper-slide">
                      <a href="<?php echo $link_url ?>">
                        <img src="<?php echo $image ?>" alt="" loading="lazy">
                        <p><?php echo $link_title ?></p>
                      </a>
                    </div>
                <?php endwhile;
                endif; ?>
            <?php endwhile;
            endif; ?>
          </div>
        </div>
        <div class="swiper-controls">
          <div class="swiper-button-next swiper-button-next1"></div>
          <div class="swiper-button-prev swiper-button-prev1"></div>
        </div>
      </div>
    </div>

  <div class="specialties">
    <div class="icon-list">
      <div class="swiper mySwiper2">
        <div class="swiper-wrapper">
        <?php
            //$test = $expertise1['icon_list'];
            if (have_rows('specialties')): while (have_rows('specialties')) : the_row();
                if (have_rows('icon_list')): while (have_rows('icon_list')) : the_row();
                    $link = get_sub_field('page_link');
                    $link_url = $link['url'] ?? null;
                    $link_title = $link['title'] ?? null;
                    $image = get_sub_field('icon');
            ?>
                    <div class="swiper-slide">
                      <a href="<?php echo $link_url ?>">
                        <img src="<?php echo $image ?>" alt="" loading="lazy">
                        <p><?php echo $link_title ?></p>
                      </a>
                    </div>
                <?php endwhile;
                endif; ?>
            <?php endwhile;
            endif; ?>
          </div>
      </div>
      <div class="swiper-controls">
        <div class="swiper-button-next swiper-button-next2"></div>
        <div class="swiper-button-prev swiper-button-prev2"></div>
      </div>
    </div>
    <div class="overview">
    <?php
  $expertise2 = get_field('specialties');
  if ($expertise2):
    $headine = $expertise2['headline'];
    $intro_text = $expertise2['intro_text'];
    $main_link = $expertise2['page_link'];
    $main_link_url = $main_link['url'] ?? null;
    $main_link_title = $main_link['title'] ?? null;
  endif; ?>
      <div class="content">
        <div class="headline">
          <h2 class="bluegreen-text"><?php echo $headine ?></h2>
        </div>
        <div class="text">
          <p><?php echo $intro_text ?></p>
        </div>
        <div class="buttons">
          <div class="buttons">
            <a href="<?php echo $main_link_url ?>" target="" class="bluegreen teal-hover blue-arrow-hover button"><span><?php echo $main_link_title ?></span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="11.078">
                <defs>
                  <clipPath id="a">
                    <path d="M0 0h14v11.078H0z" data-name="Rectangle 70"></path>
                  </clipPath>
                </defs>
                <g clip-path="url(#a)" data-name="Group 63">
                  <path d="M13.721 4.86 9.182.321a.963.963 0 0 0-1.449 1.27.974.974 0 0 0 .087.089l2.894 2.9H.963a.964.964 0 1 0 0 1.927h9.75L7.821 9.4a.963.963 0 0 0 .567 1.679h.076a.963.963 0 0 0 .715-.319l4.541-4.541a.966.966 0 0 0 0-1.359" data-name="Path 135"></path>
                </g>
              </svg></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>

</script>