<?php $taxonomy_terms = get_terms('story-theme', 'orderby=ASC&hide_empty=1&number=100');
        //$allterms = array();
        foreach($taxonomy_terms as $term) {
            $term_slug = $term->slug;
            $term_name = $term->name;
        
            //var_dump($term_id);
            
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
<section class="story" id="<?php echo $term_slug; ?>">
<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="single-story">
            <?php
            $has_attachments = get_children(
                array(
                'post_type' => 'attachment',
                'post_mime_type' => 'image',
                'post_parent' => $post->ID
                ));
            ?>
            
            <h5><?php  
            
            $terms = get_the_terms( $post->ID , 'story-theme' ); 

            echo $term_name;
            
            //$first_term = $first_term->name;
            //echo $first_term;
            
            foreach( $terms as $term ) {
                $post_term = $term->name;
                $post_slug = $term->slug;
                //var_dump($post_term);
                //print $post_term;   
            }
            ?></h5>
                
                <?php $t = wp_get_post_tags( $post->ID, array( 'fields' => 'names' ) );
                //$tag_count = count($t)+1;
                //print_r($t); ?>
                
                
              <?php
              
              $story_tax = get_terms( 'story-theme', array(
                    'orderby' => 'count'
                ));
              
              
              foreach ( $story_tax as $cat ) {
                    $slugs[] = $cat->slug;
                    $counts[$cat->slug]['count'] = $cat->count;
                    //var_dump($cat);
                    
             }
             
             $story_tags = get_terms('post_tag', array(
                 'hide_empty' => 0,
                 'fields' => 'names'
             ));
             
            //var_dump($story_tags);
             
             foreach($story_tags as $tag){
           
                $img_stories = get_posts(array(
                    'post_type' => 'oph-story',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'story-theme',
                            'field' => 'slug',
                            'terms' => $post_slug
                        ),
                        array(
                            'taxonomy' => 'post_tag',
                            'field' => 'slug',
                            'terms' => 'kuvakertomus'
                            
                        )
                    )   
                ));
                
                
                
                $interview_stories = get_posts(array(
                    'post_type' => 'oph-story',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'story-theme',
                            'field' => 'slug',
                            'terms' => $post_slug
                        ),
                        array(
                            'taxonomy' => 'post_tag',
                            'field' => 'slug',
                            'terms' => 'haastattelu'
                            
                        )
                    )   
                ));
                
                
                
                $video_stories = get_posts(array(
                    'post_type' => 'oph-story',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        'relation' => 'AND',
                        array(
                            'taxonomy' => 'story-theme',
                            'field' => 'slug',
                            'terms' => $post_slug
                        ),
                        array(
                            'taxonomy' => 'post_tag',
                            'field' => 'slug',
                            'terms' => 'video'
                            
                        )
                    )   
                ));
            
                    $total_img = count($img_stories);
                    $total_interview = count($interview_stories);
                    $total_video = count($video_stories);      
                
             }?>
            
                <div class="story-meta"><?php echo  'Videoita ('.$total_video.'), kuvakertomuksia ('.$total_img.'), haastatteluja ('.$total_interview.')'; ?></div>

              
                
                        <?php if ($has_attachments) : // Check if thumbnail exists ?>
                                <?php $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                                        $image_width = $image_data[1];
                                        $image_height = $image_data[2];
                                        
                               if($image_height/$image_width > 1) { ?>
                
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="vertical-img">
                                            <?php the_post_thumbnail('oph-small'); // Declare pixel size you need inside the array ?>
                                    </a>       
                                    <!-- post title -->
                                    
                                    <h2>
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <!-- /post title -->
                                    
                               <?php } else { ?>
                                    
                                    
                                    
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="horizontal-img">
                                            <?php the_post_thumbnail('oph-medium'); // Declare pixel size you need inside the array ?>
                                    </a>
                                    
                                    <!-- post title -->
                                    <h2>
                                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                                    </h2>
                                    <!-- /post title -->
                                    
                                <?php } ?>
                        <?php endif; ?>
                        
                        <?php             
                        
                        if(!$has_attachments) { ?>      
                        <!-- post title -->
                        <h2>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <!-- /post title -->
                        <?php } ?>
                                    
                        <?php html5wp_excerpt('html5wp_index', 'html5_blank_view_article'); // Build your custom callback length in functions.php ?>

                        <p><a href="<?php print_r(get_term_link($post_slug, 'story-theme')); ?>"><?php _e('Katso kaikki teemaan liittyvät artikkelit', 'html5blank'); ?></a></p> 
		
            </div>
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
<?php wp_reset_postdata(); ?>
<?php endif;  }  ?>