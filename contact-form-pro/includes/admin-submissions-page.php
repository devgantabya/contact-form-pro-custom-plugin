<?php
if (!defined('ABSPATH'))
    exit;


function cfp_register_submissions_page()
{
    add_submenu_page(
        'edit.php?post_type=cfp_form',
        'Form Submissions',
        'Submissions',
        'manage_options',
        'cfp-submissions',
        'cfp_render_submissions_page'
    );
}
add_action('admin_menu', 'cfp_register_submissions_page');


function cfp_export_csv()
{
    if (!isset($_GET['cfp_export']) || $_GET['cfp_export'] !== '1') {
        return;
    }

    if (!current_user_can('manage_options')) {
        wp_die('Not allowed.');
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="submissions.csv"');

    $output = fopen("php://output", "w");

    fputcsv($output, ['ID', 'Form ID', 'Name', 'Email', 'Subject', 'Message', 'Date']);

    $args = [
        'post_type' => 'cfp_submission',
        'posts_per_page' => -1,
    ];

    $submissions = get_posts($args);

    foreach ($submissions as $sub) {
        $meta = get_post_meta($sub->ID);
        fputcsv($output, [
            $sub->ID,
            $meta['cfp_form_id'][0] ?? '',
            $meta['cfp_name'][0] ?? '',
            $meta['cfp_email'][0] ?? '',
            $meta['cfp_subject'][0] ?? '',
            $meta['cfp_message'][0] ?? '',
            $sub->post_date
        ]);
    }

    fclose($output);
    exit;
}
add_action('admin_init', 'cfp_export_csv');


function cfp_render_submissions_page()
{

    $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $filter_form = isset($_GET['form_id']) ? sanitize_text_field($_GET['form_id']) : '';

    $args = [
        'post_type' => 'cfp_submission',
        'posts_per_page' => 10,
        'paged' => $paged,
        's' => $search,
    ];

    if (!empty($filter_form)) {
        $args['meta_query'] = [
            [
                'key' => 'cfp_form_id',
                'value' => $filter_form,
            ]
        ];
    }

    $query = new WP_Query($args);
    ?>

    <div class="wrap">
        <h1>Form Submissions</h1>

        <form method="get" style="margin-bottom: 20px;">
            <input type="hidden" name="post_type" value="cfp_form">
            <input type="hidden" name="page" value="cfp-submissions">

            <input type="text" name="s" placeholder="Search..." value="<?php echo esc_attr($search); ?>">
            <input type="text" name="form_id" placeholder="Filter by Form ID" value="<?php echo esc_attr($filter_form); ?>">

            <button class="button">Filter</button>

            <a href="?post_type=cfp_form&page=cfp-submissions" class="button">Reset</a>

            <a href="?post_type=cfp_form&page=cfp-submissions&cfp_export=1" class="button button-primary"
                style="float:right;">
                Export CSV
            </a>
        </form>

        <table class="widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Form ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($query->have_posts()): ?>
                    <?php while ($query->have_posts()):
                        $query->the_post();
                        $id = get_the_ID();
                        $meta = get_post_meta($id);
                        ?>
                        <tr>
                            <td><?php echo $id; ?></td>
                            <td><?php echo esc_html($meta['cfp_form_id'][0] ?? ''); ?></td>
                            <td><?php echo esc_html($meta['cfp_name'][0] ?? ''); ?></td>
                            <td><?php echo esc_html($meta['cfp_email'][0] ?? ''); ?></td>
                            <td><?php echo esc_html($meta['cfp_subject'][0] ?? ''); ?></td>
                            <td><?php echo esc_html($meta['cfp_message'][0] ?? ''); ?></td>
                            <td><?php echo get_the_date(); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No submissions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php
        echo paginate_links([
            'total' => $query->max_num_pages,
            'current' => $paged,
            'format' => '&paged=%#%',
        ]);

        wp_reset_postdata();
        ?>
    </div>

    <?php
}
