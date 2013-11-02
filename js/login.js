/**
 * UDSSL Login Form Processor
 */
jQuery(document).ready(function(){

jQuery('#udssl-login-form').validate(
 {
  rules: {
    email: {
      required: true,
      email: true
    },
    password: {
      minlength: 5,
      required: true
    }
  },
  messages: {
    email: {
      required: 'Enter your email.',
      email: 'Enter a valid email address.'
    },
    password: {
      minlength: 'UDSSL passwords should be at least 8 charactors.',
      required: 'Enter your UDSSL password'
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
}); // end document.ready
