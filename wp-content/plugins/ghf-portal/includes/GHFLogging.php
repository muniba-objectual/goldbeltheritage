<?php 

namespace Includes; 

defined('ABSPATH') || exit; 


if(!class_exists('GHFLogging')){
    class GHFLogging{
        /** 
         * Log Errors 
         * WP_DEBUG_LOG must be set to true in wp-config
         * @param string 
         * @return null
         */
        public static function ghf_log_error($statement){
            error_log($statement);
            return;
        }
    }
}