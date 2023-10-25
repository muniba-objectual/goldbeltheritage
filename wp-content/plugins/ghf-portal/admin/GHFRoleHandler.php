<?php 

namespace Admin; 

defined('ABSPATH') || exit;

if(!class_exists('GHFRoleHandler')){
    class GHFRoleHandler{
        public function __construct(){
            register_activation_hook( GHF, array($this,'ghf_add_new_user_roles') );
        }

        /** 
         * Add New User Roles
         * for applicants and community members.
         */
        public function ghf_add_new_user_roles(){
            add_role( 'comittee_member', 'Committee Member', array( 'view_applications' => true,'leave_feedback' => true ) );
            
            add_role( 'applicant', 'Applicant', array( 'submit_applications' => true,'upload_documents' => true,'edit_applications' => true ) );
        }


    }
}