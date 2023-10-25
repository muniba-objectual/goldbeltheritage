<?php 

/** 
 * Plugin Name: GHF Application Form Portal 
 * Description: GHF Application Form Portal for Scholarships Applications 
 * Author: ITSolution24x7
 * URI: https://itsolution24x7.net
 * text-domain ghf-portal
 */

defined( 'ABSPATH' ) || exit;
require 'vendor/autoload.php';
define('GHF',__FILE__);
define('GHF_CORE',plugin_dir_url(__FILE__));
define('GHF_ADMIN',plugin_dir_url(__FILE__) . 'admin');
define('GHF_ADMIN_ASSETS',plugin_dir_url(__FILE__) . '/admin/assets');
define('GHF_PUBLIC_ASSETS',plugin_dir_url(__FILE__) . '/public/assets');
define('PLUGIN_PATH',plugin_dir_path(__DIR__));


new Admin\GHFRoleHandler();
new FrontEnd\GHFHooks();
new FrontEnd\GHFForms();
new FrontEnd\GHFDashboard();
new Includes\GHFApplications();
new Includes\GHFHelper();
new Includes\GHFMailer();
new Includes\GHFAjax();
new FrontEnd\GHFThemeHelper();
new Includes\GHFSubmissions();
new Includes\GHFNotifications();

if(!class_exists('GHFPortal')){
    final class GHFPortal{
        
        const MINIMUM_PHP_VERSION         = '7.0';
        
        public function __construct(){
            add_action('admin_enqueue_scripts',array($this,'ghf_admin_enqueue_scripts'));
            add_action('wp_enqueue_scripts',array($this,'ghf_frontend_enqueue_scripts'));
            if(!is_admin()){
                add_filter( 'show_admin_bar', '__return_false' );
            }
			
			add_action('wp_head',function(){
				echo ' <script src="https://cdn.tiny.cloud/1/ww16ec8wz9miot2sqhfuy4exn2k2el0jvx70kko7gjvpld61/tinymce/5/tinymce.min.js" 	        				referrerpolicy="origin"></script>';
			});

            // add_action('wp_head',function(){
            //     print_r(wp_get_upload_dir());
            // });

            add_action( 'login_enqueue_scripts',function(){
                ?>
                <style type="text/css">
                    #login h1 a, .login h1 a {
                        background-image: url('https://secureservercdn.net/198.71.233.28/t8h.529.myftpupload.com/wp-content/themes/goldbelt-portal/assets/images/Logo.png');
                    height:100px;
                    width:300px;
                    background-size: 300px 100px;
                    background-repeat: no-repeat;
                    padding-bottom: 10px;
                    }
                </style>
                <?php
            });
        }
        
        /** 
         * Admin Side stylesheets and scripts
         */
        public function ghf_admin_enqueue_scripts(){
            wp_enqueue_style('admin-style',GHF_ADMIN_ASSETS . '/admin-style.css',array(),'1.0','all');
        }
        
        /** 
         * Front End Side stylesheets and scripts
         */
        public function ghf_frontend_enqueue_scripts(){
            wp_enqueue_style('frontend-style',GHF_PUBLIC_ASSETS . '/frontend-css.css',array(),'1.0','all');
            wp_enqueue_style('frontend-style',GHF_PUBLIC_ASSETS . '/frontend-css.css',array(),'1.0','all');
            wp_enqueue_script('jquery-ui','https://code.jquery.com/ui/1.13.0/jquery-ui.min.js',array('jquery'),'1.0',true);
            wp_enqueue_script('formbuilder','https://formbuilder.online/assets/js/form-builder.min.js',array('jquery'),'1.0',true);
            wp_enqueue_script('frontend-js',GHF_PUBLIC_ASSETS . '/frontend.js',array('jquery'),'1.0',true);
            wp_enqueue_script('ImageElement',GHF_PUBLIC_ASSETS . '/ghfImageElement.js',array('jquery'),'1.0',true);
            wp_enqueue_script('dropzone-js','https://unpkg.com/dropzone@5/dist/min/dropzone.min.js',array('jquery'),'1.0',false);
			
			//datatables 
			wp_enqueue_style('dt-css',GHF_PUBLIC_ASSETS . '/dataTables.min.css',array(),'1.0','all');
			wp_enqueue_script('dt',GHF_PUBLIC_ASSETS . '/dataTables.min.js',array('jquery'),'1.0',true);
            wp_localize_script(
				'frontend-js',
				'AJAX_HANDLER',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'site_url' => home_url(),
					'is_user_logged_in' => is_user_logged_in(),
                )
			);
        }
    }

    new GHFPortal();
}