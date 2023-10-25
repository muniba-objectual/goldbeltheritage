jQuery(document).ready(function ($) {
    function setFormValues() {
      var formData = $("#form_values").val();
      console.log(formData);
      if (formData) var parsedData = JSON.parse(formData);
      for (var i in parsedData) {
        var inputType = $(`input[name='${i}']`).attr("type");
        switch (inputType) {
          case "text":
          case "date":
            $(`input[name='${i}']`).val(parsedData[i]);
            break;
          case "radio":
            $(`input[name='${i}']`).each(function (n, t) {
              if ($(t).val() == parsedData[i]) {
                $(t).prop("checked", true);
              }
            });
            break;
          case "checkbox":
            $(`input[name='${i}']`).prop("checked", true);
            break;
          case "file":
            $(`p[data-type-name="${i}"] a`).attr("href", parsedData[i]);
            $(`p[data-type-name="${i}"] a`).text("View Attachment");
            break;
          default:
            break;
        }
      }
    }
    setFormValues();
    function loader(message) {
      return `<div class="spinner-border" role="status">
              <span class="visually-hidden">${message}</span>
          </div>`;
    }
  
    function showPageLoader(delay) {
      var fullPageLoader = $(".ajax-loader-wrapper");
      $(fullPageLoader).show();
    }
    function hidePageLoader() {
      var fullPageLoader = $(".ajax-loader-wrapper");
      $(fullPageLoader).hide();
    }
  
    function ghfDoAJax(
      form,
      method = "POST",
      action,
      formData = {},
      onSuccess,
      onFailure
    ) {
      var submitBtn = $(form).find("button[type='submit']");
      $(submitBtn).find("span").hide();
      $(submitBtn).append(loader());
      $.ajax({
        url: AJAX_HANDLER.ajax_url,
        method: method,
        data: {
          action: action,
          formData: formData,
        },
        success: function (res) {
          $(submitBtn).find(".spinner-border").hide();
          $(submitBtn).find("span").show();
          onSuccess(res);
        },
        error: function (res) {
          onFailure(res);
        },
      });
    }
  
    function ghfDoReload(url = null, delay = 0, divReload = false, elemId = "#") {
      setTimeout(function () {
        if (!divReload) {
          !url
            ? (window.location.href = window.location.href)
            : (window.location.href = url);
          return;
        }
        $("#" + elemId).load(" #" + elemId + " > *");
      }, delay);
    }
  
    function showError(form, message) {
      var errorElem = $(form).find(".error-form");
      $(errorElem).text(message);
      $(errorElem).addClass("active");
    }
  
    /** -------------------------------------------------------------------------------------------------- */
  
    var UserHandling = function () {
      $(document).on("submit", "form[name='login_form']", this.loginUser);
      $(document).on(
        "submit",
        "form[name='registration_form']",
        this.registerUser
      );
    };
  
    UserHandling.prototype.loginUser = function (e) {
      e.preventDefault();
      var _this = this;
      var formClass = $(_this).attr("class");
      var data = $(_this).serialize();
      var action = "ghf_user_login";
      ghfDoAJax(
        _this,
        "POST",
        action,
        data,
        (res) => {
          if (res.success) {
            ghfDoReload(null, 500);
          } else {
            showError(_this, res.data.message);
          }
        },
        (res) => {}
      );
    };
  
    UserHandling.prototype.registerUser = function (e) {
      e.preventDefault();
      var _this = this;
      var formClass = $(_this).attr("class");
      var data = $(_this).serialize();
      var action = "ghf_user_register";
      ghfDoAJax(
        _this,
        "POST",
        action,
        data,
        (res) => {
          console.log(res);
          if (res.success || res.code == 1) {
            ghfDoReload(null, 500);
          } else {
            if (res.data || res.message) {
              showError(_this, res.data.message);
            }
          }
        },
        (res) => {}
      );
    };
  
    new UserHandling();
  
    /**  =============================== Form Builder  ==================================================*/
    if ($(".edit_form_id").length < 1) {
      var options = {
        typeUserAttrs: {
          text: {
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
              helpText: {
                label: "Help Style",
                options: {
                  "text-style-bold": "Bold",
                  "text-style-italic": "Italic",
                  "text-style-underline": "Underline",
                },
              },
            },
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
        },
      };
      var formBuilder = $("#form_builder").formBuilder(options);
  
      console.log(options, "formBuilder");
    }
    var formHandling = function () {
      $(document).on("click", "#create_form", this.saveFormData);
      $(document).on("click", ".remove_application_form", this.deleteForm);
      $(document).on("click", ".pause_application_form", this.pauseForm);
      $(document).on("click", ".activate_application_form", this.activateForm);
      
    };
    formHandling.prototype.saveFormData = function (e) {
      e.preventDefault();
      var _this = this;
      var form_id = $(".edit_form_id").length > 0 ? $(".edit_form_id").val() : 0;
      var action = "ghf_save_form_data";
      var formDetails = [];
      formDetails["form_details"] = $("#form_details").serializeArray();
      formDetails["form_fields"] = JSON.parse(formBuilder.formData);
      var dO = Object.assign({}, formDetails);
      showPageLoader();
      ghfDoAJax(
        _this,
        "POST",
        action,
        dO,
        (res) => {
          console.log(res);
          hidePageLoader();
          ghfDoReload("?edit_form=" + res.data, 500);
        },
        (err) => {}
      );
    };
    formHandling.prototype.pauseForm = function (e) {
      e.preventDefault();
      var _this = this;
      var form_id = $(this).data("form-id");
      // alert(form_id);
      // return;
      var action = "ghf_pause_application_form";
      var confirmDelete = confirm("Are you sure you want to pause this form?");
      var nonce = $(`input[name="ghf_${form_id}_nonce"]`).val();
      var formData = `form_id=${form_id}&nonce=${nonce}`;
      if (confirmDelete) {
        showPageLoader();
        ghfDoAJax(
          _this,
          "POST",
          action,
          formData,
          (res) => {
            console.log(res);
            hidePageLoader();
            setTimeout(function () {
              window.location.href = window.location.href;
            }, 500);
          },
          (err) => {}
        );
      }
    };
    function inActiveFormSubmission(submissionId) {
      var _this = this;
      // var submissionId = submissionId;
       alert(submissionId);
      // return;
      var action = "ghf_inactive_application";
      var confirmDelete = confirm("Are you sure you want to delete this application?");
      // var nonce = $(`input[name="ghf_${submissionId}_nonce"]`).val();
      var formData = `submissionId=${submissionId}`;
      if (confirmDelete) {
        showPageLoader();
        ghfDoAJax(
          _this,
          "POST",
          action,
          formData,
          (res) => {
            console.log(res);
            hidePageLoader();
            setTimeout(function () {
              // return;
              window.location.href = window.location.href;
            }, 500);
          },
          (err) => {}
        );
      }
    };
    formHandling.prototype.activateForm = function (e) {
      e.preventDefault();
      var _this = this;
      var form_id = $(this).data("form-id");
      // alert(form_id);
      // return;
      var action = "ghf_activate_application_form";
      var confirmDelete = confirm("Are you sure you want to activate this form?");
      var nonce = $(`input[name="ghf_${form_id}_nonce"]`).val();
      var formData = `form_id=${form_id}&nonce=${nonce}`;
      if (confirmDelete) {
        showPageLoader();
        ghfDoAJax(
          _this,
          "POST",
          action,
          formData,
          (res) => {
            console.log(res);
            hidePageLoader();
            setTimeout(function () {
              window.location.href = window.location.href;
            }, 500);
          },
          (err) => {}
        );
      }
    };
    formHandling.prototype.deleteForm = function (e) {
      e.preventDefault();
      var _this = this;
      var form_id = $(this).data("form-id");
      var action = "ghf_remove_application_form";
      var confirmDelete = confirm("Are you sure you want to delete this form?");
      var nonce = $(`input[name="ghf_${form_id}_nonce"]`).val();
      var formData = `form_id=${form_id}&nonce=${nonce}`;
      if (confirmDelete) {
        showPageLoader();
        ghfDoAJax(
          _this,
          "POST",
          action,
          formData,
          (res) => {
            console.log(res);
            hidePageLoader();
            setTimeout(function () {
              window.location.href = window.location.href;
            }, 500);
          },
          (err) => {}
        );
      }
    };
    new formHandling();
  
    /** ===================================== Submission Handler ================================ */
    var submissionHandler = function () {
      $(document).on("submit", ".ghf_application_form", this.submitData);
    };
  
    submissionHandler.prototype.submitData = function (e) {
      e.preventDefault();
      var _this = this;
      var data =
        $(_this).serialize() +
        "&form_id=" +
        $("#form_id").val() +
        "&form_name=" +
        $("#form_name").val();
      var action = "ghf_submit_application";
      showPageLoader();
      ghfDoAJax(
        _this,
        "POST",
        action,
        data,
        (res) => {
          console.log(res);
          hidePageLoader();
          window.location.href = window.location.href;
        },
        (err) => {}
      );
    };
  
    new submissionHandler();
  
    /** ===================================== Save Profile Settings ================================ */
    var profileSettings = function () {
      $(document).on("submit", ".edit_profile_form", this.saveProfileSettings);
    };
  
    profileSettings.prototype.saveProfileSettings = function (e) {
      e.preventDefault();
      var _this = this;
      var data = $(_this).serialize();
      var action = "ghf_save_profile_settings";
      showPageLoader();
      ghfDoAJax(
        _this,
        "POST",
        action,
        data,
        (res) => {
          console.log(res);
          hidePageLoader();
        },
        (err) => {}
      );
    };
  
    new profileSettings();
  
    /**======================================== PDF Handler ================================= */
    var pdfHandler = function () {
      $(document).on("click", ".download_pdf", this.downloadPDF);
    };
  
    pdfHandler.prototype.downloadPDF = function (e) {
      e.preventDefault();
      var data = $(".ghf_application_form").html();
      $.ajax({
        url: AJAX_HANDLER.ajax_url,
        method: "POST",
        data: {
          action: "ghf_render_pdf",
          pdfData: data,
        },
        success: function (res) {
          console.log(res);
        },
        error: function (err) {
          console.log(err);
        },
      });
    };  
    new pdfHandler();
    $("[data-emphasis]").each(function () {
      $(this).addClass($(this).data("emphasis"));
    }); 
  
    $("[data-fontstyles]").each(function(i,v){
      $(this).addClass($(this).data('fontstyles'));
    });
    $("[data-fontsizeemphasis]").each(function () {
      $(this).addClass($(this).data("fontsizeemphasis"));
    });  
    $("[data-fontsizes]").each(function(i,v){
      $(this).addClass($(this).data('fontsizes'));
    });
    $('.applicationSelectAction').on('change',function(){
      var intRegex = /^\d+$/;
      console.log($(this).val());
      if($(this).val() != ''){
        if(intRegex.test($(this).val())){
          inActiveFormSubmission($(this).val());
          return;
        }
        window.location = $(this).val();
      }
    });
    $('.approve_submission').on('click',function(){
        $('.forward-form').toggle();
        // $(document).on("click", ".remove_application_form", this.deleteFormSubmission);
    });
  

  $('.create_new_form').click(function(){
    setTimeout(function() { 
      console.log('sd');
  }, 2000);
  //   $('.label-wrap label').text(function(i, oldText) {
  //     console.log('asdas');
  //     return oldText === 'Label' ? 'New word' : oldText;
  // });
  });

  $(".create_new_form").click(function () { 
    setTimeout(function() { 
      alert('sadasd');
      console.log('sd');
    }, 1000);
});
 
  }); 