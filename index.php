<?php
/**
 * UDSSL Theme Index
 */
get_header();
global $udssl_theme;
?>
<div class="row">
    <div class="col-md-8">
        <?php if ( have_posts() ) : ?>
            <?php while ( have_posts() ) : the_post(); ?>
                <?php get_template_part('content'); ?>
            <?php endwhile; ?>
        <?php else : ?>
            <?php get_template_part('content'); ?>
        <?php endif; ?>
    </div>
    <div class="col-md-4">
        <?php $udssl_theme->sidebar->single_right(); ?>
    </div>
</div>
<?php
get_footer();
?>
