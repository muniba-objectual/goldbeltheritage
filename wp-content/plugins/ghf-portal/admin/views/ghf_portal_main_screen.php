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
           <h3 class="page-title">Manage Forms</h3><!-- <p class="page-description">Create, Edit or Delete Forms.</p> -->
        </div>
        <div class="col-lg-6 text-end">
            <a class="create_new_form" href="<?php echo site_url('/create_new_form'); ?>">Create Form</a>
        </div>
    </div>
</div>
<div class="page_content">
    <div class="all-forms-wrapper">
        <?php $GHFApplications->ghf_all_available_applications() ?>
    </div>
</div>
<?php
do_action('ghf_dashboard_end');

get_footer('logged-in');
