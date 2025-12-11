<?php
if (!defined('ABSPATH'))
    exit;

/**
 * Register CPT: cfp_form
 * Stores form definitions. Shortcode will reference posts of this type by ID.
 */

function cfp_register_form_cpt()
{
    $labels = [
        'name' => 'Forms',
        'singular_name' => 'Form',
        'menu_name' => 'Contact Forms',
        'name_admin_bar' => 'Contact Form',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Form',
        'edit_item' => 'Edit Form',
        'new_item' => 'New Form',
        'view_item' => 'View Form',
        'all_items' => 'All Forms',
        'search_items' => 'Search Forms',
        'not_found' => 'No forms found.',
    ];

    $args = [
        'labels' => $labels,
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'capability_type' => 'post',
        'supports' => ['title', 'editor'],
        'menu_position' => 20,
        'menu_icon' => 'dashicons-feedback',
        'has_archive' => false,
    ];

    register_post_type('cfp_form', $args);
}
add_action('init', 'cfp_register_form_cpt', 10);
