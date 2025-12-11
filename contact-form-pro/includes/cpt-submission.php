<?php

add_action('init', function () {
    register_post_type('cfp_submission', [
        'label' => 'Submissions',
        'public' => false,
        'show_ui' => false,
        'supports' => ['title'],
    ]);
});
