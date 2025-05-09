<?php
function registerLeadership()
{
  $labels = array(
    'name' => 'Leadership',
    'singular_name' => 'Leadership',
    'add_new' => 'Add',
    'add_new_item' => 'Add New',
    'edit_item' => 'Edit',
    'new_item' => 'New',
    'all_items' => 'All',
    'view_item' => 'View',
    'search_items' => 'Search',
    'not_found' =>  'Not Found',
    'not_found_in_trash' => 'Not Found',
    'parent_item_colon' => '',
    'menu_name' => 'Leadership'
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
    'supports' => array('title', 'thumbnail', 'editor'),
    'rewrite' => array('slug' => 'leadership'),
    'menu_icon' => 'dashicons-groups'
    //WP ICONS: https://developer.wordpress.org/resource/dashicons/
  );

  register_post_type('leadership', $args);
}

add_action('init', 'registerLeadership');


register_taxonomy( 'leadership-role', 'leadership', array(
  'hierarchical' => true,
  'labels' => array(
    'name' => 'Role',
    'menu_name' => 'Roles',
    'singular_name' => 'Role',
    'search_items' => 'Search Roles',
    'all_items' => 'All Roles',
    'edit_item' => 'Edit Role',
    'update_item' => 'Update Role',
    'add_new_item' => 'Add New Role',
    'new_item_name' => 'New Role Name',
    'separate_items_with_commas' => '',
    'choose_from_most_used' => 'Choose from most common Roles'
  ),
  'show_admin_column' => true,
  'show_ui' => true,
  'query_var' => true,
  'rewrite' => array('slug' => 'roles'),
)
);
