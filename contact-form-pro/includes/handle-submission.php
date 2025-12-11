<?php

add_action('init', function () {

    if (!isset($_POST['cfp_submit']))
        return;

    if (
        !isset($_POST['cfp_nonce']) ||
        !wp_verify_nonce($_POST['cfp_nonce'], 'cfp_submit_form')
    ) {
        wp_die('Security check failed.');
    }

    $form_id = intval($_POST['cfp_form_id']);
    $name = sanitize_text_field($_POST['cfp_name']);
    $email = sanitize_email($_POST['cfp_email']);
    $subject = sanitize_text_field($_POST['cfp_subject']);
    $message = sanitize_textarea_field($_POST['cfp_message']);

    if (!$name || !$email || !$subject || !$message) {
        wp_die('Validation error.');
    }

    $post_id = wp_insert_post([
        'post_type' => 'cfp_submission',
        'post_title' => "Submission from $name",
        'post_status' => 'publish'
    ]);

    update_post_meta($post_id, 'cfp_name', $name);
    update_post_meta($post_id, 'cfp_email', $email);
    update_post_meta($post_id, 'cfp_subject', $subject);
    update_post_meta($post_id, 'cfp_message', $message);
    update_post_meta($post_id, 'cfp_form_id', $form_id);

    add_action('wp_footer', function () {
        echo "<p class='cfp-success'>Thank you! Your message has been sent.</p>";
    });
});
