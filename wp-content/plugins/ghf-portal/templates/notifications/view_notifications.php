<?php
/**
 * The Template for displaying all avaialable forms.
 *
 * This template can be overridden by copying it to yourtheme/ghf_templates/forms/view_all_forms.php.
 *
 */

defined( 'ABSPATH' ) || exit;

get_header('logged-in');

do_action('ghf_dashboard'); ?>

<div class="general_page_header">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <?php echo '<h3 class="page-title">Notifications</h3><p class="page-description">View all your notifications</p>';
            ?>
        </div>
    </div>
</div>
<div class="page_content">
    <?php do_action('user_notifications'); ?>
</div>
<?php
do_action('ghf_dashboard_end');

get_footer('logged-in');
