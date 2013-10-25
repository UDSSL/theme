<?php
/**
 * UDSSL Content Template
 */
global $udssl_theme;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<div class="entry-meta">
			<?php $udssl_theme->wptheme->entry_meta_header(); ?>
		</div><!-- .entry-meta -->

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
        <?php $udssl_theme->wptheme->entry_meta_footer(); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post -->
