<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
	<div id="sidebar" class="grid_4">
            <div id="sidebar-listings">
                <div class="header-announcements">
                    <h3><?php _e('Ohjeet ja materiaalit', 'oph') ?></h3>
                </div>
                <div id="sidebar-listing">
                    <ul class="sidebar-list-content">
                        <?php /* Widgetized sidebar, if you have the plugin installed. */
                            if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Left sidebar') ) : ?>

                        <?php wp_list_pages('sort_column=post_date&show_date=created&title_li='); ?>
                    
                    </ul>
                        <?php endif; ?>
                </div>
            </div>
	</div><!-- end sidebar -->
