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
            <h3 class="page-title">All Applicants</h3>
            <!-- <p class="page-description">View all applicants.</p> -->
        </div>
    </div>
</div>
<div class="page_content">
    <div class="view_all_applicants_wrapper">
       	<?php
		$args = array(
				'role' => 'applicant',
				'orderby' => 'user_nicename',
				'order' => 'ASC',
			);
			$users = get_users($args);
			?>
			<script>
				jQuery(document).ready(function($){
					var dataTable = $('#applicants-table').DataTable({
						responsive: true,
						dom: 'Bfrtip',
						buttons: [
							'copy', 'csv', 'excel', 'pdf', 'print'
						]
					});		
				})
			</script>
			<table id="applicants-table">
				<thead>
					<th>Apply Date</th>
					<th>Name</th>
					<th>Email</th>
					<th>Type</th>
					<th>Action</th>
				</thead>
				<tbody>
					<?php
					// echo '<pre>';
					// print_r($users);exit;
					
					$serial = 1; foreach($users as $user): 
						$user_applications = $wpdb->get_results('SELECT * FROM ghf_application_submissions WHERE `submitted_by` = '.$user->ID,OBJECT);
						if(empty($user_applications))
							continue;
						$formDetails 	=	json_decode($user_applications[0]->submission_details);
					?>
						<tr>
							<td><?php echo date('m-d-Y',strtotime($user_applications[0]->submission_created)); ?></td>
							<td><?php echo $user->display_name; ?></td>
							<td><?php echo $user->user_email; ?></td>
							<td><?php echo $formDetails->form_name; ?></td>
							<td>
								<select class="form-control applicationSelectAction" id="">
									<option>Action</option>
									<option value="<?php echo site_url('?view_submission='.$user_applications[0]->submissions_id); ?>">View</option>
									<option value="<?php echo site_url('?edit_submission='.$user_applications[0]->submissions_id); ?>">Edit</option>
									<option value="<?php echo site_url('?view_submission='.$user_applications[0]->submissions_id); ?>">Remove</option>
									<option value="<?php echo site_url('?view_submission='.$user_applications[0]->submissions_id); ?>">Forward</option>
									<option value="<?php echo site_url('?print_submission='.$user_applications[0]->submissions_id); ?>">Download/Print</option>
								</select>
								<!-- <a href="<?php echo site_url('?appId='.$user->ID); ?>" class=remove_user ">View Application</a> -->
						</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
    </div>
</div>
<?php
do_action('ghf_dashboard_end');

get_footer('logged-in');
