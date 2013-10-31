/**
 * UDSSL Registration Processor
 */
$(document).ready(function(){

$('#udssl-registration-form').validate(
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
    $(element).closest('.form-group').removeClass('success').addClass('has-error');
  },
  success: function(element) {
    element
    .text('OK!').addClass('valid')
    .closest('.form-group').removeClass('has-error').addClass('success');
  }
 });

$('#udssl-registration-form').submit(function( event ) {
    $('#udssl-register-button').html('Registering...');
    event.preventDefault();
    $.ajax({
      type: 'POST',
      url: udssl.registration_url,
      data: {
        firstname: $('#firstname').val(),
        lastname: $('#lastname').val(),
        username: $('#username').val(),
        password: $('#password').val(),
        passwordconfirm: $('#passwordconfirm').val(),
        email: $('#email').val(),
        subscribe: $('#newsletter').is(':checked'),
        _wpnonce: $('[name="_wpnonce"]').val(),
        _wp_http_referer: $('[name="_wp_http_referer"]').val(),
        recaptcha_challenge_field: $('#recaptcha_challenge_field').val(),
        recaptcha_response_field: $('#recaptcha_response_field').val()
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
          $('#udssl-registration-response').html(response);

      })
      .always(function() {
        $('#udssl-register-button').html('Register');
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        //Recaptcha.reload();
  });
});

}); // end document.ready
