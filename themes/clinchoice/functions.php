<?php

// front end css
function sffe()
{
  $ver = '8.12.18';
  wp_enqueue_style('sf-style', get_template_directory_uri() . '/assets/styles/app.css', '', $ver);
  wp_enqueue_style('sf-custom', get_template_directory_uri() . '/assets/styles/custom.css', '', $ver);
  wp_register_script('sf-script', get_template_directory_uri() . '/assets/scripts/app.js', '', $ver, true);
  wp_register_script('temp-script', get_template_directory_uri() . '/assets/scripts/regulatory.js', '', $ver, true);
  wp_enqueue_script('sf-script');
  wp_enqueue_script('temp-script');
}
add_action('wp_enqueue_scripts', 'sffe');

add_filter('gform_disable_form_theme_css', '__return_true');

// testing
add_filter('facetwp_facet_dropdown_show_counts', '__return_false');

// theme support
add_theme_support('post-thumbnails');
add_theme_support('menus');

// acf blocks - create
add_action('acf/init', 'my_acf_init');
function my_acf_init()
{
  @include 'blocks/register.php';

  // acf options page
  if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
      'page_title'     => 'Options',
      'post_id'       => 'options',
      'menu_title'    => 'Options',
      'menu_slug'     => 'theme-settings',
      'capability'    => 'edit_posts',
      'redirect'        => false
    ));
  }
}

// custom posts
@include 'cpts/events.php';
@include 'cpts/leadership.php';
@include 'cpts/locations.php';
@include 'cpts/regulatory.php';
@include 'cpts/solutions.php';


// acf blocks - admin view
if (is_admin()) {

  add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
  });

  // admin css/js
  function acfjs()
  {
    wp_enqueue_style('sf-admin-style', get_template_directory_uri() . '/assets/styles/editor.css');
    wp_register_script('sf-admin-script', get_template_directory_uri() . '/assets/scripts/editor.js', ['acf-input'], '1.0.0', true);
    wp_enqueue_script('sf-admin-script');
  }
  add_action('admin_enqueue_scripts', 'acfjs');
}

// use classic editor for posts
add_filter('use_block_editor_for_post_type', 'prefix_disable_gutenberg', 10, 2);
function prefix_disable_gutenberg($current_status, $post_type)
{
  if ($post_type === 'post') return false;
  return $current_status;
}

// Theme Helper - Easily pull filepaths
function filepaths()
{
  global $assets;
  $assets = get_template_directory_uri() . '/assets';

  global $images;
  $images = $assets . '/images';
}
add_action('after_setup_theme', 'filepaths');

// Brand Colors
function override_MCE_options($init)
{
  $custom_colors = '
          "1b355e", "Navy",
          "ffffff", "White",
          "00abc7", "Cyan",
          "a23a95", "Purple",
          "f58220", "Orange",
          "01ca64", "Green",
          "365eab", "Blue",
          "7ca4d6", "Light Blue",
          "415465", "Neutral",
          "95a3ae", "Light Neutral",
          "d8dfe1", "Lighter Neutral"
      ';
  $init['textcolor_map'] = '[' . $custom_colors . ']';
  $init['textcolor_rows'] = 2;

  return $init;
}
add_filter('tiny_mce_before_init', 'override_MCE_options');

/*  DISABLE GUTENBERG STYLE IN HEADER| WordPress 5.9 */
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');

add_action('wp_enqueue_scripts', function () {
  // https://github.com/WordPress/gutenberg/issues/36834
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');

  // https://stackoverflow.com/a/74341697/278272
  wp_dequeue_style('classic-theme-styles');

  // Or, go deep: https://fullsiteediting.com/lessons/how-to-remove-default-block-styles
});

add_filter('should_load_separate_core_block_assets', '__return_true');

// pagination
/* add_filter('previous_posts_link_attributes', 'previous_link_attributes');
add_filter('next_posts_link_attributes', 'next_link_attributes');

function previous_link_attributes()
{
  return 'class="button reverse"';
}
function next_link_attributes()
{
  return 'class="button"';
} */

