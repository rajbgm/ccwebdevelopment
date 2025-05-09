<div class="wrapper">

  <div class="map">
    <div class="regions">
      <?php get_template_part('assets/images/map.svg') ?>
    </div>
    <div class="pins">
      <?php get_template_part('assets/images/map/pin_na.svg') ?>
      <?php get_template_part('assets/images/map/pin_eu.svg') ?>
      <?php get_template_part('assets/images/map/pin_ap.svg') ?>
    </div>
  </div>

  <div id="locations" class="locations">
    <div class="tabs">
      <div class="tab active" data-target="na">
        <h3>North America <?php get_template_part('assets/images/arrows/down-baselight.svg') ?></h3>
      </div>
      <div class="tab" data-target="eu">
        <h3>Europe <?php get_template_part('assets/images/arrows/down-baselight.svg') ?></h3>
      </div>
      <div class="tab" data-target="ap">
        <h3>Asia-Pacific <?php get_template_part('assets/images/arrows/down-baselight.svg') ?></h3>
      </div>
    </div>
    <div class="details">
      <div class="content">

        <aside>

          <?php
          $post_type = 'location';
          $taxonomies = get_object_taxonomies((object) array('post_type' => $post_type));
          $i = 0;

          foreach ($taxonomies as $taxonomy) :

            $terms = get_terms($taxonomy);

            foreach ($terms as $term) : $i++;

          ?>

              <div id="<?php echo $term->slug ?>" <?php if ($i == 1) {
                                                    echo 'class="active"';
                                                  } ?>>

                <ul>
                  <?php

                  $args = array(
                    'posts_per_page' => -1,
                    'order' => 'ASC',
                    'orderby' => 'title',
                    'taxonomy' => $taxonomy,
                    'term' => $term->slug
                  );
                  $the_query = new WP_Query($args);
                  $c = 0;

                  if ($the_query->have_posts()) :
                    while ($the_query->have_posts()) : $the_query->the_post();
                      $c++;
                      $id = get_the_id();
                      $slug = str_replace(array('-2', '-3'), array('', ''), get_post_field('post_name', get_post()));
                  ?>


                      <li>
                        <h3 data-target="<?php echo $slug ?>" class="title h3b<?php if ($c == 1) {
                                                                                echo ' active';
                                                                              } ?>"><?php the_title() ?></h3>
                        <div class="<?php echo $slug ?> contact">
                          <div>
                            <?php
                            $l = 0;
                            if (have_rows('multi', $id)) :
                              while (have_rows('multi', $id)) : the_row();
                                $l++;
                                $address = get_sub_field('address_multi') ?? null;
                                $phone = get_sub_field('phone_multi') ?? null;
                                $email = get_sub_field('email_multi') ?? null;

                                if ($id == 2398) {
                                  $address = 'ClinChoice International Headquarters<br>';
                                  if (have_rows('multi', 547)) :
                                    while (have_rows('multi', 547)) : the_row();
                                      $phone = get_sub_field('phone_multi');
                                      $phone_clean = preg_replace('/[^0-9]/', '', $phone);
                                      $email = get_sub_field('email_multi');
                                      $address .= str_replace('USA', '', get_sub_field('address_multi'));
                                    endwhile;
                                  endif;
                                }
                            ?>
                                <div class="location-<?php echo $l ?>">
                                  <h3 class="eyebrow-1"><?php the_title() ?></h3>
                                  <?php
                                  if ($address) {
                                    echo '<p class="p2b">' . str_replace('USA', '', $address) . '</p>';
                                  }
                                  if ($phone) {
                                    echo '<h3 class="phone eyebrow-2">Phone</h3><p class="phone">' . $phone . '</p>';
                                  }
                                  if ($email) {
                                    echo '<h3 class="email eyebrow-2">Email</h3><p>' . $email . '</p>';
                                  }
                                  ?>
                                </div>

                            <?php


                              endwhile;

                            else :
                            endif;
                            ?>
                          </div>
                        </div>
                      </li>

                  <?php
                    endwhile;
                  endif;
                  ?>
                </ul>
              </div>
          <?php

            endforeach;

          endforeach;

          // Reset Post Data
          wp_reset_postdata();
          ?>
        </aside>

        <article></article>

      </div>
    </div>
  </div>

  <?php get_template_part('blocks/modules/disclaimer') ?>

</div>