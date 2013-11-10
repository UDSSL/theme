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
            <h3 class="text-muted">Newsletter</h3>
            <p>Keep up on our always evolving USB product features and technology. Enter your e-mail and subscribe to our newsletter.</p>
            <form class="form-inline" role="form" id="newsletterForm" action="http://udssl.us7.list-manage1.com/subscribe/post?u=287dd4d4f3442d0967dc399db&amp;id=2150edef1c" method="POST">
              <div class="form-group">
                <label class="sr-only" for="mailchimp-email">Email address</label>
                <input type="email" class="form-control" id="mailchimp-email" name="EMAIL" placeholder="Enter email">
              </div>
              <button type="submit" class="btn btn-default">Subscribe</button>
            </form>
            </div>
        </div>
    </div>
</div> <!-- /container -->

<div class="row" id="footer-bottom">
    <div class="col-md-12">
        <hr />
        <p class="text-center text-muted">
          <a class="text-muted" href="#" >UDSSL</a> <span class="glyphicon glyphicon-copyright-mark"></span> <?php echo date('Y'); ?>
        - <a class="text-muted" href="#" >Privacy Policy</a>
        - <a class="text-muted" href="#" >Terms of Service</a>
        - <a class="text-muted" href="<?php echo get_home_url(); ?>/contact/" >Contact</a>
    </p>
    </div>
</div>
<?php wp_footer(); ?>
<p id="back-top">
    <a href="#top"><span></span></a>
</p>
</body>
</html>
