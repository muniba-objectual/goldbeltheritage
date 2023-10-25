<?php 

namespace Includes; 

use Includes\GHFHelper;

defined('ABSPATH') || exit; 

if(!class_exists('GHFMailer')){
    class GHFMailer{

        private static $instance;
		public static function getInstance() {
			if ( ! self::$instance instanceof self ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

        /** 
         * Mail Helper - beternalSendMail
         * uses PHPMailer to Send an email
         * @param string|array|string|string|string|string|bool|array
         * @return bool|WP_Error
         * @author devsyed 
         */
        public static function ghf_send_mail($to = array(),$attachments = null,$message = array()){
            $GHFHelper = new GHFHelper();
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();                                               
                $mail->Host       = $GHFHelper->ghf_get_env_value('HOST');                  
                $mail->SMTPAuth   = $GHFHelper->ghf_get_env_value('SMTPAuth');                                
                $mail->Username   = $GHFHelper->ghf_get_env_value('USERNAME'); 
                $mail->Password   = $GHFHelper->ghf_get_env_value('PASSWORD');                           
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;            
                $mail->Port       =  $GHFHelper->ghf_get_env_value('PORT');                                 
                $mail->setFrom(get_option('admin_email'),'Admin');
                if(!empty($to)){
                    foreach($to as $recipient_email => $recipient_name){
                        $mail->addAddress($recipient_email,$recipient_name);
                    }
                }
                // Setting reply to as admin email, can be overridden 
                $mail->addReplyTo(get_option('admin_email'),get_bloginfo('name'));
                $mail->isHTML(true);                           
                $mail->Subject = $message['subject'];
                $mail->Body    = $message['body'];
                $mail->send();
                return true;
            } catch (\PHPMailer\PHPMailer\Exception $e) {
                return new \WP_Error( 'broke', __( $mail->ErrorInfo, "ghf-portal" ) );
            }
        }
    }
    $GLOBALS['GHFMailer'] = GHFMailer::getInstance();
}