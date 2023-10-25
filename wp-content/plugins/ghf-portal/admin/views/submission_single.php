<div class="submission-single" id="submission-single-<?php echo $submission->application_id;  ?>">
    <div class="row align-items-center">
        <div class="col-lg-1">
            <div class="user-avatar-image" style="background-image:url(<?php echo get_avatar_url($submission->submitted_by) ?>)"></div>
        </div>
        <div class="col-lg-8">
            <ul>
                <li><p>Applicant's Name</p><?php echo $user->display_name; ?></li>
                <li><p>Submitted on:</p><?php echo $submission->submission_created; ?></li>
            </ul>
        </div>
        <div class="col-lg-3 text-end">
            <a href="?view_submission=<?php echo $submission->submissions_id; ?>">View Submission</a>
        </div>
    </div>
</div>