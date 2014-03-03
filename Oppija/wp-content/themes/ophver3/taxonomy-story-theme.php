<?php get_header(); ?>

    <!-- breadcrumb -->
<nav class="breadcrumb">
        <!-- Breadcrumb NavXT 4.4.0 -->
        <a title="Go to Opintopolku DEV." href="<?php echo home_url(); ?>" class="home"><?php bloginfo('name'); ?></a><span>&gt; </span>
        <a title="Go to Valintojen tuki." href="<?php echo home_url(); ?>/valintojen-tuki/" class="page">Valintojen tuki</a><span>&gt; </span>
         <a title="Go to Valintojen tuki." href="<?php echo home_url(); ?>/valintojen-tuki/tutustu-tarinoihin" class="page">Tutustu tarinoihin</a>
        </nav>
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

<h1><?php _e( 'Tutustu tarinoihin', 'html5blank' ); ?></h1>
	
    <?php   $args = array (
            'post_type' => 'oph-story',
            'story-theme' => $term);
    
            $first_query = new WP_Query($args); 
    ?>
    <div class="stories">
	<!-- section -->
    <?php if ($first_query->have_posts() && !is_paged()): while ($first_query->have_posts()) : $first_query->the_post(); ?>
        
        <?php
        function get_post_term() {
            $terms = get_the_terms( $post->ID , 'story-theme' ); 
            foreach( $terms as $term ) {
                $post_term = $term->name; 
                print $post_term; 
                }
        }
        ?>
                
        <section class="story">
        <!-- article -->
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                  
                    
                    <h5>Teema <?php get_post_term();?></h5>

                        <!-- post thumbnail -->
                        <?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                        <?php the_post_thumbnail('oph-medium'); // Declare pixel size you need inside the array ?>
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
                        
        <section class="story-notfound">
            <!-- article -->
            <article>
                    <h2><?php _e( 'Ei löytynyt.', 'html5blank' ); ?></h2>
            </article>
            <!-- /article -->
        </section>

<?php endif; ?>
        
    </div>
    <!-- /stories -->           

<?php get_footer();