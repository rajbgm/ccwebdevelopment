<?php
function register_events()
{
  $labels = array(
    'name' => 'Events',
    'singular_name' => 'Events',
    'add_new' => 'Add',
    'add_new_item' => 'Add New',
    'edit_item' => 'Edit',
    'new_item' => 'New',
    'all_items' => 'All Events',
    'view_item' => 'View Events',
    'search_items' => 'Search Events',
    'not_found' =>  'No Events found',
    'not_found_in_trash' => 'No Events found in Trash',
    'parent_item_colon' => '',
    'menu_name' => 'Events'
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
    'supports' => array('title', 'editor', 'revisions', 'excerpt', 'thumbnail', 'custom-fields'),
    'rewrite' => array('slug' => 'event'),
    'menu_icon' => 'dashicons-calendar'
    //WP ICONS: https://developer.wordpress.org/resource/dashicons/
  );

  register_post_type('events', $args);
}

add_action('init', 'register_events');