<?php
/**
 * The Template for displaying all avaialable forms.
 *
 * This template can be overridden by copying it to yourtheme/ghf_templates/forms/view_all_forms.php.
 *
 */

defined( 'ABSPATH' ) || exit;
global $GHFApplications;

get_header('logged-in');

do_action('ghf_dashboard'); 
$user_last_login = get_user_meta(get_current_user_id(),'last_login',true); 
$formatted_date = ($user_last_login) ? 'Last Login ' . date('h:m:s',$user_last_login) . ',' . date('d-m-Y',$user_last_login) : '';
?>

<div class="general_page_header">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <h3 class="page-title">All Forms</h3><p class="page-description">All the forms available right now.</p>
        </div>
        <div class="col-lg-6">
            <div class="last_login_date text-end"><?php echo $formatted_date; ?>
            </div>
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
