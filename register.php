<?php
/**
 * UDSSL Theme Search Page
 */
get_header();
global $udssl_theme;
?>
<div class="row">
    <div class="col-md-8">
        <h1>UDSSL Registration</h1>
        <?php if(isset($_SESSION['registration_error'])) { ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['registration_error']; ?>
        </div>
        <?php } ?>
        <div>
        <form id="udssl-registration-form" role="form" action="<?php echo get_home_url(); ?>" method="POST" >
          <div class="row">
              <div class="form-group  col-lg-12">
                  <div id="udssl-registration-response" class="form-actions">
                  </div>
              </div>
          </div>

          <!-- First Name Last Name -->
          <div class="row">
              <div class="form-group  col-lg-6">
                <label for="firstname">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name">
              </div>
              <div class="form-group col-lg-6">
                <label for="lastname">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">
              </div>
          </div>

          <!-- Username -->
          <div class="row">
              <div class="form-group col-lg-12">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Username">
              </div>
          </div>

          <!-- Email -->
          <div class="row">
              <div class="form-group col-lg-12">
                <label for="lastname">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
              </div>
          </div>

          <!-- Password -->
          <div class="row">
              <div class="form-group col-lg-6">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              </div>
              <div class="form-group col-lg-6">
                <label for="passwordconfirm">Confirm Password</label>
                <input type="password" class="form-control" id="passwordconfirm" name="passwordconfirm" placeholder="Confirm Password">
              </div>
          </div>

            <div class="form-actions row">
              <div class="col-lg-12">
                <?php echo $udssl_theme->contact->get_recaptcha(); ?>
              </div>
            </div>

          <hr />
          <div class="row">
              <div class="form-group col-lg-12">
                  <input id="newsletter" type="checkbox" name="newsletter"> Subscribe to UDSSL Newsletter
              </div>
          </div>
          <div class="row">
              <div class="form-group col-lg-12">
              <button id="udssl-register-button" type="submit" class="btn btn-default btn-lg">Register</button>
              <?php wp_nonce_field('udssl_registration_form'); ?>
              </div>
          </div>
          <hr />
        </form>
        </div>
    </div>
    <div class="col-md-4">
        <?php $udssl_theme->sidebar->single_right(); ?>
    </div>
</div>
<?php
get_footer();
