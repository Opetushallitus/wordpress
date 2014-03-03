<?php $taxonomy_terms = get_terms('story-theme', 'orderby=ASC&hide_empty=1&number=100');
        //$allterms = array();
        foreach($taxonomy_terms as $term){
            $allterms = $term->slug;
        
            $filter = array(
                'posts_per_page' => 1,
                'post_type' => 'oph-story',
                'tax_query' => array(
                    'relation' => 'IN',
                    array(
                        'taxonomy' => 'story-theme',
                        'field' => 'slug',
                        'terms' => $term,
                        )
                )
            );
        $stories_query = new WP_Query($filter); 
       
          
//print_r($allterms);
?>

		
<?php if ($stories_query->have_posts()) : while ($stories_query->have_posts()) : $stories_query->the_post(); ?>
<section class="story">
<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <h5>Teema <?php  
            
            $terms = get_the_terms( $post->ID , 'story-theme' ); 
            
            //$term_sum = count($terms);
            //$i = 0;
            
            foreach( $terms as $term ) {
                $post_term = $term->name;
                $post_slug = $term->slug; 				
                print $post_term;
                /*if(!(++$i === $term_sum)) {
                    echo ', ';
                }*/
            }
            ?></h5>
            
                <!-- post title -->
		<h2>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</h2>
		<!-- /post title -->
                
		<!-- post thumbnail -->
		<?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php the_post_thumbnail('oph-medium'); // Declare pixel size you need inside the array ?>
			</a>
		<?php endif; ?>
		<!-- /post thumbnail -->
				
		<?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>

                <p><a href="<?php print_r(get_term_link($post_slug, 'story-theme')); ?>">Katso kaikki teemaan liittyvät artikkelit</a></p> 
		
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

<?php endif;  }  ?>