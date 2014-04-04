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
                        'terms' => $term->slug,
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
            
            <h5><?php  
            
            $terms = get_the_terms( $post->ID , 'story-theme' ); 

            foreach( $terms as $term ) {
                $post_term = $term->name;
                $post_slug = $term->slug; 				
                print $post_term;
            }
            ?></h5>
            
                        <?php if (has_post_thumbnail()) : // Check if thumbnail exists ?>
                                <?php $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                                        $image_width = $image_data[1];
                                        $image_height = $image_data[2];
                                        
                               if($image_height/$image_width > 1) { ?>
                
                                    <a  class="vertical-img" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                            <?php the_post_thumbnail('oph-small'); // Declare pixel size you need inside the array ?>
                                    </a>       
                                    <!-- post title -->
                                    
                                    <h2>
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <!-- /post title -->
                                    
                               <?php } else { ?>
                                    
                                    <!-- post title -->
                                    <h2>
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <!-- /post title -->
                                    
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                            <?php the_post_thumbnail('oph-medium'); // Declare pixel size you need inside the array ?>
                                    </a>
                                    
                                <?php } ?>                  
                        <?php             
                        
                        if(!has_post_thumbnail()) { ?>      
                        <!-- post title -->
                        <h2>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <!-- /post title -->
                        <?php } ?>
                                    
                        <?php endif; ?>
                        
                        <?php html5wp_excerpt('html5wp_index', 'html5_blank_view_article'); // Build your custom callback length in functions.php ?>

                        <p><a href="<?php print_r(get_term_link($post_slug, 'story-theme')); ?>"><?php _e('Katso kaikki teemaan liittyvät artikkelit', 'html5blank'); ?></a></p> 
		
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

<?php endif;  }  

wp_reset_query();  
