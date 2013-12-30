<?php get_header(); ?>

	<!-- breadcrumb -->
	<nav class="breadcrumb">
    <?php if(function_exists('bcn_display'))
    {
        bcn_display();
    }?>
    </nav>
	<!-- /breaddcrumb -->

	<!-- secondary navigation -->
    <nav class="sidenav">
		<ul>
            <?php oph_subnavi(); ?>
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
<?php //get_sidebar(); 
    get_template_part('related-content');
?>

<?php get_footer( get_bloginfo('language') ); ?>