<?php
/**
 * UDSSL Theme Contact Page
 */
get_header();
global $udssl_theme;
?>
<div class="row">
    <div class="col-md-8">
    <div class="row">
        <div class="col-md-12">
            <h1>Contact UDSSL</h1>
            <form id="udssl-contact-form" role="form" action="#">
            <fieldset>
              <legend>USB Digital Services <small class="text-muted"> | praveen.udssl@gmail.com</small></legend>
                <div id="udssl-contact-response" class="form-actions row">
                </div>
                <div class="form-group row">
                  <div class="col-lg-3">
                    <label class="control-label" for="name">Your Name</label>
                  </div>
                  <div class="col-lg-6">
                    <input type="text" class="form-control" name="name" id="name">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-3">
                    <label class="control-label" for="email">Email Address</label>
                  </div>
                  <div class="col-lg-6">
                    <input type="text" class="form-control" name="email" id="email">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-3">
                    <label class="control-label" for="subject">Subject</label>
                  </div>
                  <div class="col-lg-6">
                    <input type="text" class="form-control" name="subject" id="subject">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-3">
                    <label class="control-label" for="message">Your Message</label>
                  </div>
                  <div class="col-lg-6">
                    <textarea class="form-control" name="message" id="message" rows="3"></textarea>
                  </div>
                </div>
                <div class="form-actions row">
                  <div class="col-lg-3">
                  </div>
                  <div class="col-lg-6">
                    <?php echo $udssl_theme->contact->get_recaptcha(); ?>
                  </div>
                </div>
                <hr />
                <div class="form-actions row">
                  <div class="col-lg-3">
                  </div>
                  <div class="col-lg-6">
                      <button id="udssl-contact-button" type="submit" class="btn btn-primary btn-large">Submit</button>
                      <button type="reset" class="btn">Cancel</button>
                      <?php wp_nonce_field('udssl_contact_form'); ?>
                  </div>
                </div>
              </fieldset>
            </form>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-md-6">
            <div id="googlemaps" style="width:100%; height:200px;"></div>
        </div>
        <div class="col-md-6">
            <?php
            global $udssl_theme;
            echo $udssl_theme->sidebar->contact_options();
            ?>
        </div>
    </div>
    <hr />
    </div>
    <div class="col-md-4">
        <?php $udssl_theme->sidebar->single_right(); ?>
    </div>
</div>
<?php
get_footer();
