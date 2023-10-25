<?php 

namespace Includes;

defined('ABSPATH') || exit; 

if(!class_exists('GHFSubmissions')){
    class GHFSubmissions{
        public function __construct(){
            add_action('view_all_submissions',[$this,'ghf_show_submissions_tabs'],10);
            add_action('view_all_submissions',[$this,'ghf_show_submissions_tabs_content'],20);

        }
        
        /** 
         * Get Submissions Tabs -- for Admin 
         * @param 
         * @return 
         * @author
         */
        public function ghf_show_submissions_tabs(){
            global $wpdb;


            // if(isset($_GET['appId'])){
            //     if($_GET['appId'] > 0){
            //         $application_forms = $wpdb->get_results('SELECT * FROM ghf_application_submissions WHERE `submitted_by` = '.$_GET['appId'],OBJECT);
            //     }else{
            //         $application_forms = $wpdb->get_results('SELECT * FROM ghf_application_forms ORDER BY form_created DESC');
            //     }
            // }else{
            //     $application_forms = $wpdb->get_results('SELECT * FROM ghf_application_forms ORDER BY form_created DESC');
            // }


            $application_forms = $wpdb->get_results('SELECT * FROM ghf_application_forms ORDER BY form_created DESC');
            if($application_forms){
                echo '<nav><div class="nav nav-tabs" id="nav-tab" role="tablist">';
                foreach($application_forms as $key => $application_form){
                    $selected = '';
                    if($key == 0) $selected = 'active';
                    $form_name = json_decode($application_form->form_details)[0]->value;
                    echo '<button class="nav-link '.$selected.' my-2" id="nav-'.$application_form->form_id.'-tab" data-bs-toggle="tab" data-bs-target="#nav-'.$application_form->form_id.'" type="button" role="tab" aria-controls="nav-'.$form_name.'" aria-selected="true">'.$form_name.'</button>';
                }
                echo '</div></nav>';
            }
        }
        
        /** 
         * Get Submissions Tabs -- for Admin 
         * @param 
         * @return 
         * @author
         */
        public function ghf_show_submissions_tabs_content(){
            global $wpdb;
            $application_forms = $wpdb->get_results('SELECT * FROM ghf_application_forms ORDER BY form_created DESC');
            if($application_forms){
                echo '<div class="tab-content" id="application_submissions">';
                foreach($application_forms as $key => $application_form){
                    $selected = '';
                    if($key == 0) $selected = 'show active';
                    $form_name = json_decode($application_form->form_details)[0]->value;
                    echo '<div class="tab-pane fade '.$selected.'" id="nav-'.$application_form->form_id.'" role="tabpanel" aria-labelledby="'.$application_form->form_id.'-tab">'.$this->renderTabContent($application_form->form_id).'</div>';
                }
                echo '</div>';
            }
        }

        /** 
         * Render Tab Content
         */
        public function renderTabContent($id){
            global $wpdb;
            ob_start();
            $application_submissions = $wpdb->get_results('SELECT * FROM ghf_application_submissions WHERE application_id='.$id.' AND status=1 ORDER BY submission_created DESC'); ?>
			<script>
				jQuery(document).ready(function($){
					var dataTable = $('#submissions_table-<?php echo $id; ?>').DataTable({
						responsive: true,
						dom: 'Bfrtip',
						buttons: [
							'copy', 'csv', 'excel', 'pdf', 'print'
						]
					});		
				})
			</script>
            <table id="submissions_table-<?php echo $id; ?>" class="display">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Date</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                   <?php
                    if($application_submissions){
                        foreach($application_submissions as $submission){
                            $user = get_user_by('id',$submission->submitted_by);
                            echo '
                                <tr>
                                    <td>'.$user->display_name.'</td>
                                    <td>'.date('m-d-Y, h:m a',strtotime($submission->submission_created)).'</td>
                                    <td>
                                        <select class="form-control applicationSelectAction" id="">
                                            <option>Action</option>
                                            <option value="?view_submission='.$submission->submissions_id.'">View</option>
                                            <option value="?edit_submission='.$submission->submissions_id.'">Edit</option>
                                            <option value="'.$submission->submissions_id.'">Remove</option>
                                            <option value="?view_submission='.$submission->submissions_id.'">Email</option>
                                            <option value="?print_submission='.$submission->submissions_id.'">Download/Print</option>
								        </select>
                                    </td>
                                </tr>
                            ';
                        }
                    }
                   ?>
                </tbody>
            </table>
			<?php
            return ob_get_clean();
        }


        /** 
         * Get Submission Details by submission id
         * @param int $submission_id 
         * 
         */
        public static function ghf_get_submission_details($submission_id){
            global $wpdb;
            $application_submission_details = $wpdb->get_results('SELECT * FROM ghf_application_submissions WHERE submissions_id='.$submission_id.' AND status=1 ORDER BY submission_created DESC');
            if($application_submission_details){
                return $application_submission_details;
            }
            return false;
        }

    }
}