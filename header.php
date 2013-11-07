<?php
/**
 * UDSSL Header
 */
$options = get_option('udssl_options');
global $udssl_theme;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <!-- UDSSL Head -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <?php do_action('wp_head_top'); ?>
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php include 'google-analytics.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <?php echo $udssl_theme->header->top_navigation(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <a href="<?php echo home_url(); ?>" ><img class="img-left img-responsive" src="<? echo UDS_URL; ?>assets/udssl-header-logo.png" alt="UDSSL Logo" /></a>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    global $udssl_theme;
                    echo $udssl_theme->sidebar->social_icons();
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 site-title">
                    <h3 class="text-muted">USB Digital Services</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <hr />
            <?php //echo $udssl_theme->header->main_navigation(); ?>
        </div>
    </div>
