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
global $GHFForms,$GHFApplications;
// print_r(json_decode($form->form_fields,true));

?>
<input type="hidden" id="form_values" value='<?php echo ($GHFApplications->ghf_form_values($form_id)) ? $GHFApplications->ghf_form_values($form_id)->submission_details : '' ?>'/>
<input type="hidden" id="form_id" value="<?php echo $form_id; ?>"/>
<input type="hidden" id="form_name" value="<?php echo $form_name; ?>"/>
<div class="general_page_header">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <h3 class="page-title"><?php echo $form_name;  ?></h3><p class="page-description"><?php  echo $form_desc; ?></p>
        </div>
    </div>
</div>
<div class="page_content">
    <div class="form-wrapper">
        <?php $GHFForms->renderForm(json_decode($form->form_details,true),json_decode($form->form_fields,true),true); ?>
    </div>
</div>
<?php
do_action('ghf_dashboard_end');

get_footer('logged-in');
