<?php
/**
 * The Template for displaying all avaialable forms.
 *
 * This template can be overridden by copying it to yourtheme/ghf_templates/forms/view_all_forms.php.
 *
 */

defined( 'ABSPATH' ) || exit;

get_header('logged-in');

do_action('ghf_dashboard');

?>
<input type="hidden" class="edit_form_id" value="<?php echo esc_attr($form->form_id) ?>"/> 
<script>
    jQuery(document).ready(function($){
        var builder = document.getElementById('form_builder');
		var options = {
			disableFields: [
            'autocomplete',
            'hidden',
          ], 
      typeUserAttrs: {
        paragraph: {
          colorName: {
            label: "Color",
            options: {
              "text-color-red": "Red",
              "text-color-green": "Green",
              "text-color-yellow": "Yellow",
              "text-color-blue": "Blue",
              "text-color-white": "White",
              "text-color-black": "Black",
            },
            style: "border: 1px solid #ddd",
          },
          dataEmphasis: {
            label: "Style",
            options: {
              "text-style-bold": "Bold",
              "text-style-italic": "Italic",
              "text-style-underline": "Underline",
            },
          },
          dataFontSizeEmphasis: {
            label: "Font Size",
            options: {
              "font-size-18": "18 pt",
              "font-size-20": "20 pt",
              "font-size-22": "22 pt",
              "font-size-24": "24 pt",
            },
          },
        },
        header: {
          colorName: {
              label: "Color",
              options: {
                "text-color-red": "Red",
                "text-color-green": "Green",
                "text-color-yellow": "Yellow",
                "text-color-blue": "Blue",
                "text-color-white": "White",
                "text-color-black": "Black",
              },
              style: "border: 1px solid #ddd",
            },
            dataEmphasis: {
              label: "Style",
              options: {
                "text-style-bold": "Bold",
                "text-style-italic": "Italic",
                "text-style-underline": "Underline",
              },
            },
        },
        text: {
          title: {
            label: 'Title',
            value: 'Field Title',
          },
        },
      }, 
		}
    console.log('options1227',options);
        var fBuilder = $(builder).formBuilder(options);
        fBuilder.promise.then(formBuilder => {
            formBuilder.actions.setData(<?php echo $form_fields ?>);
            return formBuilder;
        });

        $(document).on("click","#update_form",function(e){
            e.preventDefault();
            $(".ajax-loader-wrapper").show();
            var _this = this;
            var form_id = ($(".edit_form_id").length > 0) ? $(".edit_form_id").val() : 0;
            var action = 'ghf_save_form_data';
            var formDetails = [];
            formDetails['formId'] = form_id;
            formDetails['form_details'] = $("#form_details").serializeArray();
            formDetails['form_fields'] = JSON.parse(fBuilder.formData);
            var dO = Object.assign({},formDetails);
            $.ajax({
                url:'<?php echo admin_url('admin-ajax.php'); ?>',
                method:'POST',
                data:{
                    action:'ghf_save_form_data',
                    formData:dO
                },
                success:function(res){
                    $(".ajax-loader-wrapper").hide();
                    console.log(res);
                    setTimeout(() => {
                        window.location.href = window.location.href;
                    }, 500);
                },
                error:function(err){
                    console.log(err);
                }
            })
        })
    })
</script>
<div class="general_page_header">
    <div class="row align-items-center">
        <div class="col-lg-6">
            <?php echo '<h3 class="page-title">Edit Form '.$form_name.'</h3>';
            ?>
        </div>
        <div class="col-lg-6 text-end">
            <p>created on: <strong><?php echo date('m-d-Y',strtotime($form->form_created)); ?></strong></p>
            <p>updated on: <strong><?php echo date('m-d-Y',strtotime($form->updated_on)); ?></strong></p>
        </div>
    </div>
</div>
<div class="page_content">
    <form id="form_details">
        <input type="text" name="form_name" placeholder="Form Name" value="<?php echo esc_attr($form_name); ?>">
        <!-- <input type="text" name="form_description" placeholder="Form Description" value="<?php echo esc_attr($form_desc); ?>"> -->
    </form>
    <div id="form_builder"></div>
    <button id="update_form">Update Form</button>
</div>
<?php
//do_action('form_helper_instructions');
do_action('ghf_dashboard_end');

get_footer('logged-in');
