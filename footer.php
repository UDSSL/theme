<?php
/**
 * UDSSL Footer
 */
$options = get_option('udssl_options');
?>
    <div class="row">
        <div class="col-md-4">
            <div class="footer-box">
            <?php if ( ! dynamic_sidebar('Footer Left') ) : ?>
                <li>{static sidebar item 1}</li>
            <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="footer-box">
            <?php if ( ! dynamic_sidebar('Footer Middle') ) : ?>
                <li>{static sidebar item 1}</li>
            <?php endif; ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="footer-box">
            <h3>Newsletter</h3>
            <p>Keep up on our always evolving USB product features and technology. Enter your e-mail and subscribe to our newsletter.</p>

            <div class="alert alert-success hidden" id="newsletterSuccess">
                <strong>Success!</strong> You've been added to our email list.
            </div>

            <div class="alert alert-error hidden" id="newsletterError"></div>

            <form class="form-inline" role="form" id="newsletterForm" action="<?php echo home_url(); ?>/mailchimp-subscribe/" method="POST">
              <div class="form-group">
                <label class="sr-only" for="mailchimp-email">Email address</label>
                <input type="email" class="form-control" id="mailchimp-email" name="email" placeholder="Enter email">
              </div>
              <button type="submit" class="btn btn-default">Subscribe</button>
            </form>
            </div>
        </div>
    </div>
</div> <!-- /container -->

<div class="row" id="footer-bottom">
    <div class="col-md-12">
        <p class="text-center text-muted"><a href="<?php echo get_home_url(); ?>/about/" >UDSSL</a> <span class="glyphicon glyphicon-copyright-mark"></span> <?php echo date('Y'); ?>
        | <a href="<?php echo get_home_url(); ?>/content-policy/" >Content Policy</a>
        | <a href="<?php echo get_home_url(); ?>/privacy-policy/" >Privacy Policy</a>
        | <a href="<?php echo get_home_url(); ?>/terms-of-service/" >Terms of Service</a>
        | <a href="<?php echo get_home_url(); ?>/contact/" >Contact</a></p>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
