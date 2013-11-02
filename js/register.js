/**
 * UDSSL Registration Processor
 */
jQuery(document).ready(function(){

jQuery('#udssl-registration-form').validate(
 {
  rules: {
    firstname: {
      minlength: 4,
      required: true
    },
    lastname: {
      minlength: 4,
      required: true
    },
    username: {
      minlength: 4,
      required: true
    },
    email: {
      required: true,
      email: true
    },
    password: {
      minlength: 5,
      required: true
    },
    passwordconfirm: {
      equalTo: "#password"
    }
  },
  highlight: function(element) {
    jQuery(element).closest('.form-group').removeClass('success').addClass('has-error');
  },
  success: function(element) {
    element
    .text('OK!').addClass('valid')
    .closest('.form-group').removeClass('has-error').addClass('success');
  }
 });

jQuery('#udssl-registration-form').submit(function( event ) {
    jQuery('#udssl-register-button').html('Registering...');
    event.preventDefault();
    jQuery.ajax({
      type: 'POST',
      url: udssl.registration_url,
      data: {
        firstname: jQuery('#firstname').val(),
        lastname: jQuery('#lastname').val(),
        username: jQuery('#username').val(),
        password: jQuery('#password').val(),
        passwordconfirm: jQuery('#passwordconfirm').val(),
        email: jQuery('#email').val(),
        subscribe: jQuery('#newsletter').is(':checked'),
        _wpnonce: jQuery('[name="_wpnonce"]').val(),
        _wp_http_referer: jQuery('[name="_wp_http_referer"]').val(),
        recaptcha_challenge_field: jQuery('#recaptcha_challenge_field').val(),
        recaptcha_response_field: jQuery('#recaptcha_response_field').val()
      }
    })
      .done(function(msg) {
          if(msg.response == 'success'){
              response = '<div class="alert alert-success fade in">';
              response += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
              response += '<strong>Success!</strong> ' + msg.message + '</div>';
              window.location.replace(udssl.registration_url);
          } else {
              response = '<div class="alert alert-warning fade in">';
              response += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
              response += '<strong>Error!</strong> ' + msg.message + '</div>';
          }
          jQuery('#udssl-registration-response').html(response);

      })
      .always(function() {
        jQuery('#udssl-register-button').html('Register');
        jQuery('body,html').animate({
            scrollTop: 0
        }, 800);
        Recaptcha.reload();
  });
});

}); // end document.ready
