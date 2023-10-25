<?php 

namespace FrontEnd; 

defined('ABSPATH') || exit; 

if(!class_exists('GHFForms')){
    class GHFForms{
        private static $instance;
		public static function getInstance() {
			if ( ! self::$instance instanceof self ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

        public function __construct(){
            add_shortcode('ghf_file_uploader',[$this,'ghf_file_uploader_func']);
            add_action('pre_render_query',[$this,'ghf_update_submission'],10);
        }


        /** 
         * GHF File Uploader
         */
        public function ghf_file_uploader_func($atts){
            $atts = shortcode_atts(array(
                'classes' => '',
                'label' => '',
                'field_name' => '', 
                'editing' => ''
            ),$atts);
            extract($atts);
            $random_id = rand(1,99); 
            ?>
            <div class="ghf_file_uploader <?php echo $classes ?>">
            <h4 class="heading_uploader"><?php echo ucfirst(str_replace('_',' ',$field_name)); ?></h4>
            <?php if($editing): ?>
            <script>
                jQuery(document).ready(function($){
                    var DropZone_<?php echo $random_id ?> = new Dropzone('#ghf-file-uploader-<?php echo $random_id ?>',{
                        url:'<?php echo admin_url('admin-ajax.php') . '?action=ghf_upload_files&user_id=' . get_current_user_id(); ?>',
                        uploadMultiple:true,
                        maxFileSize:1,
                        acceptedFiles : '.pdf,.docx',
                        autoProcessQueue:false,
                        addRemoveLinks:true,
                        parallelUploads:1,
                        maxFiles:1,
                        success:function(files,response){
                            console.log(response);
                            $(".ghf_file_uploader").append(`<input type="hidden" name="<?php echo $field_name ?>" value="${response.data}"/>`);
                        },
                    });

                    $(document).on("click","#upload_files_<?php echo $random_id; ?>",function(e){
                        e.preventDefault();
                        DropZone_<?php echo $random_id; ?>.processQueue();
                    })
                })
            </script>
            <div class="ghf-file-uploader" id="ghf-file-uploader-<?php echo $random_id ?>" data-uploader-for="resume">
                <div class="dz-message" data-dz-message>
                    <p>Upload Files Here</p>
                </div>
            </div>
            <button class="upload_images" data-uploader-id="<?php echo $random_id; ?>" id="upload_files_<?php echo $random_id; ?>">Upload Selected File</button>
            <?php 
            else:
                echo '<input type="file" style="display:none!important" name="'.$field_name.'"/><p data-type-name="'.$field_name.'" class="text-center"><a href="javascript:void(0)">Not Uploaded</a></p>';
                endif;
            ?>
            </div>

            <?php
        }

        /** 
         * Create Form 
         * @param string|string|array|boolean|string|string
         * @return Form
         * @author devSyed
         * @since 1.0
         */
        public function createForm($form_name,$form_class,$fields,$ajax = false,$submit_value,$labels=false){
            echo '<form class="'.$form_class.'" name="'.$form_name.'">';
            echo '<p class="error-form" id="'.$form_class.'-error" style="display:none"></p>';
            if(is_array($fields) && !empty($fields)){
                foreach($fields as $field){
                    switch($field['input_type']){
                        case 'text': case 'email' : case 'password':
                            $field = '<input type="'.$field['input_type'].'" name="'.$field['field_name'].'" placeholder="'.$field['placeholder'].'" id="'.$field['id'].'" class="'.$field['class'].'"/>';
                            echo $field;
                            break;
                        default:
                        break;
                    }
                }
            }
            echo '<input type="hidden" name="ghf_'.$form_class.'_nonce_" value="'.wp_create_nonce('ghf_'.$form_class.'_nonce_').'"/>';
            $submit_btn = '<button class="'.$form_name.'-submit-btn submit-form" type="submit"><span class="text">'.$submit_value.'</span></button>';
            echo $submit_btn;
            echo '</form>';
        }


        /** 
         * Form Builder to Form
         */
        public function renderForm($details = array(),$fields,$editing = false){
            $form_id = ($editing) ? str_replace(' ','_',strtolower($details[0]['value'])) : rand(1,100);
            $disabled = (!$editing) ? 'disabled' : '';
			$editing_att = (!$editing) ? 'admin_view' :'';
            echo '<form id="ghf_form_'.$form_id.'" class="ghf_application_form '.$editing_att.'">';
            echo '<p style="display:none"></p>';
            if(is_array($fields) && !empty($fields)){
                foreach($fields as $field){
                    $class_raw = (array_key_exists('className',$field)) ? $field['className'] : '';
                    $data = (array_key_exists('dataEmphasis',$field)) ? $field['dataEmphasis'] : '';
                    $dataFontSizeEmphasis = (array_key_exists('dataFontSizeEmphasis',$field)) ? $field['dataFontSizeEmphasis'] : '';
                    $label = (array_key_exists('label',$field)) ? $field['label'] : '';
                    $name = (array_key_exists('name',$field)) ? $field['name'] : '';
                    $placeholder = (array_key_exists('placeholder',$field)) ? $field['placeholder'] : '';
                    $class = str_replace("form-control",' ',$class_raw);
                    switch($field['type']){
                        case 'text': case 'email' : case 'password': case 'tel':
                            echo '<div class="ghf-field '.$class.'">';
                            echo '<label>' . str_replace('\\','',$label) . '</label>';
                            $field = '<input type="'.$field['type'].'" name="'.$name.'" id="'.$name.'" placeholder="'.$placeholder.'" '.$disabled.' />';
                            echo $field;
                            echo '</div>';
                            break;
                        case 'radio-group':
                            echo '<div class="ghf-field '.$class.'">';
                                echo '<div class="ghf-form-label"><strong>' . $label . '</strong></div>';
                                $values = $field['values'];
                                echo '<div class="options-radio">';
                                foreach($values as $value){
                                    $html = '
									<div class="option-single">
                                    <input type="radio" id="'.$field['name'].'" name="'.$field['name'].'" value="'.$value['value'].'" '.$disabled.'/>
                                    <label>'.$value['label'].'</label></div>';
                                    echo $html;
                                }
                                echo '</div>
                            </div>';
                            break;
                        case 'date':
                            echo '<div class="ghf-field '.$class.'">';
                            echo '<label>' . str_replace('\\','',$label) . '</label>';
                            $field = '<input type="'.$field['type'].'" name="'.$name.'" id="'.$name.'" placeholder="'.$placeholder.'" '.$disabled.' />';
                            echo $field;
                            echo '</div>';
                        break;
                        case 'header':
                            echo '<div class="ghf-section-heading '.$class.'" data-fontstyles='.$data.'>';
                                echo '<h5>' . str_replace('\\','',$label) . '</h5>';
                            echo '</div>';
                        break;
						case 'imageElement':
							echo '<img src="'.$field['value'].'"/>';
							break;
                        case 'paragraph':
                            echo '<div class="ghf-field '.$class.'" data-fontstyles='.$data.'  data-fontsizes='.$dataFontSizeEmphasis.'>';
                            echo '<p>' . str_replace('\\','',$label) . '</p>';
                            echo '</div>';
                            break;
                            case 'textarea':
                            echo '<div class="ghf-field '.$class.'">';
                            echo '<strong>'.$label.'</strong>';
                            echo '<div class="ghf-textarea '.$class.'">';
                            echo '<textarea class="ghf_tinymce" id="'.str_replace('\\','',$field['name']).'" name="'.str_replace('\\','',$field['name']).'" '.$disabled.'></textarea>';
                            echo '</div>';
                            echo '</div>';
                            break;
                            case 'checkbox-group':
                            echo '<div class="ghf-field '.$class.'">';
                            echo '<strong>'.$label.'</strong>';
                            echo '<div class="ghf-checkbox-group '.$class.'">';
                                if($field['values']){
                                    foreach($field['values'] as $cbfield){
										echo '<div class="checkbox-single">';
                                        echo '<input type="checkbox" id="'.$name.'" name="'.$name.'" '.$disabled.'/>';
                                        echo '<label>' . str_replace('\\','',$cbfield['label']) . '</label>';
										echo '</div>';
                                    }
                                }
                            echo '</div>';
                            echo '</div>';
                            break;
                        case 'file':
                            echo do_shortcode('[ghf_file_uploader editing="'.$editing.'" field_name="'.$name.'" classes="'.$class.'" label="'.$label.'"]');
                            break;
                        default:
                            break;
                    }
                }
            }
            echo ($editing) ? '<input type="hidden" name="ghf_'.$form_id.'_nonce_" value="'.wp_create_nonce('ghf_'.$form_id.'_nonce_').'"/>' : '';
            $submit_btn = ($editing) ? '<button class="'.$form_id.'-submit-btn submit-form submit-form-application" type="submit"><span class="text">Submit</span></button>' : '';
            echo $submit_btn;
        }
        /** 
         * Form Builder to Edit Form
         */
        public function renderEditForm($details = array(),$fields,$editing = false,$submission_id = 0){
            // echo $editing;exit;
            global $wpdb;
            $formValues = $wpdb->get_row($wpdb->prepare('SELECT * FROM ghf_application_submissions WHERE submissions_id=%d',array($submission_id)));
            // var_dump($formValues);exit;
            $submission_details =   json_decode($formValues->submission_details);
            $form_id = $formValues->application_id;
            $disabled = (!$editing) ? '' : '';
			$editing_att = (!$editing) ? 'admin_view' :'';
            echo '<form class="" action="#" method="POST">';
            echo '<p style="display:none"></p>';
            if(is_array($fields) && !empty($fields)){
                foreach($fields as $field){
                    $class_raw = (array_key_exists('className',$field)) ? $field['className'] : '';
                    $data = (array_key_exists('dataEmphasis',$field)) ? $field['dataEmphasis'] : '';
                    $dataFontSizeEmphasis = (array_key_exists('dataFontSizeEmphasis',$field)) ? $field['dataFontSizeEmphasis'] : '';
                    $label = (array_key_exists('label',$field)) ? $field['label'] : '';
                    $name = (array_key_exists('name',$field)) ? $field['name'] : '';
                    $placeholder = (array_key_exists('placeholder',$field)) ? $field['placeholder'] : '';
                    $class = str_replace("form-control",' ',$class_raw);
                    switch($field['type']){
                        case 'text': case 'email' : case 'password': case 'tel':
                            echo '<div class="ghf-field '.$class.'">';
                            echo '<label>' . str_replace('\\','',$label) . '</label>';
                            $field = '<input type="'.$field['type'].'" name="'.$name.'" id="'.$name.'" placeholder="'.$placeholder.'" '.$disabled.' />';
                            echo $field;
                            echo '</div>';
                            break;
                        case 'radio-group':
                            echo '<div class="ghf-field '.$class.'">';
                                echo '<div class="ghf-form-label"><strong>' . $label . '</strong></div>';
                                $values = $field['values'];
                                echo '<div class="options-radio">';
                                foreach($values as $value){
                                    $html = '
									<div class="option-single">
                                    <input type="radio" id="'.$field['name'].'" name="'.$field['name'].'" value="'.$value['value'].'" '.$disabled.'/>
                                    <label>'.$value['label'].'</label></div>';
                                    echo $html;
                                }
                                echo '</div>
                            </div>';
                            break;
                        case 'date':
                            echo '<div class="ghf-field '.$class.'">';
                            echo '<label>' . str_replace('\\','',$label) . '</label>';
                            $field = '<input type="'.$field['type'].'" name="'.$name.'" id="'.$name.'" placeholder="'.$placeholder.'" '.$disabled.' />';
                            echo $field;
                            echo '</div>';
                        break;
                        case 'header':
                            echo '<div class="ghf-section-heading '.$class.'" data-fontstyles='.$data.'>';
                                echo '<h5>' . str_replace('\\','',$label) . '</h5>';
                            echo '</div>';
                        break;
						case 'imageElement':
							echo '<img src="'.$field['value'].'"/>';
							break;
                        case 'paragraph':
                            echo '<div class="ghf-field '.$class.'" data-fontstyles='.$data.'  data-fontsizes='.$dataFontSizeEmphasis.'>';
                            echo '<p>' . str_replace('\\','',$label) . '</p>';
                            echo '</div>';
                            break;
                            case 'textarea':
                            echo '<div class="ghf-field '.$class.'">';
                            echo '<strong>'.$label.'</strong>';
                            echo '<div class="ghf-textarea '.$class.'">';
                            echo '<textarea class="ghf_tinymce" id="'.str_replace('\\','',$field['name']).'" name="'.str_replace('\\','',$field['name']).'" '.$disabled.'></textarea>';
                            echo '</div>';
                            echo '</div>';
                            break;
                            case 'checkbox-group':
                            echo '<div class="ghf-field '.$class.'">';
                            echo '<strong>'.$label.'</strong>';
                            echo '<div class="ghf-checkbox-group '.$class.'">';
                                if($field['values']){
                                    foreach($field['values'] as $cbfield){
										echo '<div class="checkbox-single">';
                                        echo '<input type="checkbox" id="'.$name.'" name="'.$name.'" '.$disabled.'/>';
                                        echo '<label>' . str_replace('\\','',$cbfield['label']) . '</label>';
										echo '</div>';
                                    }
                                }
                            echo '</div>';
                            echo '</div>';
                            break;
                        case 'file':
                            echo do_shortcode('[ghf_file_uploader editing="'.$editing.'" field_name="'.$name.'" classes="'.$class.'" label="'.$label.'"]');
                            break;
                        default:
                            break;
                    }
                }
            }
            echo '<input type="hidden" name="form_name" value="'.$submission_details->form_name.'">';
            echo '<input type="hidden" name="sub_id" value="'.$submission_id.'">';
            echo '<input type="hidden" name="to_update_form_id" value="'.$form_id.'">';
            echo ($editing) ? '<input type="hidden" name="ghf_'.$form_id.'_nonce_" value="'.wp_create_nonce('ghf_'.$form_id.'_nonce_').'"/>' : '';
            $submit_btn = ($editing) ? '<button class="'.$form_id.'-submit-btn submit-form " type="submit"><span class="text">Submit</span></button>' : '';
            echo $submit_btn;
        }
        function ghf_update_submission(){
            global $wpdb;
            // echo '<pre>';
            // print_r($_POST);
            // echo 'ghf_'.$_POST['to_update_form_id'].'_nonce_';exit;
            if(isset($_POST['to_update_form_id'])){
                // $updatedData    =   [
                //     ''
                // ];
                foreach($_POST as $key => $data){
                    if($key == $_POST['to_update_form_id']){
                        $updatedData['form_id'] = $data;
                    }
                    if($key == 'ghf_'.$_POST['to_update_form_id'].'_nonce_'){
                        $updatedData['ghf_student_form_nonce_'] = $data;
                    }
                    $updatedData[$key] = $data;
                }
                $id = $wpdb->query($wpdb->prepare('UPDATE ghf_application_submissions SET submission_details=%s WHERE submissions_id=%d',array(json_encode($updatedData,JSON_UNESCAPED_SLASHES),$_POST['sub_id'])));
                wp_safe_redirect(site_url('?edit_submission='.$_POST['sub_id']));
            }
        }


    }
    $GLOBALS['GHFForms'] = GHFForms::getInstance();
}