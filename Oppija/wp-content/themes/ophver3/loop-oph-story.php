
<?php if (have_posts()): while (have_posts()) : the_post(); ?>
<section class="story">
<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <h5>Teema <?php $terms = get_the_terms( $post->ID , 'story-theme' ); foreach( $terms as $term ) {  $post_term = $term->name; print $post_term; }?></h5>
            
		<!-- post thumbnail -->
		<?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php the_post_thumbnail('small'); // Declare pixel size you need inside the array ?>
			</a>
		<?php endif; ?>
		<!-- /post thumbnail -->
		
		<!-- post title -->
		<h2>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</h2>
		<!-- /post title -->
				
		<?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>

                <p><a href="<?php print_r(get_term_link($post_term, 'story-theme')); ?>">Katso kaikki teemaan liittyvät artikkelit</a></p> 
		
	</article>
	<!-- /article -->
</section>	
<?php endwhile; ?>


<?php else: ?>

	<!-- article -->
	<article>
		<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
	</article>
	<!-- /article -->

<?php endif; ?>