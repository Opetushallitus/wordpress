<?php get_header(); ?>
	
	<!-- section -->
	<section role="main">
		<h1><?php _e( 'Tutustu tarinoihin teemoittain', 'html5blank' ); ?></h1>
	
		<?php get_template_part('loop-oph-story'); ?>
		
		<?php get_template_part('pagination'); ?>
	
	</section>
	<!-- /section -->
	
<?php //get_sidebar(); ?>

<?php get_footer(); ?>