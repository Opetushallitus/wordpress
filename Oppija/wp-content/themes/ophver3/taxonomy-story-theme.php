<?php get_header(); ?>

    <!-- breadcrumb -->
    <nav class="breadcrumb">
        <?php if(function_exists('theme_bcn'))
        {
            theme_bcn();
        }?>
    </nav>
    <!-- /breaddcrumb -->

 <div class="row padding-bottom-10">
<div class="col-sm-16 col-sm-16 col-sm-16">
<div class="col-sm-16 col-md-4 col-lg-4">     
    <nav class="sidenav">
        <ul>
            <li>
                <ul class="stories-sidenav">
            <?php
            
                function get_custom_terms($taxonomies){
                    $args = array('orderby'=>'asc','hide_empty'=>false);
                    $custom_terms = get_terms(array($taxonomies), $args);
                    
                    foreach($custom_terms as $term){
                        echo '<li class="page_item"><a href="'. get_term_link($term) .'" class="expanded"><span>'. $term->name.'</span></a></li>';
                    }
                }
                
                get_custom_terms('story-theme'); 
                
                 ?>
                </ul>
            </li>
        </ul>
    </nav>
</div>    

<h1><?php _e( 'Tutustu tarinoihin', 'html5blank' ); ?></h1>
<h5 class="theme-term"><?php single_cat_title(); ?></h5>
	
    <?php   $args = array (
            'posts_per_page' => '9999',
            'post_type' => 'oph-story',
            'story-theme' => $term);
    
            $first_query = new WP_Query($args); 
    ?>
    <div class="col-sm-16 col-md-12 col-lg-12">  
    <div class="stories">
	<!-- section -->
    <?php if ($first_query->have_posts() && !is_paged()): while ($first_query->have_posts()) : $first_query->the_post(); ?>
                
        <section class="story">
        <!-- article -->
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="single-story-theme">
                  
                    <?php if ( has_post_thumbnail()) : ?>
                        
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
                                    
                                    <!-- post title -->
                                <h2>
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                                </h2>
                                    <!-- /post title -->
                                    
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="horizontal-img">
                                    <?php the_post_thumbnail('oph-medium'); // Declare pixel size you need inside the array ?>
                                </a>
                                    
                                <?php } ?>

                        <?php endif; ?>

                        <?php if(!has_post_thumbnail()) { ?>      
                        <!-- post title -->
                        <h2>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                        </h2>
                        <!-- /post title -->
                        <?php } ?>            
   
                        <?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>
                    </div>
                </article>
                <!-- /article -->
                
        </section>
                
     
<?php endwhile; ?>


<?php else: ?>
                        
        <section class="story-notfound">
            <!-- article -->
            <article>
                    <h2><?php _e( 'Ei löytynyt.', 'html5blank' ); ?></h2>
            </article>
            <!-- /article -->
        </section>

<?php endif; ?>
    </div>  
    </div> <!-- /stories -->  
</div>             

<?php get_footer();