<?php
$env = getenv('HTTP_HOST');

global $assets;
global $images;

if (get_field('redirect')) {
  $redirect_url = get_field('redirect_url');
  header('Location: ' . $redirect_url);
}

$page = $post->post_name;
if (is_home()) {
  $page = 'insights';
} else if (is_single()) {
  $page = 'post';
}

$current = get_post($post->ID);
$parent = $current->post_parent ? get_post($current->post_parent) : '';
$grandparent = $parent ? ($parent->post_parent ? get_post($parent->post_parent) : '') : '';

$banner_height = 0;
if (get_field('banner_height', get_the_id())) {
  $banner_height = get_field('banner_height', get_the_id());
};
$page_style = '';
if (get_field('style', get_the_id()) > 0) {
  $page_style = ' plain-text';
};
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title><?php wp_title() ?>
  </title>
	
	<!-- Loading the following 3 scripts in this specific order is necessary for their proper functionality. -->
	
	<!-- 1. CookieYes with Consent Mode -->
	<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() {
        dataLayer.push(arguments);
    }
    gtag("consent", "default", {
        ad_storage: "denied",
        ad_user_data: "denied", 
        ad_personalization: "denied",
        analytics_storage: "denied",
        functionality_storage: "denied",
        personalization_storage: "denied",
        security_storage: "granted",
        wait_for_update: 2000,
    });
    gtag("set", "ads_data_redaction", true);
    gtag("set", "url_passthrough", true);
	</script>

  <!-- 2. Google Tag Manager -->
  <script>
    (function(w, d, s, l, i) {
      w[l] = w[l] || [];
      w[l].push({
        'gtm.start': new Date().getTime(),
        event: 'gtm.js'
      });
      var f = d.getElementsByTagName(s)[0],
        j = d.createElement(s),
        dl = l != 'dataLayer' ? '&l=' + l : '';
      j.async = true;
      j.src =
        'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
      f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'GTM-K3TWN7P');
  </script>

  <?php if ($env == "clinchoice.com") { ?>
    <!-- 3. CookieYes banner -->
    <script id="cookieyes" type="text/javascript" src="https://cdn-cookieyes.com/client_data/793778232a9611b9c494337d/script.js"></script>
  <?php } ?>

  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="apple-touch-icon" href="assets/img/icons/apple-touch-icon.png">
  <link rel="stylesheet" href="<?php echo get_template_directory_uri() ?>/assets/styles/plugins.css" />
  <?php wp_head(); ?>
<!--   <script src="https://unpkg.com/counterup2@2.0.2/dist/index.js"> </script> -->
  <script src="<?php echo get_template_directory_uri() ?>/assets/scripts/libraries/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

  <!-- ShareThis -->
  <script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=663469c540fad4001977746d&product=sop' async='async'>
  </script>

  <?php if ($env == "clinchoicedev.wpengine.com") { ?>
    <!-- Bugherd (design) -->
    <script type="text/javascript" src="https://www.bugherd.com/sidebarv2.js?apikey=okfemt0e3auykqidwpfura" async="true"></script>
  <?php } ?>

  <!-- favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="manifest" href="/site.webmanifest">
  <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#02bbb4">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#484468">

</head>

<body id="<?php echo $page; ?>" <?php body_class(); ?>>

  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-K3TWN7P" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>

  <header>
    <nav id="primary">
      <div class="wrapper">
        <div class="flex flex-mobile">
          <div class="logo">
            <a href="<?php echo home_url() ?>">
              <img src="<?php echo home_url() ?>/wp-content/uploads/clinchoice_logo.svg">
            </a>
          </div>
          <div class="mobile-nav mobile-open">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" width="60" height="60" viewBox="0 0 60 60">
              <defs>
                <filter id="Ellipse_15" x="0" y="0" width="60" height="60" filterUnits="userSpaceOnUse">
                  <feOffset dy="3" input="SourceAlpha" />
                  <feGaussianBlur stdDeviation="3" result="blur" />
                  <feFlood flood-opacity="0.161" />
                  <feComposite operator="in" in2="blur" />
                  <feComposite in="SourceGraphic" />
                </filter>
              </defs>
              <g id="Group_3097" data-name="Group 3097" transform="translate(-319.261 -17.261)">
                <g transform="matrix(1, 0, 0, 1, 319.26, 17.26)" filter="url(#Ellipse_15)">
                  <circle id="Ellipse_15-2" data-name="Ellipse 15" cx="21" cy="21" r="21" transform="translate(9 6)" fill="#00bbb4" />
                </g>
                <g id="Group_268" data-name="Group 268" transform="translate(337.917 37.704)">
                  <line id="Line_1" data-name="Line 1" x2="22.689" transform="translate(0 0)" fill="none" stroke="#fff" stroke-linecap="round" stroke-width="2" />
                  <line id="Line_2" data-name="Line 2" x2="22.689" transform="translate(0 6.557)" fill="none" stroke="#fff" stroke-linecap="round" stroke-width="2" />
                  <line id="Line_3" data-name="Line 3" x2="22.689" transform="translate(0 13.114)" fill="none" stroke="#fff" stroke-linecap="round" stroke-width="2" />
                </g>
              </g>
            </svg>

          </div>
          <div id="menu">
            <ul class="utility-menu">
              <?php
              $args = array(
                'menu' => 'utility',
                'container' => false,
                'items_wrap' => '%3$s',
                'walker' => new custom_walker
              );
              wp_nav_menu($args);
              ?>
            </ul>
            <ul class="main-menu">
              <?php
              $args = array(
                'menu' => 'main',
                'container' => false,
                'depth' => 1,
                'items_wrap' => '%3$s'
              );
              wp_nav_menu($args);
              ?>
            </ul>
          </div>
        </div>
      </div>
    </nav>
    <?php

    // args
    $args = array(
      'posts_per_page'    => -1,
      'post_type'     => 'page',
      'meta_key'      => 'mega_menu_item',
      'meta_value'    => 1
    );

    // query
    $the_query = new WP_Query($args);

    ?>
    <?php if ($the_query->have_posts()) : while ($the_query->have_posts()) : $the_query->the_post();
        $id = get_the_ID();
        $slug = get_post_field('post_name', $id);
        $description = get_field('mega_menu_description') ?? null;
        $image = get_field('mega_menu_image') ?? null;
    ?>
        <nav id="<?php echo $slug ?>-nav" class="mega">
          <div class="wrapper">
            <div class="flex">
              <div class="details">
                <li class="mobile-nav back">Back</li>
                <h2 class="orange-text"><?php the_title(); ?></h2>
                <p><?php echo $description ?></p>
                <?php if ($image) { ?>
                  <img src="<?php echo $image['url'] ?>" alt="<?php echo $image['alt'] ?>" />
                <?php } ?>
              </div>
              <div class="links">
                <ul>
                  <?php
                  // args
                  $args2 = array(
                    'posts_per_page'    => -1,
                    'post_type'     => 'page',
                    'post_parent' => $id,
                    'orderby' => 'menu_order',
                    'order' => 'ASC'
                  );

                  // query
                  $the_query2 = new WP_Query($args2);
                  $i = 0;
                  if ($the_query2->have_posts()) : while ($the_query2->have_posts()) : $the_query2->the_post();
                      $i++;
                      $id2 = get_the_ID();
                      $slug2 = get_post_field('post_name', $id2);
                  ?>
                      <?php if ($i == 6) {
                        echo '</ul><ul>';
                      } ?>
                      <li class="<?php echo $slug2 ?>">
                        <h3 class="h3a"><a href="<?php the_permalink() ?>"><?php the_title() ?> <?php get_template_part('assets/images/arrow.svg') ?></a></h3>


                        <?php
                        // args
                        $args3 = array(
                          'posts_per_page'    => -1,
                          'post_type'     => 'page',
                          'post_parent' => $id2,
                          'orderby' => 'menu_order',
                          'order' => 'ASC'
                        );

                        // query
                        $the_query3 = new WP_Query($args3);
                        if ($the_query3->have_posts()) : ?>
                          <ul>
                            <?php while ($the_query3->have_posts()) : $the_query3->the_post();
                            ?>
                              <li><a href="<?php the_permalink() ?>"><?php the_title() ?> <?php get_template_part('assets/images/arrow.svg') ?></a></li>

                            <?php endwhile; ?>
                          </ul>
                        <?php endif; ?>
                      </li>
                  <?php endwhile;
                  endif; ?>
                </ul>
              </div>
            </div>
          </div>
        </nav>
    <?php endwhile;
    endif; ?>

    <?php wp_reset_query();   // Restore global post data stomped by the_post(). 
    ?>
    <div class="mobile-nav mobile-close"></div>

    <div id="search-form">
      <div class="wrapper">
        <form action="/" method="get">
          <input type="text" name="s" id="search" placeholder="Search" />
          <input type="submit" id="searchsubmit" value="Submit" />
        </form>
      </div>
    </div>

  </header>

  <main class="banner-<?php echo $banner_height . $page_style ?>">

    <div class="breadcrumbs desktop">
      <?php
      if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
      }
      ?>
    </div>