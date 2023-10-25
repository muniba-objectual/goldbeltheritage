<?php 

namespace Includes; 

use Includes\GHFHelper;
use Includes\GHFMailer;

defined('ABSPATH') || exit; 

if(!class_exists('GHFApplications')){
    class GHFApplications{
        
        private static $instance;
		public static function getInstance() {
			if ( ! self::$instance instanceof self ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

        public function __construct(){
            add_action('init',[$this,'ghf_applications_create_table']);
            add_action('init',[$this,'ghf_applications_submission_create_table']);
            add_shortcode('ghf_pdf_application_send_email',[$this,'ghf_pdf_application_send_email']);
        }

        /** 
         * Applications Table
         */
        public function ghf_applications_create_table(){
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
            // $sql             = 'ALTER TABLE ghf_application_forms ADD 
            //     availability int(9) DEFAULT 1 ';
			// $asdf = $wpdb->query( $sql );
            // var_dump($asdf);exit;
			$sql             = 'CREATE TABLE IF NOT EXISTS ghf_application_forms(
                form_id int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                form_details LONGTEXT,
                form_fields LONGTEXT,
                form_submissions VARCHAR(255),
                status int(9) DEFAULT 1,
                form_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_on TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )' . $charset_collate . ';';
			dbDelta( $sql );
        }
        
        /** 
         * Applications Submissions Table
         */
        public function ghf_applications_submission_create_table(){
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
			$sql             = 'CREATE TABLE IF NOT EXISTS ghf_application_submissions(
                submissions_id int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                application_id LONGTEXT,
                submitted_by int(9),
                submission_details LONGTEXT,
                submission_approved BOOLEAN,
                submission_rejection_comments LONGTEXT,
                submission_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )' . $charset_collate . ';';
			dbDelta( $sql );
        }

        /** 
         * GHF - Check if Form exists
         * @param int @form_id
         * @return bool
         */
        public function ghf_does_form_exist($form_id){
            global $wpdb;
            $form = $wpdb->get_var($wpdb->prepare('SELECT * FROM ghf_application_forms WHERE form_id=%d',array($form_id)));
            if($form){
                return true;
            }
            return false;
        } 
        
        /** 
         * GHF - Get form by id
         * @param int @form_id
         * @return bool
         */
        public static function ghf_form($form_id){
            global $wpdb;
            $form = $wpdb->get_row($wpdb->prepare('SELECT * FROM ghf_application_forms WHERE form_id=%d',array($form_id)));
            if($form){
                return $form;
            }
            return false;
        } 
        
        /** 
         * GHF - Get form values by id
         * @param int @form_id
         * @return bool
         */
        public function ghf_form_values($submission_id){
            global $wpdb;
            $formValues = $wpdb->get_row($wpdb->prepare('SELECT * FROM ghf_application_submissions WHERE submissions_id=%d',array($submission_id)));
            // var_dump($formValues);exit;
            if($formValues){
                return $formValues;
            }
            return false;
        } 
        /** 
         * GHF - Get User by id
         * @param int @form_id
         * @return bool
         */
        public function editProfile($user_id){
            return get_user_by('id', $user_id);
        }
        
        /** 
         * GHF - Get form by id
         * @param int @form_id
         * @return bool
         */
        public function ghf_has_submitted_already($user_id,$application_id){
            global $wpdb;
            $hasSubmitted = $wpdb->get_row($wpdb->prepare('SELECT * FROM ghf_application_submissions WHERE application_id=%d AND submitted_by=%d AND submission_approved=%d',array($application_id,$user_id,0)));
            if($hasSubmitted){
                return true;
            }
            return false;
        } 

        /** 
         * GHF Show all available applications 
         *  
         */
        public function ghf_all_available_applications(){
            global $wpdb;
            $application_forms = $wpdb->get_results('SELECT * FROM ghf_application_forms WHERE status = 1 ORDER BY form_created DESC');
            $template_for_applications = file_exists(dirname(__DIR__) . '/templates/available_application_single.php') ? dirname(__DIR__) . '/templates/available_application_single.php' : false;
            $template_for_zero_application = file_exists(dirname(__DIR__) . '/templates/forms/no_form_available.php') ? dirname(__DIR__) . '/templates/forms/no_form_available.php' : false;
            if($application_forms){
                foreach($application_forms as $application_form){
                    $form_details = json_decode($application_form->form_details,true);
                    $form_id =  $application_form->form_id;
                    $form_name = $form_details[0]['value'];
                    $form_desc = $form_details[1]['value'];
                    $form_date  =   $application_form->form_created;
                    // var_dump($this->ghf_is_form_active($form_id));
                    $isActive   =   $this->ghf_is_form_active($form_id);
                    $statusLinks    =   ($this->ghf_is_form_active($form_id)) ? '<a href="javascript:void(0)" class="pause_application_form" data-form-id="'.$form_id.'">Pause</a>' : '<a href="javascript:void(0)" class="activate_application_form" data-form-id="'.$form_id.'">Activate</a>';
                    $form_url = (GHFHelper::ghf_is_admin(get_current_user_id())) ? '<a href="?edit_form='.$form_id.'">Edit</a><a href="javascript:void(0)" class="remove_application_form" data-form-id="'.$form_id.'">Remove</a><input type="hidden" name="ghf_'.$form_id.'_nonce" value="'.wp_create_nonce("ghf_${form_id}_nonce").'"/>'.$statusLinks : '<a href="?view_form='.$form_id.'">View</a>' ; 
                    require($template_for_applications);
                }
            }else{
                require($template_for_zero_application);
            }
        }

        /** 
         * Create Form Application
         */
        public function ghf_create_form_application($data){
            global $wpdb;
            $form_details = $data['form_details'];
            $form_fields = $data['form_fields'];
            $form_id = (int)$data['formId'];
            $id = 0;
            if($data['formId'] == 0){
                $id = $wpdb->query($wpdb->prepare('INSERT INTO ghf_application_forms (form_details,form_fields,form_submissions) VALUES (%s,%s,%s)',array(json_encode($form_details,JSON_UNESCAPED_SLASHES),json_encode($form_fields,JSON_UNESCAPED_SLASHES),0)));
            }else{
                $id = $wpdb->query($wpdb->prepare('UPDATE ghf_application_forms SET form_details=%s,form_fields=%s,updated_on=CURRENT_TIMESTAMP WHERE form_id=%d',array(json_encode($form_details,JSON_UNESCAPED_SLASHES),json_encode($form_fields,JSON_UNESCAPED_SLASHES),$form_id)));
            }
            if($id){
                return $wpdb->insert_id;
            }else{
                echo '<pre>';
                print_r($wpdb->print_error());
                echo '</pre>';
            }
        }

        /** 
         * Submit Form
         * @param 
         * @return
         * @author
         */
        public function ghf_submit_form($submit_data){
            global $wpdb;
            parse_str($submit_data,$data);
            unset($data['ghf_youth_camp_application_form_nonce_']);
            try {
                $submission = $wpdb->query($wpdb->prepare('INSERT INTO ghf_application_submissions (application_id,submitted_by,submission_details,submission_approved,submission_rejection_comments) VALUES (%d,%d,%s,%d,%s)',array($data['form_id'],get_current_user_id(),json_encode($data),0,'')));
                $text = wp_get_current_user()->display_name . ' has submitted the ' . $data['form_name'];
                $sendNotification = $wpdb->query($wpdb->prepare('INSERT INTO ghf_notifications (notification_for,notification_status,notification_text) VALUES (%d,%s,%s)',array(1,'unread',$text)));
                $updateCount = $wpdb->query($wpdb->prepare('UPDATE ghf_application_forms SET form_submissions = form_submissions + %d WHERE form_id=%d',array(1,$data['form_id'])));
                if($submission){
					$mail_to = array(get_option('admin_email') => 'Admin GHF Portal');
					$message = array(
						'subject' => 'Application Submitted for ' . $data['form_name'] . ' on GHF Applications Portal',
						'body' => wp_get_current_user()->display_name . ' has submitted ' . $data['form_name'] . '.<br>View Submission <a href="'.home_url().'?view_submission='.$wpdb->insert_id.'">here</a>.',
					);
					GHFMailer::ghf_send_mail($mail_to,null,$message);
							return $wpdb->insert_id;
						}
            } catch (\Throwable $th) {
                return $th->getMessage();
            }

        }
        /** 
         * Email pdf application to
         * @param 
         * @return
         * @author
         */
        public function ghf_pdf_application_send_email(){
            global $GHFForms;
            $submission_id = (isset($_GET['view_submission'])) ? $_GET['view_submission'] : false;
            $submission = GHFSubmissions::ghf_get_submission_details($submission_id);
            $submission_details = json_decode($submission[0]->submission_details,true);
            $form_fields = json_decode($this->ghf_form($submission[0]->application_id)->form_fields);
            // print_r($form_fields);exit;
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
                        $pdfTable .= '<p class="ghf-label" style="font-weight:700;">'.$field->label.'</p>';
                        $pdfTable .= '<p class="ghf-value">'.$submission_details[$field->name].'</p>';
                        $pdfTable .= '</div>';
                    }else{
                        $pdfTable .= '<div class="ghf-field" style="width: 100%;border-bottom: 1px solid;">';
                        $pdfTable .= '<p class="ghf-label" style="font-weight:700;">'.$field->label.'</p>';
                        $pdfTable .= '</div>';
                    }
                    $index++;
                }
                $pdfTable .= '</div>';
                $pdfTable .= $styleingPdf;
                // var_dump($pdfTable);return;
            // exit;
            
            try {
                $mail_to = [$_POST['applicant_email']  => 'Admin GHF Portal'];
                $message = array(
                    'subject' => 'Application Forwared From GHF Applications Portal',
                    'body' => $pdfTable,
                );
                GHFMailer::ghf_send_mail($mail_to,null,$message);
            } catch (\Throwable $th) {
                return $th->getMessage();
            }
            return;
            // var_dump($_POST['applicant_email']);
            global $wpdb;
            parse_str($submit_data,$data);
            unset($data['ghf_youth_camp_application_form_nonce_']);
            try {
                $submission = $wpdb->query($wpdb->prepare('INSERT INTO ghf_application_submissions (application_id,submitted_by,submission_details,submission_approved,submission_rejection_comments) VALUES (%d,%d,%s,%d,%s)',array($data['form_id'],get_current_user_id(),json_encode($data),0,'')));
                $text = wp_get_current_user()->display_name . ' has submitted the ' . $data['form_name'];
                $sendNotification = $wpdb->query($wpdb->prepare('INSERT INTO ghf_notifications (notification_for,notification_status,notification_text) VALUES (%d,%s,%s)',array(1,'unread',$text)));
                $updateCount = $wpdb->query($wpdb->prepare('UPDATE ghf_application_forms SET form_submissions = form_submissions + %d WHERE form_id=%d',array(1,$data['form_id'])));
                if($submission){
					$mail_to = array(get_option('admin_email') => 'Admin GHF Portal');
					$message = array(
						'subject' => 'Application Submitted for ' . $data['form_name'] . ' on GHF Applications Portal',
						'body' => wp_get_current_user()->display_name . ' has submitted ' . $data['form_name'] . '.<br>View Submission <a href="'.home_url().'?view_submission='.$wpdb->insert_id.'">here</a>.',
					);
					GHFMailer::ghf_send_mail($mail_to,null,$message);
							return $wpdb->insert_id;
						}
            } catch (\Throwable $th) {
                return $th->getMessage();
            }
        }


        /** 
         * Delete Form Application
         */
        public function ghf_delete_form(int $form_id){
            global $wpdb; 
            if($form_id == 0){
                return 'Invalid form ID';
            }
            $remove_application_form = $wpdb->query($wpdb->prepare('UPDATE ghf_application_forms SET status = 0 WHERE ghf_application_forms.form_id=%d',array($form_id)));
            // $remove_application_form = $wpdb->query($wpdb->prepare('DELETE FROM ghf_application_forms WHERE form_id=%d',array($form_id)));
            if($remove_application_form){
                return true;
            }
            return false;
        }
        /** 
         * Pause Form Application
         */
        public function ghf_pause_form(int $form_id){
            global $wpdb; 
            if($form_id == 0){
                return 'Invalid form ID';
            }
            $remove_application_form = $wpdb->query($wpdb->prepare('UPDATE ghf_application_forms SET availability = 0 WHERE ghf_application_forms.form_id=%d',array($form_id)));
            // $remove_application_form = $wpdb->query($wpdb->prepare('DELETE FROM ghf_application_forms WHERE form_id=%d',array($form_id)));
            if($remove_application_form){
                return true;
            }
            return false;
        }
        /** 
         * Activate Form Application
         */
        public function ghf_inactivate_submission(int $submission_id){
            global $wpdb; 
            if($submission_id == 0){
                return 'Invalid Submission ID';
            }
            $remove_application_form = $wpdb->query($wpdb->prepare('UPDATE ghf_application_submissions SET status = 0 WHERE ghf_application_submissions.submissions_id=%d',array($submission_id)));
            // $remove_application_form = $wpdb->query($wpdb->prepare('DELETE FROM ghf_application_forms WHERE form_id=%d',array($form_id)));
            if($remove_application_form){
                return true;
            }
            return false;
        }
        /** 
         * Activate Form Application
         */
        public function ghf_activate_form(int $form_id){
            global $wpdb; 
            if($form_id == 0){
                return 'Invalid form ID';
            }
            $remove_application_form = $wpdb->query($wpdb->prepare('UPDATE ghf_application_forms SET availability = 1 WHERE ghf_application_forms.form_id=%d',array($form_id)));
            // $remove_application_form = $wpdb->query($wpdb->prepare('DELETE FROM ghf_application_forms WHERE form_id=%d',array($form_id)));
            if($remove_application_form){
                return true;
            }
            return false;
        }
        /** 
         * Is Form Active
         */
        public function ghf_is_form_active(int $form_id){
            global $wpdb; 
            if($form_id == 0){
                return 'Invalid form ID';
            }
            $form_active = $wpdb->get_row($wpdb->prepare('SELECT * FROM ghf_application_forms  WHERE ghf_application_forms.form_id=%d',array($form_id)));
            if($form_active->availability == '1' ){
                return true;
            }else{
                return false;
            }
            return $form_active->availability;
            // $remove_application_form = $wpdb->query($wpdb->prepare('DELETE FROM ghf_application_forms WHERE form_id=%d',array($form_id)));
            if($remove_application_form){
                return true;
            }
            return false;
        }


        /** 
         * Submission Exists
         */
        public function submissions_exists($submission_id){
            global $wpdb;
            $submission_exists = $wpdb->get_row($wpdb->prepare('SELECT * FROM ghf_application_submissions WHERE submissions_id=%d',array($submission_id)));
            if($submission_exists){
                return true;
            }
            return false;
        }
		

        
    }
    $GLOBALS['GHFApplications'] = GHFApplications::getInstance();
}