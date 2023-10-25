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

<div class="general_page_header">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <?php echo '<h3 class="page-title">Create a new Application Form</h3><p>Create a new application form with multiple different fields and select action that it needs to do.</p>';
            ?>
        </div>
    </div>
</div>
<div class="page_content">
    <form id="form_details">
        <input type="text" name="form_name" placeholder="Form Name">
        <!-- <input type="text" name="form_description" placeholder="Form Description"> -->
    </form>
    <div id="form_builder"></div>
    <button id="create_form">Create Form</button>
</div>
<?php
// do_action('form_helper_instructions');
do_action('ghf_dashboard_end');

get_footer('logged-in');
