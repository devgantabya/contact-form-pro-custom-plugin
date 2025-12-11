<?php

add_action('admin_menu', function () {
    add_menu_page(
        'CFP Forms',
        'Contact Forms',
        'manage_options',
        'cfp_forms',
        function () {
            echo "<h1>Forms</h1><p>Use CFP Form CPT to add/edit forms.</p>"; },
        'dashicons-feedback'
    );

    add_submenu_page(
        'cfp_forms',
        'Submissions',
        'Submissions',
        'manage_options',
        'cfp_submissions',
        'cfp_render_submissions_page'
    );
});