function tax_cat_active($output, $args)
{
  if (is_single()) {
    global $post;
    $terms = get_the_terms($post->ID, 'category');
    if (!empty($terms)) {
      foreach ($terms as $term)
        if (preg_match('#cat-item-' . $term->term_id . '#', $output))
          $output = str_replace('cat-item-' . $term->term_id, 'cat-item-' . $term->term_id . ' current-cat', $output);
    }
  }
  return $output;
}
add_filter('wp_list_categories', 'tax_cat_active', 10, 2);


function custom_pre_get_posts($query)
{
  if ($query->is_main_query() && !$query->is_feed() && !is_admin() && is_category()) {
    $query->set('paged', str_replace('/', '', get_query_var('page')));
  }
}

add_action('pre_get_posts', 'custom_pre_get_posts');

function custom_request($query_string)
{
  if (isset($query_string['page'])) {
    if ('' != $query_string['page']) {
      if (isset($query_string['name'])) {
        unset($query_string['name']);
      }
    }
  }
  return $query_string;
}

add_filter('request', 'custom_request');


/**
 * Register and enqueue a custom stylesheet in the WordPress admin.
 */
function wpdocs_enqueue_custom_admin_style()
{
  wp_register_style('custom_wp_admin_css', 'https://use.typekit.net/tbj4nmt.css', false, '1.0.0');
  wp_enqueue_style('custom_wp_admin_css');
}
add_action('admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_style');


if (!class_exists('custom_walker')) {
  class custom_walker extends Walker_Nav_Menu
  {
    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
    {
      $output .= "<li class='" .  implode(" ", $item->classes) . "'>";
      $output .= '<a href="' . $item->url . '">';
      $output .= $item->title;
      if ($item->description) {
        $output .= do_shortcode($item->description);
      }
      $output .= '</a>';
    }
  }
}

// class Menu_With_Description extends Walker_Nav_Menu
// {
//   function start_el(&$output, $item, $depth, $args)
//   {
//     global $wp_query;
//     $indent = ($depth) ? str_repeat("\t", $depth) : '';

//     $class_names = $value = '';

//     $classes = empty($item->classes) ? array() : (array) $item->classes;

//     $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item));
//     $class_names = ' class="' . esc_attr($class_names) . '"';

//     $output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $value . $class_names . '>';

//     $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
//     $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
//     $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
//     $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';

//     $item_output = $args->before;
//     $item_output .= '<a' . $attributes . '>';
//     $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
//     $item_output .= '<br /><span class="sub">' . $item->description . '</span>';
//     $item_output .= '</a>';
//     $item_output .= $args->after;

//     $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
//   }
// }

add_filter('body_class', 'dc_parent_body_class');
function dc_parent_body_class($classes)
{
  if (is_page()) {
    $parents = get_post_ancestors(get_the_id());
    $id = ($parents) ? $parents[count($parents) - 1] : get_the_id();
    $ancestor = get_post($id);
    $level = count($parents);
    $classes[] = $ancestor->post_name . '-child level-' . count($parents) + 1;
  }

  return $classes;
}

register_taxonomy(
  'source',
  'post',
  array(
    'hierarchical' => false,
    'labels' => array(
      'name' => 'Source',
      'menu_name' => 'Sources',
      'singular_name' => 'Source',
      'search_items' => 'Search Sources',
      'all_items' => 'All Sources',
      'edit_item' => 'Edit Source',
      'update_item' => 'Update Source',
      'add_new_item' => 'Add New Source',
      'new_item_name' => 'New Source Name',
      'separate_items_with_commas' => '',
      'choose_from_most_used' => 'Choose from most common Sources'
    ),
    'show_admin_column' => true,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'source'),
  )
);

function my_login_logo_one()
{
?>
  <style type="text/css">
    body.login div#login h1 a {
      background-image: url('logo.svg');
      background-size: contain;
      margin-top: 50px;
      width: 200px;
      height: 62px;
    }
  </style>
