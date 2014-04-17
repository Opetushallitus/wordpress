<?php get_header(); ?>

    <!-- breadcrumb -->
    <nav class="breadcrumb">
        <?php if(function_exists('theme_bcn'))
        {
        	theme_bcn();
        }?>
    </nav>
    
    <?php
    
    function get_theme_name() {
    $terms = get_the_terms( $post->ID , 'story-theme' );
        if ( $terms != null ){
            foreach( $terms as $term ) {
                print $term->name ;
                unset($term);
            } 
        }
    }?>
    
    <!-- /breaddcrumb -->
	
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

            <!-- section -->
            <div class="story-single">
            <?php if (have_posts()): while (have_posts()) : the_post(); ?>

                    <!-- article -->
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                            <!-- post title -->
                            <h1>
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                            </h1>
                            <!-- /post title -->
                        
                            <!-- post thumbnail -->
                            <?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                            <?php the_post_thumbnail(); // Fullsize image for the single post ?>
                                    </a>
                            <?php endif; ?>
                            <!-- /post thumbnail -->

                            <?php the_content(); // Dynamic Content ?>

                            <?php //the_tags( __( 'Tags: ', 'html5blank' ), ', ', '<br>'); // Separated by commas with a line break at the end ?>

                            <?php edit_post_link(); // Always handy to have Edit Post Links available ?>

                            <?php comments_template(); ?>

                    </article>
                    <!-- /article -->

            <?php endwhile; ?>

            <?php else: ?>

                    <!-- article -->
                    <article>

                            <h1><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h1>

                    </article>
                    <!-- /article -->

            <?php endif; ?>

            </div>
            <!-- /story -->
	            
<?php //get_sidebar(); 
    get_template_part('related-content');
?>

<?php get_footer(); ?>