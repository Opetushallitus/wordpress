<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
	<div id="sidebar" class="grid_4">
            <div id="sidebar-listings">
                <div class="header-announcements">
                    <h2>Ohjeet ja materiaalit</h2>
                </div>
                <div id="sidebar-listing">
                    <ul class="sidebar-list">
                                <?php 	/* Widgetized sidebar, if you have the plugin installed. */
                                                if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Left sidebar') ) : ?>

                            <?php
                                $args=array(
                                'showposts'=>5,
                                'post_type' => 'page',
                                'caller_get_posts'=>1
                                );
                             $my_query = new WP_Query($args);
                             if( $my_query->have_posts() ) {
                               while ($my_query->have_posts()) : $my_query->the_post(); ?>
                                <ul>
                                    <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
                                    <li><small><?php the_time('j.n.Y') ?> <?php the_author() ?></small></li>
                                </ul>
                                 <?php
                               endwhile;
                             } ?>

                                <?php endif; ?>
                        </ul>
                </div>
            </div>
	</div><!-- end sidebar -->
