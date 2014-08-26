<?php get_header( get_bloginfo('language') ); ?>


        <?php
        
        $args = array(
	'post_type' => 'page',
        'order_by' => 'name',
        'order' => 'ASC',
	'tax_query' => array(
		array(
			'taxonomy' => 'post_tag',
			'field' => 'slug',
			'terms' => array('intro1', 'intro2', 'intro3', 'intro4'),
		),
            ),
        );
        
        $intro_pages = new WP_Query($args);
        
        ?>

        <div class="row padding-bottom-10">

	<?php if ($intro_pages->have_posts()): while ($intro_pages->have_posts()) : $intro_pages->the_post(); ?>

            
                    <div class="col-sm-16 col-md-4 col-lg-4 frontpage-link">
                        <div class="row col-xs-16">
                            <h2 class=""><?php the_title(); ?></h2>    
                            <?php the_content(); ?>  
                        </div>
                    </div>
                

                

	<?php endwhile; ?>

	<?php else: ?>

		<!-- article -->
		<article>

			<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>

		</article>
		<!-- /article -->

	<?php endif; ?>

        </div>

<?php //get_sidebar('widget-area-2'); ?>

<?php get_footer ( get_bloginfo('language') ); ?>