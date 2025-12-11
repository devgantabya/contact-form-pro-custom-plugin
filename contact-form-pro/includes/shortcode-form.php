<?php
if (!defined('ABSPATH'))
    exit;

/**
 * Shortcode: [cfp_form id="POST_ID"]
 * Renders the form UI and shows success / error messages from submission handling.
 */

function cfp_form_shortcode($atts)
{
    $atts = shortcode_atts([
        'id' => '',
    ], $atts, 'cfp_form');

    $form_id = intval($atts['id']);
    if (!$form_id) {
        return '<p><strong>Contact Form:</strong> invalid form ID.</p>';
    }

    // Optionally, verify that the form post exists
    $form_post = get_post($form_id);
    if (!$form_post || $form_post->post_type !== 'cfp_form') {
        return '<p><strong>Contact Form:</strong> form not found.</p>';
    }

    // Messages from redirect
    $message_html = '';
    if (isset($_GET['cfp_success']) && intval($_GET['cfp_success']) === 1) {
        $message_html = '<div class="cfp-success" style="margin-bottom:15px;padding:10px;border:1px solid #46b450;background:#e6f7ec;">Thank you — your message was sent.</div>';
    } elseif (!empty($_GET['cfp_error'])) {
        $err = sanitize_text_field($_GET['cfp_error']);
        $message_html = '<div class="cfp-error" style="margin-bottom:15px;padding:10px;border:1px solid #d33;background:#fdecea;color:#900;">' . esc_html($err) . '</div>';
    }

    // Preserve old values if provided (e.g. after validation failure)
    $old = [
        'name' => isset($_REQUEST['cfp_name']) ? sanitize_text_field($_REQUEST['cfp_name']) : '',
        'email' => isset($_REQUEST['cfp_email']) ? sanitize_email($_REQUEST['cfp_email']) : '',
        'subject' => isset($_REQUEST['cfp_subject']) ? sanitize_text_field($_REQUEST['cfp_subject']) : '',
        'message' => isset($_REQUEST['cfp_message']) ? sanitize_textarea_field($_REQUEST['cfp_message']) : '',
    ];

    ob_start();
    ?>
    <div class="cfp-form-wrap">
        <?php echo $message_html; ?>

        <form method="post" class="cfp-form" action="">
            <?php wp_nonce_field('cfp_submit_action', 'cfp_nonce'); ?>
            <input type="hidden" name="cfp_submit" value="1">
            <input type="hidden" name="cfp_form_id" value="<?php echo esc_attr($form_id); ?>">

            <p>
                <label for="cfp_name">Name</label><br>
                <input type="text" id="cfp_name" name="cfp_name" required value="<?php echo esc_attr($old['name']); ?>">
            </p>

            <p>
                <label for="cfp_email">Email</label><br>
                <input type="email" id="cfp_email" name="cfp_email" required value="<?php echo esc_attr($old['email']); ?>">
            </p>

            <p>
                <label for="cfp_subject">Subject</label><br>
                <input type="text" id="cfp_subject" name="cfp_subject" required
                    value="<?php echo esc_attr($old['subject']); ?>">
            </p>

            <p>
                <label for="cfp_message">Message</label><br>
                <textarea id="cfp_message" name="cfp_message" rows="6"
                    required><?php echo esc_textarea($old['message']); ?></textarea>
            </p>

            <p>
                <button type="submit" class="cfp-submit button">Send Message</button>
            </p>
        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('cfp_form', 'cfp_form_shortcode');
