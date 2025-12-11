<?php

add_action('init', function () {
    register_post_type('cfp_form', [
        'label' => 'Forms',
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-feedback',
        'supports' => ['title'],
    ]);
});
