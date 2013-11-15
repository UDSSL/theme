<?php
/**
 * UDSSL Subscribe Page
 */
get_header();
global $udssl_theme;
?>
<div class="row">
    <div class="col-md-8">
    <div class="row">
        <div class="col-md-12">
            <h1>Subscribe to UDSSL</h1>
            <div class="alert alert-warning"><b>Premium Content:</b> You must be logged in to access the content.</div>
            <div class="row" style="min-height: 500px;">
            <div class="col-md-12">
                <p class="text-center">
                <a href="<?php echo get_home_url(); ?>/signup/" class="btn btn-success btn-lg">Sign Up Free</a>
                <a href="<?php echo get_home_url(); ?>/login/" class="btn btn-default btn-lg">Login</a>
                </p>
                <p class="text-center">Login to access the content. Sign up now. It's free!</p>
            </div>
            </div>
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
