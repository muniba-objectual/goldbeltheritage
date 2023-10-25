<?php 

namespace Includes; 

use Includes\GHFMailer;

defined('ABSPATH') || exit; 

if(!class_exists('GHFUser')){
    class GHFUser{
		/** 
		 * Applicant Registration 
		 * @return bool
		 */
		public static function ghf_user_registration($user_details,$email = true){
			if ( is_user_logged_in() ) {
				return;
			}			
			$email    = $user_details['email'];
			$password = $user_details['password'];
			$userdata = array(
				'first_name' => sanitize_text_field( $user_details['first_name'] ),
				'last_name'  => sanitize_text_field( $user_details['last_name'] ),
				'user_login' => sanitize_text_field( $user_details['email'] ),
				'user_email' => sanitize_text_field( $user_details['email'] ),
				'applicant_phone' => sanitize_text_field( $user_details['applicant_phone'] ),
				'user_pass'  => sanitize_text_field( $user_details['password'] ),
				'role'       => 'applicant',
			);
			if(email_exists($email)){
				wp_send_json_error(array(
					'code' => 5,
					'message' => __('Applicant with this email address already exists, Please Login','ghf-portal'),
				));
				return;
			}
			$user_id = wp_insert_user($userdata);
			
			if ( is_wp_error( $user_id ) ) {
				wp_send_json_error(
					array(
						'code'    => 0,
						'message' => $user_id->get_error_message(),
					)
				);
				return;
			}
			// /** Mail the User now */
			if ( ! is_dir( ABSPATH . '/wp-content/uploads/user-' . $user_id ) ) {
				wp_mkdir_p( ABSPATH . '/wp-content/uploads/user-'. $user_id  );
			}
			update_user_meta($user_id,'applicant_phone',$userdata['applicant_phone']);
			wp_set_current_user( $user_id );
			wp_set_auth_cookie( $user_id );
			$mail_to = array($email => $user_details['first_name']);
			$message = array(
				'subject' => 'Account Creation',
				'body' => file_get_contents(plugin_dir_path(__DIR__) . '/templates/email/new_user_email.php'),
			);
			
			GHFMailer::ghf_send_mail($mail_to,null,$message);
			wp_send_json_success(
				array(
					'code' => 1,
					'message' => 'Account created',
				)
			);
			return;
			
			wp_die();
		}
        /** 
         * Log user in with credentials and Set Auth cookie.
         * @return bool
         */
        public static function ghf_log_user_in($user_creds){
            if ( is_user_logged_in() ) {
				return;
			}
			$user_data = array(
				'user_login'    => sanitize_text_field( $user_creds['user_login']),
				'user_password' => sanitize_text_field( $user_creds['password'] ),
			);
			$user_id   = wp_signon( $user_data, false );
			if ( is_wp_error( $user_id ) ) {
				wp_send_json_error(
					array(
						'code'    => 0,
						'message' => __('Username or Password is in-correct, Please Try Again.','clynk-core'),
					)
				);
			} else {
				update_user_meta(get_current_user_id(),'last_login',time());
				wp_send_json_success(
					array(
						'code'    => 1,
						'message' => __( 'Successfully Logged In! Redirecting to Dashboard', 'clynk-core' ),
					)
				);

			}
			wp_die();

        }
        public function editProfile($user_id){
            return $user_id;
        }
    }
}