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
           <h3 class="page-title">Submitted Applications</h3><p class="page-description">Click on "View Submission" to review.</p>
        </div>
    </div>
</div>
<div class="page_content">
    <div class="view_all_submissions_wrapper">
        <?php do_action('view_all_submissions'); ?>
    </div>
</div>
<?php
do_action('ghf_dashboard_end');

get_footer('logged-in');
