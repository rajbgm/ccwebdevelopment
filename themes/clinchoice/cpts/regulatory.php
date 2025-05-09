<?php
function register_regulatory()
{
  $labels = array(
    'name' => 'Regulatory Intelligence',
    'singular_name' => 'Regulatory Intelligence',
    'add_new' => 'Add',
    'add_new_item' => 'Add New',
    'edit_item' => 'Edit',
    'new_item' => 'New',
    'all_items' => 'View All',
    'view_item' => 'View',
    'search_items' => 'Search',
    'not_found' =>  'No Locations found',
    'not_found_in_trash' => 'No Locations found in Trash',
    'parent_item_colon' => '',
    'menu_name' => 'Regulatory Intelligence'
  );

  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'has_archive' => false,
    'hierarchical' => true,
    'menu_position' => null,
    'supports' => array('title'),
    'rewrite' => array('slug' => 'regulatory'),
    'menu_icon' => 'dashicons-media-spreadsheet'
    //WP ICONS: https://developer.wordpress.org/resource/dashicons/
  );

  register_post_type('regulatory', $args);
}

add_action('init', 'register_regulatory');

// register_taxonomy( 'region', 'location', array(
//     'hierarchical' => true,
//     'labels' => array(
//       'name' => 'Region',
//       'menu_name' => 'Regions',
//       'singular_name' => 'Region',
//       'search_items' => 'Search Regions',
//       'all_items' => 'All Regions',
//       'edit_item' => 'Edit Region',
//       'update_item' => 'Update Region',
//       'add_new_item' => 'Add New Region',
//       'new_item_name' => 'New Region Name',
//       'separate_items_with_commas' => '',
//       'choose_from_most_used' => 'Choose from most common Regions'
//     ),
//     'show_admin_column' => true,
//     'show_ui' => true,
//     'query_var' => true,
//     'rewrite' => array('slug' => 'regions'),
//   )
// );
