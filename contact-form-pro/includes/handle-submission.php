<?php
if (!defined('ABSPATH'))
    exit;

/**
 * Handle frontend form submissions.
 * - Verifies nonce
 * - Validates & sanitizes inputs
 * - Inserts cfp_submission post with meta
 * - Redirects back with success/error indicators
 */

function cfp_handle_submission()
{
    // Only handle when our form flag is present
    if (empty($_POST['cfp_submit'])) {
        return;
    }

    // Basic referer check (optional)
    if (!isset($_POST['cfp_nonce']) || !wp_verify_nonce($_POST['cfp_nonce'], 'cfp_submit_action')) {
        cfp_redirect_with_error('Security check failed (invalid nonce).');
    }

    // Gather & sanitize inputs
    $name = isset($_POST['cfp_name']) ? sanitize_text_field(wp_unslash($_POST['cfp_name'])) : '';
    $email = isset($_POST['cfp_email']) ? sanitize_email(wp_unslash($_POST['cfp_email'])) : '';
    $subject = isset($_POST['cfp_subject']) ? sanitize_text_field(wp_unslash($_POST['cfp_subject'])) : '';
    $message = isset($_POST['cfp_message']) ? sanitize_textarea_field(wp_unslash($_POST['cfp_message'])) : '';
    $form_id = isset($_POST['cfp_form_id']) ? intval($_POST['cfp_form_id']) : 0;

    // Basic validation
    if (empty($name) || empty($email) || empty($message) || empty($subject) || !$form_id) {
        cfp_redirect_with_error('Please fill in all required fields.');
    }

    if (!is_email($email)) {
        cfp_redirect_with_error('Please provide a valid email address.');
    }

    // Optional: ensure the form exists
    $form_post = get_post($form_id);
    if (!$form_post || $form_post->post_type !== 'cfp_form') {
        cfp_redirect_with_error('Form not found.');
    }

    // Prepare post for insertion
    $postarr = [
        'post_title' => wp_trim_words($subject, 8, '...'),
        'post_type' => 'cfp_submission',
        'post_status' => 'publish',
        'post_content' => wp_trim_words($message, 50, '...'),
    ];

    $submission_id = wp_insert_post($postarr, true);

    if (is_wp_error($submission_id)) {
        cfp_redirect_with_error('Failed to save submission. Please try again later.');
    }

    // Save meta
    update_post_meta($submission_id, 'cfp_form_id', $form_id);
    update_post_meta($submission_id, 'cfp_name', $name);
    update_post_meta($submission_id, 'cfp_email', $email);
    update_post_meta($submission_id, 'cfp_subject', $subject);
    update_post_meta($submission_id, 'cfp_message', $message);

    // Optionally: fire action so other code can hook (e.g., send email)
    do_action('cfp_submission_saved', $submission_id, [
        'name' => $name,
        'email' => $email,
        'subject' => $subject,
        'message' => $message,
        'form_id' => $form_id,
    ]);

    // Redirect back with success
    $redirect = wp_get_referer();
    if (!$redirect) {
        $redirect = home_url('/');
    }
    $redirect = add_query_arg('cfp_success', '1', $redirect);

    wp_safe_redirect($redirect);
    exit;
}
add_action('init', 'cfp_handle_submission', 20);


/**
 * Helper: redirect with error message (urlencoded) and preserve posted values for convenience
 */
function cfp_redirect_with_error($error_message)
{
    $redirect = wp_get_referer();
    if (!$redirect) {
        $redirect = home_url('/');
    }

    // Preserve some fields so the shortcode can pre-fill them on redirect
    $preserve = [
        'cfp_name' => isset($_POST['cfp_name']) ? sanitize_text_field(wp_unslash($_POST['cfp_name'])) : '',
        'cfp_email' => isset($_POST['cfp_email']) ? sanitize_email(wp_unslash($_POST['cfp_email'])) : '',
        'cfp_subject' => isset($_POST['cfp_subject']) ? sanitize_text_field(wp_unslash($_POST['cfp_subject'])) : '',
        'cfp_message' => isset($_POST['cfp_message']) ? sanitize_textarea_field(wp_unslash($_POST['cfp_message'])) : '',
    ];

    $redirect = add_query_arg('cfp_error', rawurlencode($error_message), $redirect);

    // Append preserved fields (so shortcode can read them via $_REQUEST)
    foreach ($preserve as $k => $v) {
        $redirect = add_query_arg($k, rawurlencode($v), $redirect);
    }

    wp_safe_redirect($redirect);
    exit;
}
