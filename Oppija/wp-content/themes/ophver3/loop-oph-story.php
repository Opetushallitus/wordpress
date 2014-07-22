<?php /*
$terms = get_terms('post_tag');
$all_terms = array();
$i = 0;

foreach ($terms as $term) :

    $term_id = $term->term_id;
    $term_slug = $term->slug;
    
    $all_terms[$i] = array($term_id, $term_slug);
    
    $i++;

endforeach;

//var_dump($all_terms);

$b = $all_terms;
echo in_array_r("video", $b) ? 'found' : 'not found';
*/
?>


<?php $taxonomy_terms = get_terms('story-theme', 'orderby=ASC&hide_empty=1&number=100'); ?>
<?php foreach($taxonomy_terms as $term) : ?>
    <?php
        //$tag_ids = array('22370', '22369', '22371');
        $tag_array = array();
        
        $tag_slugs = array('video', 'kuvakertomus', 'haastattelu', 'intervjuer', 'videor', 'foto-rapporter');
        
        foreach($tag_slugs as $tag_slug) {
            $args = array(
                'posts_per_page' => '9999',
                'post_type' => 'oph-story',
                'tag_slug__in' => $tag_slug,
                //'tag__in' => $tag_id,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'story-theme',
                        'field' => 'slug',
                        //'field' => 'term_id',
                        'terms' => $term->slug
                    )
                )
            );

            $query = new WP_Query($args);

            array_push($tag_array, count($query->posts));
        }
    ?>
    <?php
        $term_slug = $term->slug;
        $term_name = $term->name;

        $filter = array(
            'posts_per_page' => 1,
            'post_type' => 'oph-story',
            'tax_query' => array(
                'relation' => 'IN',
                array(
                    'taxonomy' => 'story-theme',
                    'field' => 'slug',
                    'terms' => $term
                )
            )
        );

        $stories_query = new WP_Query($filter);

        if($stories_query->have_posts()) : while($stories_query->have_posts()) : $stories_query->the_post();
    ?>
        <section class="story" id="<?php echo $term_slug; ?>">

            <!-- article -->
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="single-story">

                    <!-- term title -->
                    <h5>
                        <?php
                            $terms = get_the_terms($post->ID , 'story-theme');

                            echo $term_name;

                            foreach($terms as $term) {
                                $post_slug = $term->slug;
                            }
                        ?>
                    </h5>
                    <!-- /term title -->

                    <!-- story meta -->
                    <div class="story-meta">
                        <?php 
                        if($tag_array[0] > 0) :
                            _e('videos', 'html5blank'); echo ' (' . $tag_array[0] . ')';
                            
                            if($tag_array[1] > 0 || $tag_array[2] > 0) :
                                echo ', ';
                            endif;
                            
                        endif;
                        
                        if($tag_array[1] > 0) :
                            _e('stories', 'html5blank'); echo ' (' . $tag_array[1] . ')';
                            
                            if($tag_array[2] > 0) :
                                echo ', ';
                            endif;
                            
                        endif;
                        
                        if($tag_array[2] > 0) :
                            _e('interviews', 'html5blank'); echo ' (' . $tag_array[2] . ')'; 
                        endif;
                        ?>
                    </div>
                    <!-- /story meta -->

                    <?php
                        $has_attachments = get_children(
                            array(
                                'post_type' => 'attachment',
                                'post_mime_type' => 'image',
                                'post_parent' => $post->ID
                            )
                        );

                        if($has_attachments) : // Check if thumbnail exists
                    ?>
                        <?php
                            $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
                            $image_width = $image_data[1];
                            $image_height = $image_data[2];

                            if($image_height/$image_width > 1) :
                        ?>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="vertical-img">
                                <?php the_post_thumbnail('oph-small'); // Declare pixel size you need inside the array ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="horizontal-img">
                                <?php the_post_thumbnail('oph-medium'); // Declare pixel size you need inside the array ?>
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- post title -->
                    <h2>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <!-- /post title -->

                    <?php html5wp_excerpt('html5wp_index', 'html5_blank_view_article'); // Build your custom callback length in functions.php ?>

                    <p><a href="<?php print_r(get_term_link($term_slug, 'story-theme')); ?>"><?php _e('View all articles', 'html5blank'); ?></a></p>

                </div>
            </article>
            <!-- /article -->
        </section>
    <?php endwhile; ?>

    <?php else: ?>
        <article>
           <h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
        </article>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>

<?php endforeach;