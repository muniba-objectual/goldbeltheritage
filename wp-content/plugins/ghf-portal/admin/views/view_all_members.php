<?php
/**
 * The Template for displaying all submissions.
 *
 * This template can be overridden by copying it to yourtheme/ghf_templates/applications/view_all_submissions.php.
 *
 */

defined( 'ABSPATH' ) || exit;

get_header('logged-in');

do_action('ghf_dashboard'); ?>

<div class="general_page_header">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <h3 class="page-title">All Members</h3>
            <p class="page-description">View all committee members.</p>
        </div>
    </div>
</div>
<?php
do_action('ghf_dashboard_end');

get_footer('logged-in');
