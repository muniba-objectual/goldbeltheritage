<?php
namespace FrontEnd;

defined('ABSPATH') || exit; 

if(!class_exists('GHFHooks')){
    class GHFHooks{
        public function __construct(){
            add_action('ghf_main',array($this,'ghf_main_header'));
            add_action('ghf_login',array($this,'ghf_login_screen_wrapper_start'),10);
            add_action('ghf_login',array($this,'ghf_login_screen_wrapper_end'),999999999999);

            add_action('ghf_login',array($this,'ghf_login_form_side'),20);
            add_action('ghf_login',array($this,'ghf_login_form'),30);

            add_action('ghf_dashboard',array($this,'ghf_dashboard_main_wrapper_start'),10);
            add_action('ghf_dashboard_end',array($this,'ghf_dashboard_main_wrapper_end'),99999999999999);

            // ajax loader
            add_action('ghf_dashboard',[$this,'ghf_dashboard_ajax_loader'],25);

        }


        /** 
         * Ajax Loader 
         */
        public function ghf_dashboard_ajax_loader(){
            ?>
            <div class="ajax-loader-wrapper" style="display:none">
                <p>Loading... Please wait</p>
            </div>
            <?php
        }


        

        /** 
         * GHF Login Form Side
         */
        public function ghf_login_form_side(){
            ?>
            <div class="col-xl-8 col-lg-7 col-md-12 main-bg-image none-992" style="background-color:#000;background-image:url(<?php echo GHF_IMAGES . '/ghf-main-2.png'; ?>)">
                <div class="info">
                    <h1>Welcome to GHF Applications Portal</h1>
                    <p>The Goldbelt Heritage was established in 2001 and is a 501(c)(3) not-for-profit organization. Based in Juneau, Alaska, GHF is committed to protecting, preserving, and passing on the cultural identity and traditional ways of life of the Tlingit Indian people of southeast Alaska.</p>
                    <a href="https://ghf.com">Visit Website</a>

                    <p class="further-support">For any further queries, please send us a message at <a href="mailto:#">GHF Support</a></p>
                </div>
            </div>
            <?php
        }

        /** 
         * GHF Login Form
         */
        public function ghf_login_form(){
            global $GHFForms;
            ?>
             <div class="col-xl-4 col-lg-5 col-md-12 bg-color-10">
                <div class="form-section login-section-ghf">
                    <?php if(function_exists('ghf_logo')){ ghf_logo(); } ?>
                    <h4><?php echo __('To Proceed, Create an Account or Sign in','ghf-portal'); ?></h4>
                    <div class="registration-form-wrapper">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Existing Applicant</button>
                            <button class="nav-link " id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">New Applicant</button>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <?php
                            $registration_form_fields = array(
                                array(
                                    'field_name' => 'first_name',
                                    'label' => 'First Name',
                                    'placeholder' => 'Enter your First Name',
                                    'class' => 'form-control',
                                    'id' => 'first_name',
                                    'required' => true,
                                    'input_type' => 'text',
                                ),
                                array(
                                    'field_name' => 'last_name',
                                    'label' => 'Last Name',
                                    'placeholder' => 'Enter your Last Name',
                                    'class' => 'form-control',
                                    'id' => 'last_name',
                                    'required' => true,
                                    'input_type' => 'text',
                                ),
                                array(
                                    'field_name' => 'email',
                                    'label' => 'Email',
                                    'placeholder' => 'Enter your Email Address',
                                    'class' => 'form-control',
                                    'id' => 'email',
                                    'required' => true,
                                    'input_type' => 'email',
                                ),
                                array(
                                    'field_name' => 'applicant_phone',
                                    'label' => 'Phone',
                                    'placeholder' => 'Enter your Phone Number',
                                    'class' => 'form-control',
                                    'id' => 'applicant_phone',
                                    'required' => true,
                                    'input_type' => 'text',
                                ),
                                array(
                                    'field_name' => 'password',
                                    'label' => 'Password',
                                    'placeholder' => 'Enter your Password',
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required' => true,
                                    'input_type' => 'password',
                                ),
                            ); 
                            $registration_form_fields = apply_filters('registration_form_field',$registration_form_fields);
                            $GHFForms->createForm('registration_form','form-registration',$registration_form_fields,true,'Proceed'); 
                        ?>
                        </div>
                        <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <?php
                            $login_form_fields = array(
                                array(
                                    'field_name' => 'user_login',
                                    'label' => 'Username or Email Address',
                                    'placeholder' => 'Enter your Username or Email Address',
                                    'class' => 'form-control',
                                    'id' => 'user_login',
                                    'required' => true,
                                    'input_type' => 'text',
                                ),
                                array(
                                    'field_name' => 'password',
                                    'label' => 'Password',
                                    'placeholder' => 'Enter your Password',
                                    'class' => 'form-control',
                                    'id' => 'password',
                                    'required' => true,
                                    'input_type' => 'password',
                                ),
                            ); 
                            apply_filters('registration_form_field',$login_form_fields);
                            $GHFForms->createForm('login_form','form-login',$login_form_fields,true,'Login'); 
                            echo '<a href="'.wp_lostpassword_url(home_url()).'">Forgot Password</a>';
                        ?>
                        </div>
                    </div>
                    
                    </div>
                </div>
            </div>
            <?php
        }

        /** 
         * GHF Main Header - Different for Logged in Logged Out Users.
         */
        public function ghf_main_header(){
            global $user;
            if(is_user_logged_in()){
               get_header('logged-in');
               do_action('ghf_dashboard');
               do_action('ghf_dashboard_end');
               get_footer('logged-in');
            }else{
                get_header();
                do_action('ghf_login');
                get_footer();
            }
        }
        
        public function ghf_login_screen_wrapper_start(){
            $html = '<div class="login-12"><div class="container-fluid"><div class="row">';
            echo $html;
        }
        
        
        public function ghf_login_screen_wrapper_end(){
            $html = '</div></div></div>';
            echo $html;
        }


        /** 
         * Dashboard Main Wrapper Start
         */
        public function ghf_dashboard_main_wrapper_start(){
            $user = wp_get_current_user();
            echo '<div class="dashboard-main-inside"><div class="container">';   
        }
        
        /** 
         * Dashboard Main Wrapper Start
         */
        public function ghf_dashboard_main_wrapper_end(){
            echo '</div></div>';   
        }
    }
}