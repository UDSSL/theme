<?php
/**
 * UDSSL 404 Page
 */
get_header();
global $udssl_theme;
?>
<div class="row">
    <div class="col-md-8">
    <div id="content" class="narrowcolumn">
        <h2 class="center">Error 404 - Not Found</h2>
   </div>
    </div>
    <div class="col-md-4">
        <?php $udssl_theme->sidebar->single_right(); ?>
    </div>
</div>
<?php get_footer(); ?>
