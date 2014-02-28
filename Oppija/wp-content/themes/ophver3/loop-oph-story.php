<?php $taxonomy_terms = get_terms('story-theme', 'orderby=ASC&hide_empty=0');
        $allterms = array();
        foreach($taxonomy_terms as $term){
          $allterms[] = $term->name;
        } 

     //print_r($allterms);

        $filter = array(
            'post_type' => 'oph-story',
            //'story-theme' => 'tekniikka'
            'tax_query' => array(
                'relation' => 'IN',
                array(
                    'taxonomy' => 'story-theme',
                    'field' => 'slug',
                    'terms' => $allterms )
            )
        );
        $stories_query = new WP_Query($filter); ?>

<?php if ($stories_query->have_posts()) : while ($stories_query->have_posts()) : $stories_query->the_post(); ?>
<?php //if(has_term(array($allterms), 'story-theme')) : ?>
<section class="story">
<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            
            <h5>Teema <?php
            
            $terms = get_the_terms( $post->ID , 'story-theme' ); 
            foreach( $terms as $term ) {
                $post_term = $term->name; 
                print $post_term; 
            }?></h5>
            
                <!-- post title -->
		<h2>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</h2>
		<!-- /post title -->
                
		<!-- post thumbnail -->
		<?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php the_post_thumbnail('small'); // Declare pixel size you need inside the array ?>
			</a>
		<?php endif; ?>
		<!-- /post thumbnail -->
				
		<?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>

                <p><a href="<?php print_r(get_term_link($post_term, 'story-theme')); ?>">Katso kaikki teemaan liittyvät artikkelit</a></p> 
		
	</article>
	<!-- /article -->
</section>
<?php //endif; ?>
<?php endwhile; ?>


<?php else: ?>

	<!-- article -->
	<article>
		<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
	</article>
	<!-- /article -->

<?php endif; ?>