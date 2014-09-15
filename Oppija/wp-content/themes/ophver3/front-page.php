<?php get_header( get_bloginfo('language') ); ?>


        <?php
        
        $args = array(
	'post_type' => 'page',
        'order_by' => 'name',
        'order' => 'ASC',
	'tax_query' => array(
		array(
			'taxonomy' => 'oph-additional-tags',
			'field' => 'slug',
			'terms' => array('intro1', 'intro1-sv', 'intro2', 'intro2-sv', 'intro3', 'intro3-sv', 'intro4', 'intro4-sv'),
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
                 <?php if(has_post_thumbnail()) :
                    the_post_thumbnail('oph-intro');
                endif; ?>
                <p>
                    <?php the_content(); ?>
                </p>
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