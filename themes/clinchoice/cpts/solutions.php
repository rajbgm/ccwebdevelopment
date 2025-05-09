<?php
function register_solutions()
{
  $labels = array(
    'name' => 'Solutions',
    'singular_name' => 'Solutions',
    'add_new' => 'Add',
    'add_new_item' => 'Add New',
    'edit_item' => 'Edit',
    'new_item' => 'New',
    'all_items' => 'All Solutions',
    'view_item' => 'View Solutions',
    'search_items' => 'Search Solutions',
    'not_found' =>  'No Solutions found',
    'not_found_in_trash' => 'No Solutions found in Trash',
    'parent_item_colon' => '',
    'menu_name' => 'Solutions'
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
    'rewrite' => array('slug' => 'solution'),
    'menu_icon' => 'dashicons-lightbulb'
    //WP ICONS: https://developer.wordpress.org/resource/dashicons/
  );

  register_post_type('solutions', $args);
}

add_action('init', 'register_solutions');