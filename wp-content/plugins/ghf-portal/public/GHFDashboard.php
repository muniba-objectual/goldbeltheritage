<?php 
namespace FrontEnd; 

use Includes\GHFHelper;
use Includes\GHFSubmissions;



defined('ABSPATH') || exit; 

if(!class_exists('GHFDashboard')){
    class GHFDashboard{
        public function __construct(){
            
            add_action('pre_render_query',[$this,'ghf_render_form'],10,1);
            add_action('pre_render_query',[$this,'ghf_view_or_edit_form'],20,1);
            add_action('pre_render_query',[$this,'ghf_view_submission'],10,1);
            add_action('pre_render_query',[$this,'ghf_edit_submission'],10,1);
            add_action('pre_render_query',[$this,'ghf_open_pdf'],10,1);
			
			add_action('form_helper_instructions',[$this,'ghf_render_form_helper_instructions']);
        }

        /** 
         * ghf_view_or_edit_form
         */
        public function ghf_view_or_edit_form($form_id){
            global $GHFApplications,$wp_query;
            if(isset($_GET['edit_form']) && !GHFHelper::ghf_is_admin(get_current_user_id())){
                    wp_safe_redirect(home_url());
            }
            $form_id = (isset($_GET['edit_form'])) ? $_GET['edit_form'] : false;
            if($form_id && !$GHFApplications->ghf_does_form_exist($form_id)){
                $wp_query->set_404();
                status_header( 404 );
                get_template_part( 404 ); 
                exit();
            }
            if($form_id){
                $edit_form_template = file_exists(dirname(__DIR__) . '/admin/views/ghf_edit_form.php') ? dirname(__DIR__) . '/admin/views/ghf_edit_form.php' : false;
                $form = $GHFApplications->ghf_form($form_id);
                $form_details = json_decode($form->form_details,true);
                $form_fields = $form->form_fields;
                $form_id =  $form->form_id;
                $form_name = $form_details[0]['value'];
                $form_desc = $form_details[1]['value'];
                if($edit_form_template){
                    require($edit_form_template);
                    exit;
                }
            }
            return;
            
        }
        /** 
         * GHF Render Form
         */
        public function ghf_render_form(){
            global $GHFApplications;
            $form_id = (isset($_GET['view_form']))?$_GET['view_form']:false;
            
            if($form_id){
                if(!GHFHelper::ghf_is_applicant(get_current_user_id())){
                    wp_safe_redirect(home_url());
                }
                $template_for_applications = 'templates/forms/view_form_single.php';
                $form = $GHFApplications->ghf_form($form_id);
                $form_details = json_decode($form->form_details,true);
                $form_id =  $form->form_id;
                $form_name = $form_details[0]['value'];
                $form_desc = $form_details[1]['value'];
                $template = '';
                if($GHFApplications->ghf_has_submitted_already(get_current_user_id(),$form_id)){
                   $template = (locate_template('ghf_' . 'templates/forms/ghf-already-submitted.php')) ? locate_template('ghf_' . 'templates/forms/already-submitted.php') : dirname(__DIR__) . '/templates/forms/already-submitted.php';
                   require($template);
                   exit;
                }
                if(locate_template( 'ghf_' . $template_for_applications)){
                    $template = locate_template('ghf_' . $template_for_applications);
                }else{
                    $template = dirname(__DIR__) . '/' .  $template_for_applications;
                }
                require($template);
                exit;
            }
            return;
        }


        /** 
         * GHF View Submission
         */
        public function ghf_view_submission($submission_id){
            global $GHFApplications;
            $submission_id = (isset($_GET['view_submission'])) ? $_GET['view_submission'] : false;
            // check if submission exists.
            $submission_exists = $GHFApplications->submissions_exists($submission_id);
            if($submission_id && !$submission_exists){
                wp_safe_redirect(home_url());
            }
            if($submission_id){
                if(!GHFHelper::ghf_is_admin(get_current_user_id())){
                    wp_safe_redirect(home_url());
                }
                $view_single_submission_template = file_exists(dirname(__DIR__) . '/admin/views/view_single_submission.php') ? dirname(__DIR__) . '/admin/views/view_single_submission.php' : false;
                if($view_single_submission_template){
                    $submission = GHFSubmissions::ghf_get_submission_details($submission_id);
                    $submission_details = json_decode($submission[0]->submission_details,true);
                    $form_fields = $GHFApplications->ghf_form($submission[0]->application_id)->form_fields;
                    require($view_single_submission_template);
                    exit;
                }
            }
        }
        /** 
         * GHF Edit Submission
         */
        public function ghf_edit_submission($submission_id){
            global $GHFApplications;
            $submission_id = (isset($_GET['edit_submission'])) ? $_GET['edit_submission'] : false;
            // echo $submission_id;exit;
            // check if submission exists.
            $submission_exists = $GHFApplications->submissions_exists($submission_id);
            if($submission_id && !$submission_exists){
                wp_safe_redirect(home_url());
            }
            if($submission_id){
                if(!GHFHelper::ghf_is_admin(get_current_user_id())){
                    wp_safe_redirect(home_url());
                }
                $view_single_submission_template = file_exists(dirname(__DIR__) . '/admin/views/edit_single_submission.php') ? dirname(__DIR__) . '/admin/views/edit_single_submission.php' : false;
                if($view_single_submission_template){
                    $submission = GHFSubmissions::ghf_get_submission_details($submission_id);
                    $submission_details = json_decode($submission[0]->submission_details,true);
                    $form_fields = $GHFApplications->ghf_form($submission[0]->application_id)->form_fields;
                    require($view_single_submission_template);
                    exit;
                }
            }
        }
        
        
        /** 
         * GHF Open PDF
         */
        public function ghf_open_pdf($submission_id){
            global $GHFApplications,$GHFForms;
            $submission_id = (isset($_GET['print_submission'])) ? $_GET['print_submission'] : false;
            if($submission_id){
                if(!GHFHelper::ghf_is_admin(get_current_user_id())){
                    wp_safe_redirect(home_url());
                }
                $submission = GHFSubmissions::ghf_get_submission_details($submission_id);
                $submission_details = json_decode($submission[0]->submission_details,true);
                $form_fields = json_decode($GHFApplications->ghf_form($submission[0]->application_id)->form_fields);
                $styleingPdf    =   '<style>';
                $styleingPdf    .=   '
                .field-wrap {
                    width: 90%;
                    border: 1px solid #D9D9D9;
                    padding: 20px !important;
                    background: #F7F7F7;
                    margin: auto;
                    display: flex;
                    flex-wrap: wrap;
                }
                .ghf-field {
                    display: inline-grid;
                    margin-bottom: 0px;
                    grid-gap: 5px;
                    margin-right: 12px;
                    background: none;
                    border: none;
                    margin-top: 10px;
                    width: 24%;
                }
                .ghf-label {
                    font-weight: 600;
                    font-size: 14px;
                }
                .ghf-value{
                    color: #666;
                    border: 1px solid #ccc;
                    border-radius: 3px;
                    padding: 3px;
                    height: 40px;
                    font-size: 15px;
                    margin: 0px;
                }
                ';
                $styleingPdf    .=   '</style>';
                $pdfTable   =   '<div class="field-wrap">';
                $index  =   1;
                foreach($form_fields as $field){
                    if(isset($field->name)){
                        $pdfTable .= '<div class="ghf-field">';
                        $pdfTable .= '<p class="ghf-label">'.$field->label.'</p>';
                        $pdfTable .= '<p class="ghf-value">'.$submission_details[$field->name].'</p>';
                        $pdfTable .= '</div>';
                    }else{
                        $pdfTable .= '<div class="ghf-field" style="width: 100%;border-bottom: 1px solid;">';
                        $pdfTable .= '<p class="ghf-label">'.$field->label.'</p>';
                        $pdfTable .= '</div>';
                    }
                    $index++;
                }
                $pdfTable .= '</div>';
                $pdfTable .= $styleingPdf;
                $dompdf = new \Dompdf\Dompdf();
                try{
                    $dompdf->loadHtml($pdfTable);
                    $dompdf->setPaper('A4', 'landscape');
                    $dompdf->render();
                    $dompdf->stream();
                }catch(Exception $e){
                    print_r($e);
                }
                exit;
            }
        }
		
		/** 
		 * GHF Form Create Helper Instructions 
		 * */
		public function ghf_render_form_helper_instructions(){
			?>
				<div class="form_instructions">
					<h3>Form Helper Classes & Instructions</h3>
					<table>
						<thead>
							<tr>
								<td>Description</td>
								<td>Class</td>
							</tr>
							
						</thead>
						<tbody>
								<tr>
									<td>Text Color </td>
									<td>"text-color-{color}" 
										<br>
										<strong>Colors allowed</strong>: red,yellow,green,blue,white <br>
										<strong>eg: text-color-red</strong> will turn all the text red
									</td>
								</tr>
								<tr>
									<td>Background Color </td>
									<td>"bg-color-{color}" 
										<br>
										<strong>Colors allowed</strong>: default,black,green,red <br>
										<strong>eg: bg-color-red</strong> will turn all the text background red
									</td>
								</tr>
								<tr>
									<td>Font Style </td>
									<td>"text-style-{style}" 
										<br>
										<strong>Styles allowed</strong>: italic,bold,underline,strikethrough <br>
										<strong>eg: text-style-bold</strong> will turn all the text bold
									</td>
								</tr>
							<tr>
									<td>Where to put classes</td>
									<td> 
										<img src="https://mygoldbeltheritage.org/wp-content/uploads/2022/03/helper-img.png" alt="classes-helper" />
									</td>
								</tr>
							</tbody>
					</table>
				</div>
			<?php
		}
    }
}