<?php
/**
 * Plugin Name: Contact Form Pro
 * Description: Simple contact form using CPT with shortcode and admin submission viewer.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH'))
    exit;

require_once plugin_dir_path(__FILE__) . 'includes/cpt-form.php';
require_once plugin_dir_path(__FILE__) . 'includes/cpt-submission.php';


require_once plugin_dir_path(__FILE__) . 'includes/shortcode-form.php';
require_once plugin_dir_path(__FILE__) . 'includes/handle-submission.php';


require_once plugin_dir_path(__FILE__) . 'includes/admin-menu.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-submissions-page.php';



add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'cfp-frontend',
        plugin_dir_url(__FILE__) . 'assets/css/frontend.css',
        [],
        '1.0'
    );
});

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style(
        'cfp-admin',
        plugin_dir_url(__FILE__) . 'assets/css/admin.css',
        [],
        '1.0'
    );
});