<?php
}
add_action('login_enqueue_scripts', 'my_login_logo_one');

add_filter('gform_disable_css', '__return_true');

add_action('init', 'cp_change_post_object');
// Change dashboard Posts to News
function cp_change_post_object()
{
  $get_post_type = get_post_type_object('post');
  $labels = $get_post_type->labels;
  $labels->menu_name = 'Insights';
  $labels->name_admin_bar = 'Insights';
}

add_filter('gform_confirmation', function ($confirmation, $form, $entry, $ajax) {
  GFCommon::log_debug('gform_confirmation: running.');

  $forms = array(4);

  if (!in_array($form['id'], $forms) || empty($confirmation['redirect'])) {
    return $confirmation;
  }

  $url = esc_url_raw($confirmation['redirect']);
  GFCommon::log_debug(__METHOD__ . '(): Redirect to URL: ' . $url);
  $confirmation = '<h2>Thank You!</h2> <p>Your resource will begin downloading automatically.<br>You may also <a href="' . $url . '" target="_blank" download>click here</a> for a direct link.</p>';
  $confirmation .= GFCommon::get_inline_script_tag("window.open('$url', '_blank');");

  return $confirmation;
}, 10, 4);

/**
 * Disable the emoji's
 */
function disable_emojis()
{
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
  add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
  add_filter('wp_resource_hints', 'disable_emojis_remove_dns_prefetch', 10, 2);
}
add_action('init', 'disable_emojis');

/**
 * Filter function used to remove the tinymce emoji plugin.
 * 
 * @param array $plugins 
 * @return array Difference betwen the two arrays
 */
function disable_emojis_tinymce($plugins)
{
  if (is_array($plugins)) {
    return array_diff($plugins, array('wpemoji'));
  } else {
    return array();
  }
}

/**
 * Remove emoji CDN hostname from DNS prefetching hints.
 *
 * @param array $urls URLs to print for resource hints.
 * @param string $relation_type The relation type the URLs are printed for.
 * @return array Difference betwen the two arrays.
 */
function disable_emojis_remove_dns_prefetch($urls, $relation_type)
{
  if ('dns-prefetch' == $relation_type) {
    /** This filter is documented in wp-includes/formatting.php */
    $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');

    $urls = array_diff($urls, array($emoji_svg_url));
  }

  return $urls;
}

/*
 * Add custom FacetWP functions below
 */

 add_filter('facetwp_index_row', function ($params, $class) {
  if ('regulatory_date' == $params['facet_name']) { // change "my_facet_name" to the name of your facet
    $raw_value = $params['facet_value'];
    $params['facet_value'] = date('Y-m', strtotime($raw_value)); // Use "2023-11" for the facet choice in the URL
    $params['facet_display_value'] = date('F Y', strtotime($raw_value)); // Use "November 2023" for the facet choice's display value
  }
  return $params;
}, 10, 2);

add_filter('facetwp_facet_orderby', function ($orderby, $facet) {
  if ('regulatory_date' == $facet['name']) {
    $orderby = 'f.facet_value DESC';
  }
  return $orderby;
}, 10, 2);

add_filter( 'facetwp_is_main_query', function( $is_main_query, $query ) {
  if ( $query->is_archive() && $query->is_main_query() ) {
    $is_main_query = false;
  }
  return $is_main_query;
}, 10, 2 );


function change_region()
{
  global $images;
  return '<div class="language_wrapper"><a href="https://www.clinchoice.com.cn/" target="_blank"><img loading="lazy" src="'.$images.'/flag-china.svg" alt="china"><span>China</span></a><a href="https://clinchoice.co.jp/"target="_blank"><img loading="lazy" src="'.$images.'/flag-japan.svg" width="48" height="48" alt="japan"><span>Japan</span></a></div>';
}
add_shortcode('regions', 'change_region');

?>