<?php get_header(); ?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <?php
    $category = get_the_category();
    $cat_name = $category[0]->cat_name;

    // temporary, will change before launch
    if ($cat_name == 'Case Study') {
      $cat_name = 'Case Studie';
    }
    if ($cat_name == 'News') {
      $cat_name = 'New';
    }

    // author
    $author = get_the_author_meta('display_name', get_the_author_meta('ID')) ?: 'ClinChoice';
    ?>

    <section id="banner" class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained">
      <div class="wp-block-group is-layout-constrained wp-block-group-is-layout-constrained">
        <div class="subhead">
          <h3>Insights</h3>
        </div>
        <div class="headline">
			<?php
			if ($cat_name == 'Latest News') {
      			$cat_name = 'New';
    		}
			?>
          <h2 class="h1">Dive Into Our Latest <?php echo $cat_name; ?>s</h2>
        </div>
      </div>
      <div class="bg" style="--bg-image-desktop:url(/wp-content/uploads/ins_gfx1.jpg);--bg-image-mobile:url(/wp-content/uploads/ins_gfx1.jpg);"></div>
    </section>

    <section id="main-content" class="gradient-bg">
      <div class="wrapper">
        <div class="flex">
          <article>
            <div class="image">
              <img src="<?php echo get_the_post_thumbnail_url() ?>" />
            </div>
            <div class="entry-title">
              <h1 class="h2"><?php the_title() ?></h1>
            </div>
            <div class="entry-details">
              <div class="flex">
                <div class="author-date">
                  <p>
                    <strong class="bluegreen-text">By <?php echo $author ?></strong><br />
                    <?php echo the_date() ?>
                  </p>
                </div>
                <div class="share">
                  <h3 class="eyebrow-2 baselight-text">SHARE</h3>
                  <div class="st-custom-button twitter" data-network="twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="23.291" height="23.291">
                      <defs>
                        <clipPath id="as1">
                          <path fill="#0083ad" d="M0 0h23.291v23.291H0z" data-name="Rectangle 3697" />
                        </clipPath>
                      </defs>
                      <path fill="#0083ad" d="m6.557 5.686 8.353 11.948h1.814L8.371 5.686Z" data-name="Path 1113" />
                      <g data-name="Group 3062">
                        <g clip-path="url(#as1)" data-name="Group 3061">
                          <path fill="#0083ad" d="M11.646 0a11.646 11.646 0 1 0 11.645 11.646A11.646 11.646 0 0 0 11.646 0m2.708 18.483L10.7 13.169l-4.569 5.314H4.95l5.229-6.077L4.95 4.8h3.987l3.457 5.032 4.33-5.032h1.181l-4.985 5.792 5.422 7.891Z" data-name="Path 1114" />
                        </g>
                      </g>
                    </svg>

                  </div>
                  <div class="st-custom-button linkedin" data-network="linkedin">
                    <svg xmlns="http://www.w3.org/2000/svg" width="23.291" height="23.291">
                      <defs>
                        <clipPath id="as2">
                          <path fill="#0083ad" d="M0 0h23.291v23.291H0z" data-name="Rectangle 3698" />
                        </clipPath>
                      </defs>
                      <g clip-path="url(#as2)" data-name="Group 3064">
                        <path fill="#0083ad" d="M11.646 0a11.646 11.646 0 1 0 11.645 11.646A11.647 11.647 0 0 0 11.646 0M8.262 17.6H5.425V9.072h2.837ZM6.844 7.9h-.019a1.478 1.478 0 1 1 .037-2.948A1.479 1.479 0 1 1 6.843 7.9m11.645 9.7h-2.835v-4.56c0-1.147-.411-1.93-1.437-1.93a1.553 1.553 0 0 0-1.455 1.037 1.937 1.937 0 0 0-.093.692V17.6H9.831s.037-7.732 0-8.533h2.836v1.213a2.816 2.816 0 0 1 2.556-1.408c1.866 0 3.265 1.22 3.265 3.841Z" data-name="Path 1115" />
                      </g>
                    </svg>
                  </div>
                  <div class="st-custom-button facebook" data-network="facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" width="23.375" height="23.291">
                      <defs>
                        <clipPath id="as3">
                          <path fill="#0083ad" d="M0 0h23.375v23.291H0z" data-name="Rectangle 3696" />
                        </clipPath>
                      </defs>
                      <g clip-path="url(#as3)" data-name="Group 3059">
                        <path fill="#0083ad" d="M23.375 11.687a11.661 11.661 0 0 1-10.268 11.6v-9.012h2.755l.417-3.172H13.19v-1.92c0-.918.251-1.5 1.586-1.5h1.669V4.758a23.47 23.47 0 0 0-2.421-.083 3.837 3.837 0 0 0-4.09 4.174v2.337H7.179v3.172h2.755v8.933a11.71 11.71 0 1 1 13.441-11.6Z" data-name="Path 1112" />
                      </g>
                    </svg>
                  </div>
                </div>
              </div>
            </div>
            <div class="entry-content">
              <?php the_content() ?>
              <button class="arrow reverse" onclick="history.back()">Back</button>
            </div>
          </article>
          <aside>
            <div class="newsletter">
              <?php gravity_form(5, true, false, false, '', true); ?>
            </div>
            <div class="insights">
              <h3 class="h3a">Insights</h3>
              <a href="/insights" class="button clear arrow">Resources from ClinChoice</a>
            </div>
            <div class="recent">
              <h3 class="h3a">Recent Posts</h3>
              <?php
              $args = array(
                'posts_per_page' => 4,
                'paged' => $paged
              );
              $query = new WP_Query($args);
              if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();
              ?>
                  <a href="<?php the_permalink() ?>" class="button clear arrow"><?php the_title() ?></a>
              <?php
                endwhile;
              endif;
              ?>
            </div>
          </aside>
        </div>
      </div>
    </section>

<?php endwhile;
endif; ?>

<?php get_template_part('blocks/widgets/cta') ?>

<?php get_footer(); ?>