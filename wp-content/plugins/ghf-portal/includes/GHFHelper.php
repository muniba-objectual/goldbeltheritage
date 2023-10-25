<?php 

namespace Includes; 

defined('ABSPATH') || exit;

if(!class_exists('GHFHelper')){
    class GHFHelper{

        public function __construct(){
            add_action('wp_head',array($this,'redirect_to_login'));
            add_action('init',array($this,'ghf_rewrite_rule'));
            add_filter( 'template_include', array( $this, 'ghf_filter_template' ), 99 );
        }

       
        public $required_pages = array(
            'templates/forms/view_all_forms.php' => '',
            'templates/notifications/view_notifications.php' => 'notifications',
            'templates/profile/edit_profile.php' => 'edit_profile',
            'templates/contact.php' => 'contact',
        );
        
        public $committee_required_pages = array(
            'templates/contact.php' => 'contact',
            'templates/applications/view_all_submissions.php' => 'view_all_submissions',
        );
        public $admin_required_pages = array(
            'admin/views/manage_applicants.php' => 'Manage Applicants',
			'admin/views/view_all_submissions.php' => '',
            'admin/views/view_all_applicants.php' => 'all_applicants',
            'admin/views/view_all_users.php' => 'view_users',
            'admin/views/edit_single_user.php' => 'edit_user',
            'admin/views/view_all_members.php' => 'committee_members',
            'templates/notifications/view_notifications.php' => 'notifications',
            'admin/views/ghf_portal_main_screen.php' => 'manage_applications',
            'admin/views/ghf_create_new_form.php' => 'create_new_form',
        );
        

        /** 
         * Redirect to Login
         */
        public function redirect_to_login(){
            global $wp;
            if($wp->request !== '' && !is_user_logged_in()){
                wp_safe_redirect(home_url());
            }
        }

        /** 
         * Rewrite Rule 
         * rewrites rule to give access to custom urls.
         */
        public function ghf_rewrite_rule(){
            foreach ( $this->required_pages as $required_page ) {
                add_rewrite_rule(
                    $required_page,
                    'index.php?post_type[]=applicant_required_pages',
                    'top'
                );
            }
        }

        public function init() {
			$this->ghf_rewrite_rule();
		}

        /**
         * Required Pgaes - Loop 
         */
        public function ghf_required_pages_loop($required_pages,$template){
            global $wp,$wp_query;
            
            if($required_pages){
                foreach($required_pages as $page_location => $required_page){
                    if ( $wp_query->is_404 ) {
                        $wp_query->is_404 = false;
                    }
                    header( 'HTTP/1.1 200 OK' );
                    if($wp->request === $required_page){
                        if ( locate_template( 'ghf_' . $page_location ) ) {
                            
                            $template = locate_template( 'ghf_' . $page_location );
                        } else {
                            $template = dirname(__DIR__) . '/' . $page_location;
                        }
                    }
                }
            }
            do_action('pre_render_query',$_GET);
            return $template;
        }


        /** 
         * Filter Template 
         * 
         */
        public function ghf_filter_template($template){
            global $wp,$wp_query;
            if($this->ghf_is_admin(get_current_user_id())){
                $template = $this->ghf_required_pages_loop($this->admin_required_pages,$template);
            }elseif ($this->ghf_is_applicant(get_current_user_id())) {
                $template = $this->ghf_required_pages_loop($this->required_pages,$template);
            }else{
                $template = $this->ghf_required_pages_loop($this->committee_required_pages,$template);
            }
            return $template;
            
        }

        /** 
         * Load Value from .ENV 
         * @param string
         * @return string
         * @author DevSyed
         * @since 1.0
         */
        public function ghf_get_env_value($variable){
            $dotenv = \Dotenv\Dotenv::createImmutable(plugin_dir_path(__DIR__));
            $dotenv->safeLoad();
            $value = $_ENV[$variable];
            return $value;
        }


        
        /** 
         * Check if GHF Applicant
         * @param int
         * @return bool
         */
        public static function ghf_is_applicant($user_id){
            $user_data = get_userdata($user_id);
            if($user_data){
                $user_roles = $user_data->roles;
                return in_array('applicant',$user_roles);
            }
            return false;
        }
        
        /** 
         * Check if not an admin or super admin
         * @param int
         * @return bool
         */
        public static function ghf_is_admin($user_id){
            $user_data = get_userdata($user_id);
            if($user_data){
                $user_roles = $user_data->roles;
                return in_array('administrator',$user_roles);
            }
            return false;
        }
        
    }
}

register_activation_hook(
	__FILE__,
	function() {
		$ghf_helper = new GHFHelper();
		$ghf_helper->init();
		flush_rewrite_rules();
	}
);