<?php

/**
 * The Template for displaying all avaialable forms.
 *
 * This template can be overridden by copying it to yourtheme/ghf_templates/forms/view_all_forms.php.
 *
 */

defined( 'ABSPATH' ) || exit;


get_header('logged-in');

do_action('ghf_dashboard'); 
global $GHFForms;
?>
<input type="hidden" id="form_id" value="<?php echo $form_id; ?>"/>
<div class="general_page_header">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <h3 class="page-title"><?php echo $form_name;  ?></h3>
        </div>
    </div>
</div>
<div class="page_content">
    <div class="form-wrapper">
        You have successfully submitted your application. Please wait until it is being reviewed, you will be notified soon.
    </div>
</div>
<?php
do_action('ghf_dashboard_end');

get_footer('logged-in');
