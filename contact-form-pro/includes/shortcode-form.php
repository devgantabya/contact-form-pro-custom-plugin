<?php

function cfp_form_shortcode($atts)
{
    $atts = shortcode_atts(['id' => 0], $atts);

    if (!$atts['id'])
        return "<p>Form ID missing.</p>";

    ob_start(); ?>

    <form method="post" class="cfp-form">
        <input type="hidden" name="cfp_form_id" value="<?php echo $atts['id']; ?>">
        <?php wp_nonce_field('cfp_submit_form', 'cfp_nonce'); ?>

        <label>Name</label>
        <input type="text" name="cfp_name" required>

        <label>Email</label>
        <input type="email" name="cfp_email" required>

        <label>Subject</label>
        <input type="text" name="cfp_subject" required>

        <label>Message</label>
        <textarea name="cfp_message" required></textarea>

        <button type="submit" name="cfp_submit">Send</button>
    </form>

    <?php return ob_get_clean();
}

add_shortcode('cfp_form', 'cfp_form_shortcode');
