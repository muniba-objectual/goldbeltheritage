<?php 

namespace FrontEnd; 
use Includes\GHFHelper;
use Includes\GHFNotifications;

defined('ABSPATH') || exit;

if(!class_exists('GHFThemeHelper')){
    class GHFThemeHelper{
        
        private static $instance;
		public static function getInstance() {
			if ( ! self::$instance instanceof self ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

        /** 
         * Applicant Menu Links
         * All the links required for applicant dashboard, can be customized with filter_var
         * applicant_menu_links
         */
        public function ghf_applicant_menu_links(){
            global $wp;
            $GHFHelper = new GHFHelper();
            $main_website = $GHFHelper->ghf_get_env_value('WEBSITE');
            $applicant_menu_links = array(
                '/' => 'All Forms',
                '/notifications' => 'Notifications <span class="notification_count">' . GHFNotifications::ghf_get_user_notifications_count(get_current_user_id()) . '</span>' ,
                $main_website => 'Main Website',
                'contact' => 'Contact Us',
            );
            apply_filters('applicant_menu_links',$applicant_menu_links);
            foreach($applicant_menu_links as $link => $label){
                $active = '';
                if($wp->request == substr($link,1)){
                    $active = 'active';
                }
                echo '<li class="nav-item"><a href="'.$link.'" class="nav-link '.$active.'">'.$label.'</a></li>';
            }
        }
        
        /** 
         * Applicant Menu Links
         * All the links required for applicant dashboard, can be customized with filter_var
         * comittee_menu_links
         */
        public function ghf_committee_menu_links(){
            global $wp;
            $GHFHelper = new GHFHelper();
            $main_website = $GHFHelper->ghf_get_env_value('WEBSITE');
            $comittee_menu_links = array(
                '/' => 'View Applications',
                '/notifications' => 'Notifications',
                '/edit_profile' => 'Manage Profile',
                $main_website => 'Main Website',
                'contact' => 'Contact Us',
            );
            apply_filters('comittee_menu_links',$comittee_menu_links);
            foreach($comittee_menu_links as $link => $label){
                $active = '';
                if($wp->request == substr($link,1)){
                    $active = 'active';
                }
                echo '<li class="nav-item"><a href="'.$link.'" class="nav-link '.$active.'">'.$label.'</a></li>';
            }
        }
        
        
        /** 
         * Admin Menu Links
         * All the links required for applicant dashboard, can be customized with filter_var
         * admin_menu_links
         */
        public function ghf_admin_menu_links(){
            global $wp;
            $GHFHelper = new GHFHelper();
            $main_website = $GHFHelper->ghf_get_env_value('WEBSITE');
            $admin_menu_links = array(
				home_url().'/all_applicants' => 'Manage Applicants',
                home_url().'/' => 'View Applications',
                home_url().'/view_users' => 'Manage Users',
                // home_url().'/notifications' => 'Notifications',
                home_url().'/manage_applications' => 'Manage Forms',
            );
            apply_filters('admin_menu_links',$admin_menu_links);
            foreach($admin_menu_links as $link => $label){
                $active = '';
                if($wp->request == substr($link,1)){
                    $active = 'active';
                }
                echo '<li class="nav-item"><a href="'.$link.'" class="nav-link '.$active.'">'.$label.'</a></li>';
            }
        }

        /** 
         * Menu Handler for GHF Theme Logged in Header
         * Checks for current user role and renders different menu contents
         */
        public function ghf_render_menu(){
            $user_meta = get_userdata(get_current_user_id());
            if(in_array('applicant',$user_meta->roles)){
                $this->ghf_applicant_menu_links();
            }elseif(in_array('comittee_member',$user_meta->roles)){
                $this->ghf_committee_menu_links();
            }else{
                $this->ghf_admin_menu_links();
            }
            
        }
    }

    $GLOBALS['GHFThemeHelper'] = GHFThemeHelper::getInstance();
}