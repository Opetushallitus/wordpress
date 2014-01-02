<?php get_header( get_bloginfo('language') ); ?>

	<!-- secondary navigation -->
	<nav class="sidenav">
		<ul>
<?php
	$top_level = array_reverse(get_post_ancestors($post->ID));
	add_filter('the_title', 'show_short_title', 10, 2);
	wp_list_pages( array(
	    'title_li' => '',
	    'child_of' => $top_level[0],
	    'depth'	=> 0,
	    ) );
	remove_filter('the_title', 'show_short_title');	
	?>
	
		</ul>
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