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
					<th>Register Date</th>
					<th>Name</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Action</th>
				</thead>
				<tbody>
					<?php					
						foreach($users as $user): 
						$user_applications = $wpdb->get_results('SELECT * FROM ghf_application_submissions WHERE `submitted_by` = '.$user->ID,OBJECT);
						$numData = get_user_meta($user->ID,'applicant_phone',true);
						
					?>
						<tr>
							<td><?php echo date('m-d-Y',strtotime($user->user_registered)); ?></td>
							<td><?php echo $user->display_name; ?></td>
							<td><?php echo $user->user_email; ?></td>
							<td><?php echo substr($numData, -10, -7) . "-" . substr($numData, -7, -4) . "-" .substr($numData, -4,); ?></td>
							<td>
								<a href="<?php echo site_url('/edit_user?uid='.$user->ID); ?>" class="ghf_edit_user">Edit</a>
								<br>
								<a href="javascript:void(0)" class="remove_user">Remove</a>
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
