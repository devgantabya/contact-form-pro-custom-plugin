<?php
/**
 * Plugin Name: Contact Form Pro
 * Description: Simple contact form using CPT with shortcode and admin submission viewer.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH'))
    exit;



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