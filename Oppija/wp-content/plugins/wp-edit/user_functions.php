<?php
/*****************
****************** $wp_edit_pro User Specific Functions
*****************/

function wp_edit_user_specific_init() {
	
	global $current_user;
	$opts_user_meta = get_user_meta($current_user->ID, 'aaa_wp_edit_user_meta', true);
	
	
	//
	// Save Scrollbar Position
	if(isset($opts_user_meta['save_scrollbar']) && $opts_user_meta['save_scrollbar'] === '1') {
		
		final class wp_edit_Preserve_Editor_Scroll_Position {
			/**
			 * Init.
			 *
			 * @since 0.1.0
			 */
			public static function init() {
				add_filter( 'redirect_post_location', array( __CLASS__, 'add_query_arg' ) );
				add_action( 'edit_form_advanced', array( __CLASS__, 'add_input_field' ) );
				add_action( 'edit_page_form', array( __CLASS__, 'add_input_field' ) );
				add_filter( 'tiny_mce_before_init', array( __CLASS__, 'extend_tiny_mce' ) );
				add_action( 'after_wp_tiny_mce', array( __CLASS__, 'print_js' ) );
			}
		
			/**
			 * Adds a hidden input field for scrolltop value.
			 *
			 * @since 0.1.0
			 */
			public static function add_input_field() {
				$position = ! empty( $_GET['scrollto'] ) ? $_GET['scrollto'] : 0;
		
				printf( '<input type="hidden" id="scrollto" name="scrollto" value="%d"/>', esc_attr( $position ) );
			}
		
		
			/**
			 * Extend TinyMCE config with a setup function.
			 * See http://www.tinymce.com/wiki.php/API3:event.tinymce.Editor.onInit
			 *
			 * @since 0.1.0
			 *
			 * @param array $init
			 * @return array
			 */
			public static function extend_tiny_mce( $init ) {
				if ( wp_default_editor() == 'tinymce' )
					$init['setup'] = 'rich_scroll';
		
				return $init;
			}
		
		
			/**
			 * Returns redirect url with query arg for scroll position.
			 *
			 * @since 0.1.0
			 *
			 * @param string $location
			 * @return string
			 */
			public static function add_query_arg( $location ) {
				if( ! empty( $_POST['scrollto'] ) )
					$location = add_query_arg( 'scrollto', (int) $_POST['scrollto'], $location );
		
				return $location;
			}
		
			/**
			 * Prints Javascript data.
			 * On form submit the scrollTop value will be saved into the hidden input field.
			 * Includes callback function for TinyMCE scrolling.
			 *
			 * @since 0.1.0
			 */
			public static function print_js( $mce_settings ) {
				?>
				<script>
				( function( $ ) {
					$( '#post' ).submit( function() {
						// TinyMCE or HTML Editor?
						scrollto =
							$('#content' ).is(':hidden') ?
							$('#content_ifr').contents().find( 'body' ).scrollTop() :
							$('#content' ).scrollTop();
				
						// Save scrollto value
						$( '#scrollto' ).val( scrollto );
					} );
				
					// Only HTML editor: scroll to scrollto value
					$( '#content' ).scrollTop( $( '#scrollto' ).val() );
				} )( jQuery );
				<?php if ( wp_default_editor() == 'tinymce' && ! empty( $mce_settings ) ) : ?>
				/*
				 * Callback function for TinyMCE setup event
				 * See http://www.tinymce.com/wiki.php/API3:event.tinymce.Editor.onInit
				 */
				function rich_scroll( ed ) {
					ed.onInit.add( function() {
						jQuery( '#content_ifr' ).contents().find( 'body' ).scrollTop( jQuery( '#scrollto' ).val() );
					} );
				};
				<?php endif; ?>
				</script>
				<?php
			}
		}
		
		// Please load. Thanks.
		add_action( 'admin_init', array( 'wp_edit_Preserve_Editor_Scroll_Position', 'init' ) );
	}
	
	
	//
	// Add ID Column
	if(isset($opts_user_meta['id_column']) && $opts_user_meta['id_column'] === '1') {
			
		function wp_edit_column_id($defaults){
			$defaults['wps_post_id'] = __('ID');
			return $defaults;
		}
		add_filter('manage_posts_columns', 'wp_edit_column_id', 5);
		add_filter('manage_pages_columns', 'wp_edit_column_id', 5);
		function wp_edit_custom_column_id($column_name, $id){
			if($column_name === 'wps_post_id'){
				echo $id;
			}
		}
		add_action('manage_posts_custom_column', 'wp_edit_custom_column_id', 5, 2);
		add_action('manage_pages_custom_column', 'wp_edit_custom_column_id', 5, 2);
	}
	
	//
	// Add Tumbnail Column
	if(isset($opts_user_meta['thumbnail_column']) && $opts_user_meta['thumbnail_column'] === '1') {
		
		if ( !function_exists('wp_edit_AddThumbColumn') && function_exists('add_theme_support') ) {  
		// for post and page  
		add_theme_support('post-thumbnails', array( 'post', 'page' ) );  
		function wp_edit_AddThumbColumn($cols) {  
			$cols['thumbnail'] = __('Thumbnail', 'wp_edit_langs');  
			return $cols;  
		}  
		  
		function wp_edit_AddThumbValue($column_name, $post_id) {  
			$width = (int) 35;  
			$height = (int) 35;  
			if ( 'thumbnail' == $column_name ) {  
				// thumbnail of WP 2.9  
				$thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );  
				  
				// image from gallery  
				$attachments = get_children( array('post_parent' => $post_id, 'post_type' => 'attachment', 'post_mime_type' => 'image') );  
				  
				if ($thumbnail_id)  
					$thumb = wp_get_attachment_image( $thumbnail_id, array($width, $height), true );  
				elseif ($attachments) {  
					foreach ( $attachments as $attachment_id => $attachment ) {  
					$thumb = wp_get_attachment_image( $attachment_id, array($width, $height), true );  
				}  
			}  
			if ( isset($thumb) && $thumb ) { echo $thumb; }  
			else { echo __('None', 'wp_edit_langs'); }  
			}  
		}  
		  
		// for posts  
		add_filter( 'manage_posts_columns', 'wp_edit_AddThumbColumn' );  
		add_action( 'manage_posts_custom_column', 'wp_edit_AddThumbValue', 10, 2 );  
		  
		// for pages  
		add_filter( 'manage_pages_columns', 'wp_edit_AddThumbColumn' );  
		add_action( 'manage_pages_custom_column', 'wp_edit_AddThumbValue', 10, 2 );  
		}
	}
	
	
	//
	// Hide Text Tab
	if(isset($opts_user_meta['hide_text_tab']) && $opts_user_meta['hide_text_tab'] === '1') {
		
		global $pagenow;
		if ($pagenow=='post.php' || $pagenow == 'post-new.php' || ($pagenow == "admin.php" && (isset($_GET['page'])) == 'cleverness-to-do-list') || ($pagenow == "options-general.php" && (isset($_GET['page'])) == 'ultimate-tinymce')) {
			function wp_edit_user_hide_on_todo() {
				?><style type="text/css"> #excerpt-html { display: none !important; } #content-id-html { display: none !important; }  #content-html { display: none !important; } #clevernesstododescription-html { display: none !important; }</style><?php
			}
			add_filter('admin_head','wp_edit_user_hide_on_todo');
		}
	}
	
	
	//
	// Default Visual Tab
	// CURRENTLY NOT WORKING... TRAC TICKET IN PROGRESS
	if(isset($opts_user_meta['default_visual_tab']) && $opts_user_meta['default_visual_tab'] === '1') {
		
		//add_filter( 'wp_default_editor', create_function('', 'return "tmce";') );
	}
	
	
	//
	// Disable Dashboard Widget
	if(isset($opts_user_meta['dashboard_widget']) && $opts_user_meta['dashboard_widget'] != '1') {
		
		add_action('wp_dashboard_setup', 'wp_edit_user_custom_dashboard_widgets');
		function wp_edit_user_custom_dashboard_widgets() {
			global $wp_meta_boxes;
			wp_add_dashboard_widget('jwl_user_tinymce_dashboard_widget', __('WP Edit RSS Feed', 'wp_edit_langs'), 'wp_edit_user_tinymce_widget', 'wp_edit_user_configure_widget');
		}	
		function wp_edit_user_tinymce_widget() {
			$jwl_widgets = get_option( 'wp_edit_user_dashboard_options' ); // Get the dashboard widget options
			$jwl_widget_id = 'jwl_user_tinymce_dashboard_widget'; // This must be the same ID we set in wp_add_dashboard_widget
			/* Check whether we have set the post count through the controls. If we didn't, set the default to 5 */
			$jwl_total_items = isset( $jwl_widgets[$jwl_widget_id] ) && isset( $jwl_widgets[$jwl_widget_id]['items'] ) ? absint( $jwl_widgets[$jwl_widget_id]['items'] ) : 5;
			// Echo the output of the RSS Feed.
			echo '<p style="border-bottom:#000 1px solid;">Showing ('.$jwl_total_items.') Posts</p>';
			echo '<div class="rss-widget">';
				wp_widget_rss_output(array(
					'url' => 'http://www.ultimatetinymcepro.com/feed/',
					'title' => '',
					'items' => $jwl_total_items,
					'show_summary' => 0,
					'show_author' => 0,
					'show_date' => 0
				));
			echo "</div>";
			echo '<p style="text-align:center;border-top: #000 1px solid;padding:5px;"><a href="http://www.ultimatetinymcepro.com/">Ultimate Tinymce Pro</a> - Visual Wordpress Editor</p>';
		}
		function wp_edit_user_configure_widget() {
			$jwl_widget_id = 'jwl_user_tinymce_dashboard_widget'; // This must be the same ID we set in wp_add_dashboard_widget
			$jwl_form_id = 'jwl-user-dashboard-control'; // Set this to whatever you want
			// Checks whether there are already dashboard widget options in the database
			if ( !$jwl_widget_options = get_option( 'wp_edit_user_dashboard_options' ) ) {
				$jwl_widget_options = array(); // If not, we create a new array
			}
			// Check whether we have information for this form
			if ( !isset($jwl_widget_options[$jwl_widget_id]) ) {
				$jwl_widget_options[$jwl_widget_id] = array(); // If not, we create a new array
			}
			// Check whether our form was just submitted
			if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST[$jwl_form_id]) ) {
				/* Get the value. In this case ['items'] is from the input field with the name of '.$form_id.'[items] */
				$jwl_number = absint( $_POST[$jwl_form_id]['items'] );
				$jwl_widget_options[$jwl_widget_id]['items'] = $jwl_number; // Set the number of items
				update_option( 'wp_edit_user_dashboard_options', $jwl_widget_options ); // Update our dashboard widget options so we can access later
			}
			// Check if we have set the number of posts previously. If we didn't, then we just set it as empty. This value is used when we create the input field
			$jwl_number = isset( $jwl_widget_options[$jwl_widget_id]['items'] ) ? (int) $jwl_widget_options[$jwl_widget_id]['items'] : '';
			// Create our form fields. Pay very close attention to the name part of the input field.
			echo '<p><label for="jwl_user_tinymce_dashboard_widget-number">' . __('Number of posts to show:', 'wp_edit_langs') . '</label>';
			echo '<input id="jwl_user_tinymce_dashboard_widget-number" name="'.$jwl_form_id.'[items]" type="text" value="' . $jwl_number . '" size="3" /></p>';
		}
	}
	
	
	//
	// Enable Post/Page Highlights
	if(isset($opts_user_meta['enable_highlights']) && $opts_user_meta['enable_highlights'] === '1') {
	
		function wp_edit_highlight_posts_status_colors(){
			
			global $current_user;
			$opts_user_meta = get_user_meta($current_user->ID, 'aaa_wp_edit_user_meta', true);
			?>
			<style type="text/css">
			.status-draft{background-color: <?php (isset($opts_user_meta['draft_highlight']) ? print $opts_user_meta['draft_highlight'] : print '#FFFFFF'); ?> !important;}
			.status-pending{background-color: <?php (isset($opts_user_meta['pending_highlight']) ? print $opts_user_meta['pending_highlight'] : print '#FFFFFF'); ?> !important;}
			.status-publish{background-color: <?php (isset($opts_user_meta['published_highlight']) ? print $opts_user_meta['published_highlight'] : print '#FFFFFF'); ?> !important;}
			.status-future{background-color: <?php (isset($opts_user_meta['future_highlight']) ? print $opts_user_meta['future_highlight'] : print '#FFFFFF'); ?> !important;}
			.status-private{background-color: <?php (isset($opts_user_meta['private_highlight']) ? print $opts_user_meta['private_highlight'] : print '#FFFFFF'); ?> !important;}
			</style>
			<?php
		}
		add_action('admin_head','wp_edit_highlight_posts_status_colors');
	}
	
}
add_action('init', 'wp_edit_user_specific_init');






?>