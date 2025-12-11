<?php
if (!defined('ABSPATH'))
    exit;

/**
 * Register CPT: cfp_submission
 * Each submission becomes a post of this type (stored as non-public posts).
 */

function cfp_register_submission_cpt()
{
    $labels = [
        'name' => 'Submissions',
        'singular_name' => 'Submission',
        'menu_name' => 'Submissions',
        'name_admin_bar' => 'Submission',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Submission',
        'edit_item' => 'Edit Submission',
        'new_item' => 'New Submission',
        'view_item' => 'View Submission',
        'all_items' => 'All Submissions',
        'search_items' => 'Search Submissions',
        'not_found' => 'No submissions found.',
    ];

    $args = [
        'labels' => $labels,
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'capability_type' => 'post',
        'supports' => ['title'],
        'has_archive' => false,
    ];

    register_post_type('cfp_submission', $args);
}
add_action('init', 'cfp_register_submission_cpt', 10);
