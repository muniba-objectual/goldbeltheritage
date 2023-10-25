<?php 

namespace Includes; 

defined('ABSPATH') || exit; 

if(!class_exists('GHFNotifications')){
    class GHFNotifications{

        public function __construct(){
            add_action('init',array($this,'ghf_create_notifications_table'));

            add_action('user_notifications',[$this,'ghf_render_user_notifications']);
        }

        /** 
         * Create Notificatins Table
         */
        public function ghf_create_notifications_table(){
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
			global $wpdb;
			$charset_collate = $wpdb->get_charset_collate();
			$sql             = 'CREATE TABLE IF NOT EXISTS ghf_notifications(
                notification_id int(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                notification_for int(9),
                notification_status VARCHAR(255),
                notification_text LONGTEXT,
                notification_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )' . $charset_collate . ';';
			dbDelta( $sql );
        }
        
        /** 
         * Get User Notifications
         */
        public function ghf_get_user_notifications(){
            global $wpdb;
            $nottifcations = $wpdb->get_results($wpdb->prepare('SELECT * FROM ghf_notifications WHERE notification_for=%d',array(get_current_user_id())));
            return $nottifcations;
        }
        
        
        /** 
         * Get User Notifications
         */
        public static function ghf_get_user_notifications_count(){
            global $wpdb;
            $notifcations_count = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM ghf_notifications WHERE notification_for=%d',array(get_current_user_id())));
            return $notifcations_count;
        }
        
        /** 
         * Create Notification 
         */
        public function ghf_create_notification(){
            global $wpdb;
            $notifcations_count = $wpdb->get_results($wpdb->prepare('SELECT COUNT(*) FROM ghf_notifications WHERE notification_for=%d',array(get_current_user_id())));
            return $notifcations_count;
        }
        
        
        /** 
         * Render User Notifications
         */
        public function ghf_render_user_notifications(){
            global $wp_query;
            $nottifcations = $this->ghf_get_user_notifications();
            $template_for_applications = 'templates/notifications/notification_single.php';
            $template = '';
            if(locate_template( 'ghf_' . $template_for_applications)){
                $template = locate_template('ghf_' . $template_for_applications);
            }else{
                $template = dirname(__DIR__) . '/' .  $template_for_applications;
            }
            if($nottifcations){
                echo '<div class="all_notifications_wrapper">';
                foreach($nottifcations as $notification_id => $notification){
                    require($template);
                }
                echo '</div>';
				return;
            }
            echo '<div class="no_notifications_wrapper"><p>No Notifications Found.</p></div>';
        }
    }
}