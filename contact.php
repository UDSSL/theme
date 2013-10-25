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
            <form role="form" action="<?php echo get_home_url(); ?>/contact/" method="POST">
              <div class="form-group">
                <label for="contacting-name">Name</label>
                <input type="text" class="form-control" id="contacting-name" name="name" placeholder="Enter name">
              </div>
              <div class="form-group">
                <label for="contacting-email">Email address</label>
                <input type="email" class="form-control" id="contacting-email" name="email" placeholder="Enter email">
              </div>
              <div class="form-group">
                <label for="contacting-message">Message</label>
                <textarea class="form-control" id="contacting-message" name="message" placeholder="Enter your message"></textarea>
              </div>
              <button type="submit" class="btn btn-default">Submit</button>
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
    </div>
    <div class="col-md-4">
        <?php $udssl_theme->sidebar->single_right(); ?>
    </div>
</div>
<?php
get_footer();
