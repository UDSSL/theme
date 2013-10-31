/**
 * UDSSL Contact Processor
 */
$(document).ready(function(){

$('#udssl-contact-form').validate(
 {
  rules: {
    name: {
      minlength: 4,
      required: true
    },
    email: {
      required: true,
      email: true
    },
    subject: {
      minlength: 5,
      required: true
    },
    message: {
      minlength: 5,
      required: true
    }
  },
  messages: {
    name: {
      minlength: 'UDSSL requires at least 4 charactors.',
      required: 'UDSSL requires your name.'
    },
    email: {
      required: 'UDSSL requires your email.',
      email: 'Please enter a valid email address.'
    },
    subject: {
      minlength: 'UDSSL requires at least 5 charactors.',
      required: 'Why you are contacting UDSSL.'
    },
    message: {
      minlength: 'UDSSL requires at least 5 charactors.',
      required: 'Please describe why you want to contact UDSSL.'
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

$('#udssl-contact-form').submit(function( event ) {
    $('#udssl-contact-button').html('Contacting...');
    event.preventDefault();
    $.ajax({
      type: 'POST',
      url: udssl.contact_url,
      data: {
        name: $('#name').val(),
        email: $('#email').val(),
        subject: $('#subject').val(),
        message: $('#message').val(),
        wpnonce: $('[name="_wpnonce"]').val(),
        wp_http_referer: $('[name="_wp_http_referer"]').val(),
        recaptcha_challenge_field: $('#recaptcha_challenge_field').val(),
        recaptcha_response_field: $('#recaptcha_response_field').val()
      }
    })
      .done(function(msg) {
          if(msg.response == 'success'){
              response = '<div class="alert alert-success fade in">';
              response += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
              response += '<strong>Success!</strong> ' + msg.message + '</div>';
          } else {
              response = '<div class="alert alert-warning fade in">';
              response += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
              response += '<strong>Error!</strong> ' + msg.message + '</div>';
          }
          $('#udssl-contact-response').html(response);

      })
      .always(function() {
        $('#udssl-contact-button').html('Submit');
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        Recaptcha.reload();
  });
});

}); // end document.ready
