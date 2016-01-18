<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
	<div id="sidebar" class="grid_4">
            <div class="sidebar-list" id="sidebar-widgets">
		<ul>
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('Right sidebar') ) : ?>

			<?php endif; ?>
		</ul>
            </div>     
	</div><!-- end sidebar -->
	<div class="clear">&nbsp;</div>

