<?php
/**
 * The Template for displaying all avaialable forms.
 *
 * This template can be overridden by copying it to yourtheme/ghf_templates/forms/view_all_forms.php.
 *
 */

defined( 'ABSPATH' ) || exit;
global $GHFForms;

get_header('logged-in');

do_action('ghf_dashboard'); 

$fields = array(
    array(
        'field_name' => 'first_name',
        'label' => 'First Name',
        'placeholder' => 'Enter your First Name',
        'class' => 'form-control',
        'id' => 'first_name',
        'required' => true,
        'input_type' => 'text',
    ),
    array(
        'field_name' => 'last_name',
        'label' => 'Last Name',
        'placeholder' => 'Enter your Last Name',
        'class' => 'form-control',
        'id' => 'first_name',
        'required' => true,
        'input_type' => 'text',
    ),
);
?>

<div class="general_page_header">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <?php echo '<h3 class="page-title">Edit Profile</h3><p class="page-description">View and Edit your profile details.</p>';
            ?>
        </div>
    </div>
</div>
<div class="page_content">
    <?php $GHFForms->createForm('edit_profile_form','edit_profile_form',$fields,true,'Save Profile Settings',true); ?>
</div>
<?php
do_action('ghf_dashboard_end');

get_footer('logged-in');
