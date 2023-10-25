<?php
/**
 * The Template for viewing single submission
 * 
 * This template can be overridden by copying it to yourtheme/ghf_templates/applications/view_all_submissions.php.
 *
 */
global $GHFForms,$GHFApplications,$GHFUser;
defined( 'ABSPATH' ) || exit;

get_header('logged-in');

do_action('ghf_dashboard'); 
// $user   =    $GHFUser::editProfile();
$user = $GHFApplications->editProfile($_GET['uid']);

echo '<pre>';var_dump($user);exit;
?>
<input type="hidden" id="form_values" value='<?php // echo $GHFDashboard->editProfile(2); ?>' />
<div class="general_page_header">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <h3 class="page-title">Edit Submission #<?php //echo $submission_id; ?></h3>
        </div>
    </div>
</div>

<div class="page_content">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-wrapper">
                <form class="" action="#" method="POST">
                    <div class="ghf-field  ">
                        <label>First Name</label>
                        <input type="text" disabled >
                    </div>
                    <div class="ghf-field  ">
                        <label>Last Name</label>
                        <input type="text" disabled>
                    </div>
                    <div class="ghf-field  ">
                        <label>Email Address</label>
                        <input type="text" disabled>
                    </div>
                    <div class="ghf-field  ">
                        <label>Phone Number</label>
                        <input type="text" disabled>
                    </div>
                    <div class="ghf-field  ">
                        <label>Password</label>
                        <input type="text" disabled>
                    </div>
                    <div class="ghf-field  ">
                        <label>Current Password</label>
                        <input type="text" disabled>
                    </div>
                    <input type="hidden" name="form_name" value="Application Second Form">
                    <input type="hidden" name="sub_id" value="4">
                    <input type="hidden" name="to_update_form_id" value="2">
                    <input type="hidden" name="ghf_2_nonce_" value="c067526962">
                    <button class="2-submit-btn submit-form " type="submit">
                        <span class="text">Submit</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
do_action('ghf_dashboard_end');

get_footer('logged-in');
