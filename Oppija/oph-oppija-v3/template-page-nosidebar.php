<?php  /* Template Name: Sisältösivu, ei vasenta palkkia */ 


get_header( get_bloginfo('language') ); ?>

	<!-- secondary navigation -->
	<nav class="sidenav">
	</nav>
	<!-- /secondary navigation -->
	
	
	<div class="center-content">
	
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	
		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
			<h1><?php the_title(); ?></h1>
		
			<?php the_post_thumbnail('large') ?>
		
			<?php the_content(); ?>
			
			
			<?php edit_post_link(); ?>
			
		</article>
		<!-- /article -->		
	<?php endwhile; ?>
	
	<?php else: ?>
	
		<!-- article -->
		<article>
			
			<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
			
		</article>
		<!-- /article -->
	
	<?php endif; ?>
	
	</div>
		
<?php //get_sidebar(); ?>

<?php get_footer( get_bloginfo('language') ); ?>