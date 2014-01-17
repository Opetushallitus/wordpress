<?php get_header(); ?>

    <nav class="sidenav">
        <ul>
            <li>Teema 1</li>
            <li>Teema 2</li>
            <li>Teema 3</li>
            <li>Teema 4</li>
            <li>Teema 5</li>
            <li>Teema 6</li>
            <li>Teema 7</li>
            <li>Teema 8</li>
            <li>Teema 9</li>
            <li>Teema 10</li>
            <li>Teema 11</li>
            <li>Teema 12</li>
            <li>Teema 13</li>
        </ul>
    </nav>
	
    <div class="stories">
	<!-- section -->
		<h1><?php _e( 'Tutustu tarinoihin', 'html5blank' ); ?></h1>
                
                <?php   $args = array (
                        'post_type' => 'oph-story',
                        'story-theme' => $term);
                        $first_query = new WP_Query($args); 
                ?>
	
		<?php if ($first_query->have_posts() && !is_paged()): while ($first_query->have_posts()) : $first_query->the_post(); ?>
<section class="story">
<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <h5>Teema <?php echo $term ?></h5>
            
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

	<!-- /section -->
    </div>
	

<?php get_footer(); ?>