<?php
/**
 * UDSSL Theme Search Page
 */
get_header();
global $udssl_theme;
?>
<div class="row">
    <div class="col-md-8">
        <h1>UDSSL Login</h1>
        <?php if(isset($_SESSION['login_error'])) { ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['login_error']; ?>
        </div>
        <?php } ?>
        <div>
        <form id="udssl-login-form" role="form" action="<?php echo get_home_url(); ?>/login/" method="POST" >
          <div class="form-group">
            <label for="email">Email</label>
            <input type="text" class="form-control" id="email" name="email" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="remember"> Remember me
            </label>
          </div>
          <button type="submit" class="btn btn-default btn-lg">Login</button>
          <?php wp_nonce_field('udssl_login_form'); ?>
        </form>
        </div>
    </div>
    <div class="col-md-4">
        <?php $udssl_theme->sidebar->single_right(); ?>
    </div>
</div>
<?php
get_footer();
