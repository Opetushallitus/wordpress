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
    
    <div class="row padding-bottom-10">
    <!-- /breaddcrumb -->
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
    
    
    
    <div class="col-sm-16 col-md-12 col-lg-12">      
        <!-- section -->
        <div class="story-single">
        <?php if (have_posts()): while (have_posts()) : the_post(); ?>
            <?php $post_id = get_the_ID();

            $cat = get_the_terms($post_id, 'story-theme'); 
                  foreach($cat as $c) {
                      $c_name = $c->name;  ?> 
                      <div class="visible-xs sign-lookup">
                      <h3><?php echo $c_name; ?></h3>
                           <a>
                                <span class="sign">
                                    <span class="sign-inner">Katso kaikki teemaan liittyvät artikkelit</span>
                                </span>
                            </a>
                        </div>
                   <?php } ?>
               
                <!-- article -->
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                        <!-- post title -->
                        <h1>
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                        </h1>
                        <!-- /post title -->

                        <!-- /post thumbnail -->
                        <?php 

                        $page = get_query_var('page');

                        wp_reset_postdata();

                        if ( has_post_thumbnail() && $page == 1 ) : // Check if Thumbnail exists ?>
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                        <?php the_post_thumbnail(); // Fullsize image for the single post ?>
                                </a>
                        <?php endif; ?>
                        <!-- /post thumbnail -->

                        <?php the_content(); // Dynamic Content ?>
                        
                        <div class="col-md-6 col-md-offset-10 col-xs-14 col-xs-offset-1 text-center">
                        <?php

                        if ( function_exists('wp_pagenavi') ) {

                                ob_start();
                                wp_pagenavi( array( 'type' => 'multipart' ) );

                                $pagenavi = ob_get_contents();
                                ob_end_clean();

                                if ( !strstr($pagenavi, 'previouspostslink') ) $pagenavi = str_replace('<span', '<span class="previouspostslink">←</span><span', $pagenavi);
                                if ( !strstr($pagenavi, 'nextpostslink') ) $pagenavi = str_replace('</div>', '<span class="nextpostslink">→</span></div>', $pagenavi);
                                echo $pagenavi;

                        }
                        ?>
                        </div>
                        <?php //wp_link_pages(array('before' => 'Sivut:')); ?>

                        <?php //the_tags( __( 'Tags: ', 'html5blank' ), ', ', '<br>'); // Separated by commas with a line break at the end ?>

                        <div class="post-edit">
                            <?php edit_post_link(); // Always handy to have Edit Post Links available ?>
                        </div>
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
    </div>
    </div>
</div>            
	            
<?php //get_sidebar(); 
    get_template_part('related-content');
?>

<?php get_footer(); ?>