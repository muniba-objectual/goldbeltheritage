<?php 

namespace Includes;
use Includes\GHFUser;

defined('ABSPATH') || exit; 

if(!class_exists('GHFAjax')){
    class GHFAjax{

        public $ajax_actions = array(
            'ghf_user_login',
            'ghf_user_register',
            'ghf_save_form_data',
            'ghf_submit_application',
            'ghf_remove_application_form',
            'ghf_pause_application_form',
            'ghf_activate_application_form',
            'ghf_inactive_application',
            'ghf_save_profile_settings',
            'ghf_upload_files',
			'ghf_render_pdf'
        );
        
        public function __construct(){
            foreach($this->ajax_actions as $ajax_action){
                add_action('wp_ajax_' . $ajax_action,array($this,$ajax_action));
                add_action('wp_ajax_nopriv_' . $ajax_action,array($this,$ajax_action));
            }
        }

        /** 
         * GHF Upload 
         */
        public function ghf_upload_files(){
            $post_id = $_REQUEST['post_id'];
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			$length = sizeof($_FILES['file']['name']);
			$attachment_ids = [];
			for ($i=0; $i < $length; $i++) { 
				$file_name = $_FILES['file']['name'][$i];
				$file_type = wp_check_filetype($file_name);
				move_uploaded_file($_FILES['file']['tmp_name'][$i],wp_get_upload_dir()['basedir'] . '/'  .'user-' . get_current_user_id() . '/'  . md5($_FILES['file']['name'][$i] . $_FILES['file']['tmp_name'][$i]) . '.'. $file_type['ext']);
                $upload_dir = wp_upload_dir();
				$attachment_args = array(
					'guid' => wp_get_upload_dir()['basedir'] . '/' . md5($_FILES['file']['name'][$i] . $_FILES['file']['tmp_name'][$i]) . '.'. $file_type['ext'],
					'post_title' => sanitize_text_field($_FILES['file']['name'][$i]),
					'post_excerpt' => sanitize_text_field($_FILES['file']['name'][$i]),
					'post_content' => sanitize_text_field($_FILES['file']['name'][$i]),
					'post_mime_type' => $_FILES['file']['type'][$i],
				);
				$attachment_ids[] = $attachment_id = wp_insert_attachment($attachment_args,$_FILES['file']['name'][$i],$post_id,true);
				$file = wp_get_upload_dir()['basedir'] . '/' . md5($_FILES['file']['name'][$i] . $_FILES['file']['tmp_name'][$i]) . '.'. $file_type['ext'];
				update_post_meta($attachment_id,'file_encrypted_name', md5($_FILES['file']['name'][$i] . $_FILES['file']['tmp_name'][$i]). '.' .$file_type['ext']);
                $attachment_urls[] = wp_get_upload_dir()['baseurl'] . '/' . 'user-' . get_current_user_id() . '/'  .get_post_meta($attachment_id,'file_encrypted_name',true);
			}
            wp_send_json_success($attachment_urls);
            wp_die();
        }


        /** 
         * GHF User Registration
         */
        public function ghf_user_register(){
            parse_str($_POST['formData'],$data);
            $verify_nonce = wp_verify_nonce($data['ghf_form-registration_nonce_'],'ghf_form-registration_nonce_');
            if($verify_nonce){
                $response = GHFUser::ghf_user_registration($data);   
                wp_send_json($response);
            }
            wp_die();
        }
        
        /** 
         * GHF User Login
         */
        public function ghf_user_login(){
            parse_str($_POST['formData'],$data);
            $verify_nonce = wp_verify_nonce($data['ghf_form-login_nonce_'],'ghf_form-login_nonce_');
            if($verify_nonce){
                GHFUser::ghf_log_user_in($data);
            }
            wp_die();
        }
        
        /** 
         * GHF User Login
         */
        public function ghf_save_form_data(){
            global $GHFApplications;
            $data = $GHFApplications->ghf_create_form_application($_POST['formData']);
            if($data){
                wp_send_json_success($data);
            }
            wp_die();
        }

        /** 
         * GHF User Login
         */
        public function ghf_submit_application(){
            global $GHFApplications;
            parse_str($_POST['formData'],$data);
            $data = $GHFApplications->ghf_submit_form($_POST['formData']);
            if($data){
                wp_send_json_success($data);
            }
            wp_die();
        }
        
        /** 
         * GHF User Login
         */
        public function ghf_remove_application_form(){
            global $GHFApplications;
            parse_str($_POST['formData'],$data);
            $verify_nonce = wp_verify_nonce($data['nonce'],'ghf_' . $data['form_id'] . '_nonce');
            if($verify_nonce){
                $delete = $GHFApplications->ghf_delete_form($data['form_id']);
                if($delete) wp_send_json_success('Deleted');
            }
            wp_die();
        }
        
        /** 
         * GHF Pause Form
         */
        public function ghf_pause_application_form(){
            global $GHFApplications;
            parse_str($_POST['formData'],$data);
            $verify_nonce = wp_verify_nonce($data['nonce'],'ghf_' . $data['form_id'] . '_nonce');
            if($verify_nonce){
                $delete = $GHFApplications->ghf_pause_form($data['form_id']);
                if($delete) wp_send_json_success('Deleted');
            }
            wp_die();
        }
        /** 
         * GHF Activate Form
         */
        public function ghf_activate_application_form(){
            global $GHFApplications;
            parse_str($_POST['formData'],$data);
            $verify_nonce = wp_verify_nonce($data['nonce'],'ghf_' . $data['form_id'] . '_nonce');
            if($verify_nonce){
                $delete = $GHFApplications->ghf_activate_form($data['form_id']);
                if($delete) wp_send_json_success('Deleted');
            }
            wp_die();
        }
        /** 
         * GHF DeActivate Submission
         */
        public function ghf_inactive_application(){
            global $GHFApplications;
            parse_str($_POST['formData'],$data);
            // var_dump($data['submissionId']);exit;
            // $verify_nonce = wp_verify_nonce($data['nonce'],'ghf_' . $data['submissionId'] . '_nonce');
            // if($verify_nonce){
                $delete = $GHFApplications->ghf_inactivate_submission($data['submissionId']);
                if($delete) wp_send_json_success('Deleted');
            // }
            wp_die();
        }

        /** 
         * GHF Save Profile Settings
         */
        public function ghf_save_profile_settings(){
            global $GHFApplications;
            parse_str($_POST['formData'],$data);
            $verify_nonce = wp_verify_nonce($data['ghf_edit_profile_form_nonce_'],'ghf_edit_profile_form_nonce_');
            if($verify_nonce){
                wp_send_json_success('done');
            }
            wp_die();
        }
		
		
		/** 
		 * GHF Render PDF 
		 * /
		 */
		public function ghf_render_pdf(){
            try{
				$dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($_POST['pdfData']);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();
				$dompdf->stream("pdf_filename_".rand(10,1000).".pdf", array("Attachment" => true));
			}catch(Exception $e){
				throw new \WP_Error('Couldnt Render PDF: ' . $e);
			}		
		}
    }
}