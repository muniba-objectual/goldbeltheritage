<?php
/**
 * The Template for viewing single submission
 * 
 * This template can be overridden by copying it to yourtheme/ghf_templates/applications/view_all_submissions.php.
 *
 */
global $GHFForms,$GHFApplications;
defined( 'ABSPATH' ) || exit;

get_header('logged-in');

do_action('ghf_dashboard'); 
?>
<input type="hidden" id="form_values" value='<?php echo $GHFApplications->ghf_form_values($_GET['view_submission'])->submission_details; ?>' />
<div class="general_page_header">
    <div class="row align-items-center">
        <?php if(isset($_GET['sent'])){ ?>
            <div class="col-lg-12 bg-success text-white p-3 mb-2">
                Application Forwarded Successfully!
            </div>
        <?php } ?>
        <div class="col-lg-6">
            <h3 class="page-title">View Submission #<?php echo $submission_id; ?></h3>
        </div>
        <div class="col-lg-6 text-end">
            <button class="btn approve_submission btn-success">Forward</button>
            <!-- <button class="btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Reject</button> -->
            <a href="?print_submission=<?php echo $_GET['view_submission'] ?>" class="btn btn-success text-white" target="__blank">Download/Print PDF</a>
        </div>
        <div class="offset-lg-6 float-right col-lg-6 text-end forward-form" style="display:none;">
            <form action="?view_submission=<?php echo $_GET['view_submission'];?>&sent=1" method="POST">
                <input type="text" name="applicant_email" placeholder="Enter Email Address" id="user_login" class="form-control">
                <input type="submit" class="login_form-submit-btn send-submission" value="Send">
            </form>
            <?php
            if(isset($_POST['applicant_email'])){
                do_shortcode('[ghf_pdf_application_send_email]');
            }
            ?>
        </div>
    </div>
</div>

<div class="page_content">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-wrapper">
                <?php $GHFForms->renderForm([],json_decode($form_fields,true),false); ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="staticBackdrop" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Reject Application Submission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               <label>Reasons of Rejection</label>
               <textarea name="reasons_of_rejection" cols="30" rows="10"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary">Submit Feedback</button>
            </div>
        </div>
    </div>
</div>
<?php
do_action('ghf_dashboard_end');

get_footer('logged-in');
