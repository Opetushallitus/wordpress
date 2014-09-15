<?php
/**
 * Plugin Name: WP Edit
 * Plugin URI: http://wpeditpro.com
 * Description: Ultimate WordPress Content Editing.
 * Version: 1.8
 * Author: Josh Lobe
 * Author URI: http://wpeditpro.com
 * License: GPL2
*/

/*
****************************************************************
Load plugin translation
****************************************************************
*/
add_action('plugins_loaded', 'wp_edit_load_translation');
function wp_edit_load_translation() {
	
 	load_plugin_textdomain( 'wp_edit_langs', false, dirname( plugin_basename( __FILE__ ) ) . '/langs/' );
}


/*
****************************************************************
Begin Plugin Class
****************************************************************
*/
class wp_edit {
	
	/*
	****************************************************************
	Define WP Edit Plugin Options
	****************************************************************
	*/
	public $global_options_global = array(
					'jquery_theme' => 'smoothness',
					'disable_admin_links' => '0'
				);
	public $global_options_buttons = array(
					'toolbar1' => 'bold italic strikethrough bullist numlist blockquote alignleft aligncenter alignright link unlink wp_more fullscreen hr', 
					'toolbar2' => 'formatselect underline alignjustify forecolor pastetext removeformat charmap outdent indent undo redo wp_help', 
					'toolbar3' => '', 
					'toolbar4' => '',
					'tmce_container' => 'fontselect fontsizeselect styleselect backcolor media rtl ltr table anchor code emoticons inserttime wp_page preview print searchreplace visualblocks subscript superscript image_orig advlink'
				);
	public $global_options_buttons_sidebars = array(
					'add_opts' => array(
						'disable_ajax' => '0'
					),
					'tinymce_opts' => array(
						'menu_bar' => '0',
						'context_menu' => '0'
					)
				);
	public $global_options_general = array(
					'linebreak_shortcode' => '0',
					'shortcodes_in_widgets' => '0',
					'shortcodes_in_excerpts' => '0',
					'post_excerpt_editor' => '0',
					'page_excerpt_editor' => '0',
					'profile_editor' => '0',
					'php_widgets' => '0'
				);
	public $global_options_posts = array(
					'post_title_field' => 'Enter title here',
					'max_post_revisions' => '',
					'max_page_revisions' => '',
					'delete_revisions' => '0',
					'hide_admin_posts' => '',
					'hide_admin_pages' => '',
					'disable_wpautop' => '0',
					'column_shortcodes' => '0'
				);
	public $global_options_editor = array(
					'enable_editor' => '0',
					'editor_font' => 'Verdana, Arial, Helvetica, sans-serif',
					'editor_font_color' => '000000',
					'editor_font_size' => '13px',
					'editor_line_height' => '19px',
					'editor_body_padding' => '0px',
					'editor_body_margin' => '10px',
					'editor_text_direction' => 'ltr',
					'editor_text_indent' => '0px',
					'editor_bg_color' => 'FFFFFF',
					'editor_tinymce_px' => '0'
				);
	public $global_options_fonts = array(
					'enable_google_fonts' => '0',
					'google_font_link' => "<link href='http://fonts.googleapis.com/css?family=Freckle+Face' rel='stylesheet' type='text/css'>",
					'save_google_fonts' => ''
				);
	public $global_options_widgets = array(
					'widget_builder' => '0',
				);
	public $global_options_user_specific = array(
					'save_scrollbar' => '0',
					'id_column' => '0',
					'thumbnail_column' => '0',
					'hide_text_tab' => '0',
					'default_visual_tab' => '0',
					'dashboard_widget' => '0',
					'enable_highlights' => '0',
					
					'draft_highlight' => '#FFFFFF',
					'pending_highlight' => '#FFFFFF',
					'published_highlight' => '#FFFFFF',
					'future_highlight' => '#FFFFFF',
					'private_highlight' => '#FFFFFF'
				);
	public $global_options_extras = array(
					'signoff_text' => 'Please enter text here...',
					'enable_qr' => '0',
					'enable_qr_widget' => '0',
					'qr_colors' => array(
						'background_title' => 'e2e2e2',
						'background_content' => 'dee8e4',
						'text_color' => '000000',
						'qr_foreground_color' => 'c4d7ed',
						'qr_background_color' => '120a23',
						'title_text' => 'Enter title here...',
						'content_text' => 'Enter content here...'
					)
				);
	
	/*
	****************************************************************
	Activation hook
	****************************************************************
	*/
	public function plugin_activate() {
		
		global $current_user;
		
		// Get DB values
		$options_global = get_option('wp_edit_global');
		$options_buttons = get_option('wp_edit_buttons');
		$options_buttons_sidebars = get_option('wp_edit_buttons_sidebars');
		$options_general = get_option('wp_edit_general');
		$options_posts = get_option('wp_edit_posts');
		$options_editor = get_option('wp_edit_editor');
		$options_fonts = get_option('wp_edit_fonts');
		$options_widgets = get_option('wp_edit_widgets');
		$options_user_specific = get_user_meta($current_user->ID, 'aaa_wp_edit_user_meta', true);
		$options_extras = get_option('wp_edit_extras');
		
		// Check if DB value exists.. if YES, then keep value.. if NO, then replace with protected defaults
		$options_global = $options_global ? $options_global : $this->global_options_global;
		$options_buttons = $options_buttons ? $options_buttons : $this->global_options_buttons;
		$options_buttons_sidebars = $options_buttons_sidebars ? $options_buttons_sidebars : $this->global_options_buttons_sidebars;
		$options_general = $options_general ? $options_general : $this->global_options_general;
		$options_posts = $options_posts ? $options_posts : $this->global_options_posts;
		$options_editor = $options_editor ? $options_editor : $this->global_options_editor;
		$options_fonts = $options_fonts ? $options_fonts : $this->global_options_fonts;
		$options_widgets = $options_widgets ? $options_widgets : $this->global_options_widgets;
		$options_user_specific = $options_user_specific ? $options_user_specific : $this->global_options_user_specific;
		$options_extras = $options_extras ? $options_extras : $this->global_options_extras;
		
		// Set DB values
		update_option('wp_edit_global', $options_global);
		update_option('wp_edit_buttons', $options_buttons);
		update_option('wp_edit_buttons_sidebars', $options_buttons_sidebars);
		update_option('wp_edit_general', $options_general);
		update_option('wp_edit_posts', $options_posts);
		update_option('wp_edit_editor', $options_editor);
		update_option('wp_edit_fonts', $options_fonts);
		update_option('wp_edit_widgets', $options_widgets);
		update_user_meta($current_user->ID, 'aaa_wp_edit_user_meta', $options_user_specific);
		update_option('wp_edit_extras', $options_extras);
		
		// Add option for redirect
		add_option('wp_edit_activation_redirect', true);
	}
	
	
	/*
	****************************************************************
	Class construct
	****************************************************************
	*/
	public function __construct() {
		
		register_activation_hook( __FILE__, array( $this, 'plugin_activate' ) );  // Plugin activation hook
		
		add_action('admin_menu', array($this, 'add_page'));  // Register main admin page
		add_filter('plugin_action_links_'.plugin_basename(__FILE__), array($this, 'plugin_settings_link'));  // Set plugin settings links
		
		add_action('admin_init', array($this, 'wp_edit_redirect'));  // Redirect after plugin activation
		add_action('admin_init', array($this, 'process_settings_export'));  // Export db options
		add_action('admin_init', array($this, 'process_settings_import'));  // Import db options
	}
	
	
	
	/*
	****************************************************************
	Page Functions
	****************************************************************
	*/
	public function add_page() {
		
		$wp_edit_page = add_menu_page(__('WP Edit', 'wp_edit_langs'), __('WP Edit', 'wp_edit_langs'), 'manage_options', 'wp_edit_options', array($this, 'options_do_page'));
		add_action('admin_print_scripts-'.$wp_edit_page, array($this, 'admin_scripts'));
		add_action('admin_print_styles-'.$wp_edit_page, array($this, 'admin_styles'));
		add_action('load-'.$wp_edit_page, array($this, 'load_page'));  // Checks and validation
	}
	public function admin_scripts() {
		
		$options_sidebars_buttons = get_option('wp_edit_buttons_sidebars');
		$disable_ajax = $options_sidebars_buttons['add_opts']['disable_ajax'];
		
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-dialog');
		wp_enqueue_script('jquery-ui-tooltip');
		wp_enqueue_script('jquery-ui-droppable');
		wp_enqueue_script('jquery-ui-draggable');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-selectable');
		wp_enqueue_script('jquery-ui-button');
		wp_enqueue_script('wp-color-picker');
		
		wp_register_script( 'wp_edit_js', plugin_dir_url( __FILE__ ) . 'js/admin.js', array() ); // Main Admin Page Script File
		wp_enqueue_script( 'wp_edit_js' );
		
		// Pass WP variables to main JS script
        $wp_vars = array( 'jwl_plugin_url' => plugin_dir_url( __FILE__ ), 'disable_ajax' => $disable_ajax);
        wp_localize_script( 'wp_edit_js', 'jwlWpVars', $wp_vars);  // Set wp-content
	}
	public function admin_styles() {
		
		$options = get_option('wp_edit_global');
		$select_theme = isset($options['jquery_theme']) ? $options['jquery_theme'] : 'smoothness';
		
		wp_enqueue_style( 'wp-color-picker' );
		?><link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/<?php echo $select_theme; ?>/jquery-ui.css"><?php
		?><link rel="stylesheet" href="<?php echo includes_url().'js/tinymce/skins/lightgray/skin.min.css' ?>"><?php
		
		wp_register_style('wp_edit_css', plugin_dir_url( __FILE__ ) . ('css/admin.css'), array());  // css for admin panel presentation
		wp_enqueue_style('wp_edit_css');
		wp_enqueue_style('dashicons');
	}
	
	
	/*
	****************************************************************
	Load Page
	****************************************************************
	*/
	public function load_page() {
		
		global $current_user;
		get_currentuserinfo();
		
		$options_global = get_option('wp_edit_global');
		$options_general = get_option('wp_edit_general');
		$options_posts = get_option('wp_edit_posts');
		$options_buttons_sidebars = get_option('wp_edit_buttons_sidebars');
		$options_editor = get_option('wp_edit_editor');
		$options_fonts = get_option('wp_edit_fonts');
		$options_extras = get_option('wp_edit_extras');
		$options_widgets = get_option('wp_edit_widgets');
		$options_user_specific_user_meta = get_user_meta($current_user->ID, 'aaa_wp_edit_user_meta', true);
		
		//var_dump($_POST);
		
		/*
		****************************************************************
		Page Submissions
		****************************************************************
		*/
		if(isset($_POST['submit'])) {
			
			// If User Specific was submitted
			$post_vars = isset($_POST['wp_edit_user_specific']) ? $_POST['wp_edit_user_specific'] : '';
			
			$options_user_specific_user_meta['save_scrollbar'] = isset($post_vars['save_scrollbar']) ? '1' : '0';
			$options_user_specific_user_meta['id_column'] = isset($post_vars['id_column']) ? '1' : '0';
			$options_user_specific_user_meta['thumbnail_column'] = isset($post_vars['thumbnail_column']) ? '1' : '0';
			$options_user_specific_user_meta['hide_text_tab'] = isset($post_vars['hide_text_tab']) ? '1' : '0';
			$options_user_specific_user_meta['default_visual_tab'] = isset($post_vars['default_visual_tab']) ? '1' : '0';
			$options_user_specific_user_meta['dashboard_widget'] = isset($post_vars['dashboard_widget']) ? '1' : '0';
			
			$options_user_specific_user_meta['enable_highlights'] = isset($post_vars['enable_highlights']) ? '1' : '0';
			$options_user_specific_user_meta['draft_highlight'] = isset($post_vars['draft_highlight']) ? $post_vars['draft_highlight'] : '';
			$options_user_specific_user_meta['pending_highlight'] = isset($post_vars['pending_highlight']) ? $post_vars['pending_highlight'] : '';
			$options_user_specific_user_meta['published_highlight'] = isset($post_vars['published_highlight']) ? $post_vars['published_highlight'] : '';
			$options_user_specific_user_meta['future_highlight'] = isset($post_vars['future_highlight']) ? $post_vars['future_highlight'] : '';
			$options_user_specific_user_meta['private_highlight'] = isset($post_vars['private_highlight']) ? $post_vars['private_highlight'] : '';
			
			update_user_meta($current_user->ID, 'aaa_wp_edit_user_meta', $options_user_specific_user_meta);
				
			function user_specific_saved_notice(){
				
				echo '<div class="updated"><p>';
				_e('User specific options saved.', 'wp_edit_langs');
				echo '</p></div>';
			}
			add_action('admin_notices', 'user_specific_saved_notice');
		}
		
		/*
		****************************************************************
		If Buttons Sidebars Add Opts button was submitted
		****************************************************************
		*/
		if(isset($_POST['save_buttons_sidebars'])) {
			
			$options_buttons_sidebars['add_opts']['disable_ajax'] = isset($_POST['add_opts']['disable_ajax']) ? '1' : '0';
			
			update_option('wp_edit_buttons_sidebars', $options_buttons_sidebars);
				
			function buttons_sidebars_saved_notice(){
				
				echo '<div class="updated"><p>';
				_e('Additional options saved.', 'wp_edit_langs');
				echo '</p></div>';
			}
			add_action('admin_notices', 'buttons_sidebars_saved_notice');
		}
		
		/*
		****************************************************************
		If Buttons Sidebars Tinymce Opts button was submitted
		****************************************************************
		*/
		if(isset($_POST['save_buttons_sidebars_tinymce'])) {
			
			$options_buttons_sidebars['tinymce_opts']['menu_bar'] = isset($_POST['tinymce_opts']['menu_bar']) ? '1' : '0';
			$options_buttons_sidebars['tinymce_opts']['context_menu'] = isset($_POST['tinymce_opts']['context_menu']) ? '1' : '0';
			
			update_option('wp_edit_buttons_sidebars', $options_buttons_sidebars);
				
			function buttons_sidebars_tinymce_saved_notice(){
				
				echo '<div class="updated"><p>';
				_e('Tinymce options saved.', 'wp_edit_langs');
				echo '</p></div>';
			}
			add_action('admin_notices', 'buttons_sidebars_tinymce_saved_notice');
		}
		
		/*
		****************************************************************
		If Global Tab button was submitted
		****************************************************************
		*/
		if(isset($_POST['submit_global'])) {
		
			$options_global['jquery_theme'] = isset($_POST['jquery_theme']) ? $_POST['jquery_theme'] : 'smoothness';
			$options_global['disable_admin_links'] = isset($_POST['disable_admin_links']) ? '1' : '0';
			
			update_option('wp_edit_global', $options_global);
			
			function global_saved_notice(){
				
				echo '<div class="updated"><p>';
				_e('Global options successfully saved.', 'wp_edit_langs');
				echo '</p></div>';
			}
			add_action('admin_notices', 'global_saved_notice');
		}
		
		/*
		****************************************************************
		If General Tab button was submitted
		****************************************************************
		*/
		if(isset($_POST['submit_general'])) {
		
			$options_general['linebreak_shortcode'] = isset($_POST['linebreak_shortcode']) ? '1' : '0';
			$options_general['shortcodes_in_widgets'] = isset($_POST['shortcodes_in_widgets']) ? '1' : '0';
			$options_general['shortcodes_in_excerpts'] = isset($_POST['shortcodes_in_excerpts']) ? '1' : '0';
			$options_general['post_excerpt_editor'] = isset($_POST['post_excerpt_editor']) ? '1' : '0';
			$options_general['page_excerpt_editor'] = isset($_POST['page_excerpt_editor']) ? '1' : '0';
			$options_general['profile_editor'] = isset($_POST['profile_editor']) ? '1' : '0';
			$options_general['php_widgets'] = isset($_POST['php_widgets']) ? '1' : '0';
			
			update_option('wp_edit_general', $options_general);
			
			function general_saved_notice(){
				
				echo '<div class="updated"><p>';
				_e('General options successfully saved.', 'wp_edit_langs');
				echo '</p></div>';
			}
			add_action('admin_notices', 'general_saved_notice');
		}
		
		/*
		****************************************************************
		If Posts Tab button was submitted
		****************************************************************
		*/
		if(isset($_POST['submit_posts'])) {
			
			// Delete database revisions
			if(isset($_POST['submit_posts']) && isset($_POST['delete_revisions'])) {
				
				function wp_edit_delete_revisions_admin_notice( ){	
				
					global $wpdb;
				
					// Get pre DB size
					$query = $wpdb->get_results( "SHOW TABLE STATUS", ARRAY_A );
					$size = 0;
					foreach ($query as $row) {
						$size += $row["Data_length"] + $row["Index_length"];
					}
					$decimals = 2;  
					$mbytes = number_format($size/(1024*1024),$decimals);
					
					// Delete Post Revisions from DB
					$query3_raw = "DELETE FROM wp_posts WHERE post_type = 'revision'";
					$query3 = $wpdb->query($query3_raw);
					if ($query3) {
						$deleted_rows = __('Revisions successfully deleted', 'wp_edit_langs');
					} else {
						$deleted_rows = __('No POST revisions were found to delete', 'wp_edit_langs');
					}
					
					// Get post DB size
					$query2 = $wpdb->get_results( "SHOW TABLE STATUS", ARRAY_A );
					$size2 = 0;
					foreach ($query2 as $row2) {
						$size2 += $row2["Data_length"] + $row2["Index_length"];
					}
					$decimals2 = 2;  
					$mbytes2 = number_format($size2/(1024*1024),$decimals2); 
					
					echo '<div class="updated"><p>';
					_e('Message: ', 'wp_edit_langs');
					echo '<strong>'.$deleted_rows.'</strong>.</p><p>';
					_e('Database size before deletions: ', 'wp_edit_langs');
					echo '<strong>'.$mbytes.'</strong> ';
					_e('megabytes.', 'wp_edit_langs');
					echo '</p><p>';
					_e('Database Size after deletions: ', 'wp_edit_langs');
					echo '<strong>'.$mbytes2.'</strong> ';
					_e('megabytes.', 'wp_edit_langs');
					echo '</p></div>';
				}
				add_action('admin_notices', 'wp_edit_delete_revisions_admin_notice');
			}
	
		
			$options_posts['post_title_field'] = isset($_POST['post_title_field']) ? $_POST['post_title_field'] : 'Enter title here';
			$options_posts['column_shortcodes'] = isset($_POST['column_shortcodes']) ? '1' : '0';
			$options_posts['disable_wpautop'] = isset($_POST['disable_wpautop']) ? '1' : '0';
			
			$options_posts['max_post_revisions'] = isset($_POST['max_post_revisions']) ? $_POST['max_post_revisions'] : '';
			$options_posts['max_page_revisions'] = isset($_POST['max_page_revisions']) ? $_POST['max_page_revisions'] : '';
			
			$options_posts['hide_admin_posts'] = isset($_POST['hide_admin_posts']) ? $_POST['hide_admin_posts'] : '';
			$options_posts['hide_admin_pages'] = isset($_POST['hide_admin_pages']) ? $_POST['hide_admin_pages'] : '';
			
			update_option('wp_edit_posts', $options_posts);
			
			function posts_saved_notice(){
				
				echo '<div class="updated"><p>';
				_e('Posts/Pages options successfully saved.', 'wp_edit_langs');
				echo '</p></div>';
			}
			add_action('admin_notices', 'posts_saved_notice');
		}
		
		/*
		****************************************************************
		If Editor button was submitted
		****************************************************************
		*/
		if(isset($_POST['submit_editor'])) {
			
			$options_editor['enable_editor'] = isset($_POST['enable_editor']) ? '1' : '0';
			
			$options_editor['editor_font'] = isset($_POST['editor_font']) ? $_POST['editor_font'] : 'Verdana, Arial, Helvetica, sans-serif';
			$options_editor['editor_font_color'] = isset($_POST['editor_font_color']) ? $_POST['editor_font_color'] : '000000';
			$options_editor['editor_font_size'] = isset($_POST['editor_font_size']) ? $_POST['editor_font_size'] : '13px';
			$options_editor['editor_line_height'] = isset($_POST['editor_line_height']) ? $_POST['editor_line_height'] : '19px';
			$options_editor['editor_body_padding'] = isset($_POST['editor_body_padding']) ? $_POST['editor_body_padding'] : '0px';
			$options_editor['editor_body_margin'] = isset($_POST['editor_body_margin']) ? $_POST['editor_body_margin'] : '10px';
			$options_editor['editor_text_direction'] = isset($_POST['editor_text_direction']) ? $_POST['editor_text_direction'] : 'ltr';
			$options_editor['editor_text_indent'] = isset($_POST['editor_text_indent']) ? $_POST['editor_text_indent'] : '0px';
			$options_editor['editor_bg_color'] = isset($_POST['editor_bg_color']) ? $_POST['editor_bg_color'] : 'FFFFFF';
			$options_editor['editor_tinymce_px'] = isset($_POST['editor_tinymce_px']) ? '1' : '0';
			
			update_option('wp_edit_editor', $options_editor);
				
			function editor_saved_notice(){
				
				echo '<div class="updated"><p>';
				_e('Editor options successfully saved.', 'wp_edit_langs');
				echo '</p></div>';
			}
			add_action('admin_notices', 'editor_saved_notice');
		}
		
		/*
		****************************************************************
		If Fonts button was submitted
		****************************************************************
		*/
		if(isset($_POST['submit_fonts'])) {
			
			$options_fonts['enable_google_fonts'] = isset($_POST['enable_google_fonts']) ? '1' : '0';
			$options_fonts['google_font_link'] = isset($_POST['google_font_link']) ? stripslashes($_POST['google_font_link']) : "<link href='http://fonts.googleapis.com/css?family=Freckle+Face' rel='stylesheet' type='text/css'>";
			$options_fonts['save_google_fonts'] = isset($_POST['save_google_fonts']) ? $_POST['save_google_fonts'] : '';
			
			update_option('wp_edit_fonts', $options_fonts);
				
			function fonts_saved_notice(){
				
				echo '<div class="updated"><p>';
				_e('Font options successfully saved.', 'wp_edit_langs');
				echo '</p></div>';
			}
			add_action('admin_notices', 'fonts_saved_notice');
		}
		
		/*
		****************************************************************
		If Extras button was submitted
		****************************************************************
		*/
		if(isset($_POST['submit_extras'])) {
			
			$options_extras['signoff_text'] = isset($_POST['wp_edit_signoff']) ? stripslashes($_POST['wp_edit_signoff']) : 'Please enter text here...';
			
			$options_extras['enable_qr'] = isset($_POST['enable_qr']) ? '1' : '0';
			$options_extras['enable_qr_widget'] = isset($_POST['enable_qr_widget']) ? '1' : '0';
			$options_extras['qr_colors']['background_title'] = isset($_POST['qr_colors']['background_title']) ? str_replace('#', '', $_POST['qr_colors']['background_title']) : 'e2e2e2';
			$options_extras['qr_colors']['background_content'] = isset($_POST['qr_colors']['background_content']) ? str_replace('#', '', $_POST['qr_colors']['background_content']) : 'dee8e4';
			$options_extras['qr_colors']['qr_foreground_color'] = isset($_POST['qr_colors']['qr_foreground_color']) ? str_replace('#', '', $_POST['qr_colors']['qr_foreground_color']) : '000000';
			$options_extras['qr_colors']['qr_background_color'] = isset($_POST['qr_colors']['qr_background_color']) ? str_replace('#', '', $_POST['qr_colors']['qr_background_color']) : 'c4d7ed';
			$options_extras['qr_colors']['text_color'] = isset($_POST['qr_colors']['text_color']) ? str_replace('#', '', $_POST['qr_colors']['text_color']) : '120a23';
			$options_extras['qr_colors']['title_text'] = isset($_POST['qr_colors']['title_text']) ? $_POST['qr_colors']['title_text'] : 'Enter title here...';
			$options_extras['qr_colors']['content_text'] = isset($_POST['qr_colors']['content_text']) ? $_POST['qr_colors']['content_text'] : 'Enter content here...';
			
			update_option('wp_edit_extras', $options_extras);
				
			function extras_saved_notice(){
				
				echo '<div class="updated"><p>';
				_e('Extra options saved.', 'wp_edit_langs');
				echo '</p></div>';
			}
			add_action('admin_notices', 'extras_saved_notice');
		}
		
		/*
		****************************************************************
		If ajax is disabled... AND... reset buttons was submitted
		****************************************************************
		*/
		if(isset($_POST['save_buttons_sidebars']) && isset($_POST['add_opts']['disable_ajax'])) {
			
			if(isset($_POST['disabled_ajax_save'])) {
				
				$options_buttons = get_option('wp_edit_buttons');
				
				foreach($_POST['disabled_ajax_save'] as $value) {
					
					$values = explode('`', $value);
					$options_buttons[$values[0]] = $values[1];
				}
				update_option('wp_edit_buttons', $options_buttons);
				
				function buttons_saved_notice(){
					
					echo '<div class="updated"><p>';
					_e('Editor Rows configuration saved.', 'wp_edit_langs');
					echo '</p></div>';
				}
				add_action('admin_notices', 'buttons_saved_notice');
			}
		}
		
		/*
		****************************************************************
		If Database button was submitted
		****************************************************************
		*/
		// Display notice if trying to uninstall but forget to check box
		if (isset($_POST['uninstall'] ) && !isset($_POST['uninstall_confirm'])) {
			
			echo '<div id="message" class="error"><p>';
			_e('You must also check the confirm box before options will be uninstalled and deleted.','');
			echo '</p></div>';
		}
		// Uninstall plugin
		if (isset($_POST['uninstall'], $_POST['uninstall_confirm'] ) ) {
			
			if ( !isset($_POST['wp_edit_uninstall_nonce']) || !wp_verify_nonce($_POST['wp_edit_uninstall_nonce'],'wp_edit_uninstall_nonce_check') ) {  // Verify nonce
					
				print __('Sorry, your nonce did not verify.', 'wp_edit_langs');
				exit;
			}
			else {

				delete_option('wp_edit_global','wp_edit_global');
				delete_option('wp_edit_buttons','wp_edit_buttons');
				delete_option('wp_edit_buttons_sidebars','wp_edit_buttons_sidebars');
				delete_option('wp_edit_general','wp_edit_general');
				delete_option('wp_edit_posts','wp_edit_posts');
				delete_option('wp_edit_editor','wp_edit_editor');
				delete_option('wp_edit_fonts','wp_edit_fonts');
				delete_option('wp_edit_widgets','wp_edit_widgets');
				delete_user_meta($current_user->ID, 'aaa_wp_edit_user_meta');
				delete_option('wp_edit_extras','wp_edit_extras');
			 
				// Deactivate the plugin
				$current = get_option('active_plugins');
				array_splice($current, array_search( $_POST['plugin'], $current), 1 );
				update_option('active_plugins', $current);
				
				// Redirect to plugins page with 'plugin deactivated' status message
				wp_redirect( admin_url('/plugins.php?deactivate=true') );
				exit;
			}
		}
		
		// Convert options from Ultimate Tinymce
		if (isset($_POST['submit_convert_opts'])) {
			
			// Get old opts from Ultimate Tinymce (if they exist)
			$get_utmce1 = get_option('jwl_options_group1');
			$get_utmce3 = get_option('jwl_options_group3');
			$get_utmce4 = get_option('jwl_options_group4');
			$add_buttons = array();
			
			// Globals for count alert function'
			global $count_buttons, $count_global, $count_general, $count_posts_pages, $count_extras, $count_user_specific;
			$count_buttons = 0;
			$count_global = 0;
			$count_general = 0;
			$count_posts_pages = 0;
			$count_extras = 0;
			$count_user_specific = '0';
			
			// Build 'WP Edit -> Buttons' options
			if(isset($get_utmce1['jwl_fontselect_field_id'])) { $add_buttons[] .= 'fontselect'; }
			if(isset($get_utmce1['jwl_fontsizeselect_field_id'])) { $add_buttons[] .= 'fontsizeselect'; }
			if(isset($get_utmce1['jwl_styleselect_field_id'])) { $add_buttons[] .= 'styleselect'; }
			if(isset($get_utmce1['jwl_backcolorpicker_field_id'])) { $add_buttons[] .= 'backcolor'; }
			if(isset($get_utmce1['jwl_media_field_id'])) { $add_buttons[] .= 'media'; }
			if(isset($get_utmce1['jwl_directionality_field_id'])) { $add_buttons[] .= 'rtl'; $add_buttons[] .= 'ltr'; }
			if(isset($get_utmce1['jwl_tableDropdown_field_id'])) { $add_buttons[] .= 'table'; }
			if(isset($get_utmce1['jwl_anchor_field_id'])) { $add_buttons[] .= 'anchor';}
			if(isset($get_utmce1['jwl_code_field_id'])) { $add_buttons[] .= 'code';}
			if(isset($get_utmce1['jwl_emotions_field_id'])) { $add_buttons[] .= 'emoticons'; }
			if(isset($get_utmce1['jwl_datetime_field_id'])) { $add_buttons[] .= 'inserttime'; }
			if(isset($get_utmce1['jwl_nextpage_field_id'])) { $add_buttons[] .= 'wp_page';}
			if(isset($get_utmce1['jwl_preview_field_id'])) { $add_buttons[] .= 'preview';}
			if(isset($get_utmce1['jwl_print_field_id'])) { $add_buttons[] .= 'print';}
			if(isset($get_utmce1['jwl_search_field_id']) || isset($get_utmce1['jwl_replace_field_id'])) { $add_buttons[] .= 'searchreplace';}
			if(isset($get_utmce1['jwl_visualchars_field_id'])) { $add_buttons[] .= 'visualblocks';}
			if(isset($get_utmce1['jwl_sub_field_id'])) { $add_buttons[] .= 'subscript';}
			if(isset($get_utmce1['jwl_sup_field_id'])) { $add_buttons[] .= 'superscript';}
			// Count buttons converted (to pass to alert message)
			$count_buttons = count($add_buttons);
			
			// Build 'WP Edit -> Global' options
			$opt_admin_bar_links = isset($get_utmce4['jwl_admin_bar_link']) ? '0' : '1';
			// Count Global opts (to pass to alert message)
			if(isset($get_utmce4['jwl_admin_bar_link'])) { $count_global++; }
			
			// Build 'WP Edit -> General' options
			$opt_linebreak_shortcode = isset($get_utmce3['jwl_linebreak_field_id']) ? '1' : '0';
			$opt_shortcodes_in_widgets = isset($get_utmce3['jwl_shortcode_field_id']) ? '1' : '0';
			$opt_post_excerpt = isset($get_utmce4['jwl_tinymce_excerpt']) ? '1' : '0';
			$opt_page_excerpt = isset($get_utmce4['jwl_tinymce_excerpt_page']) ? '1' : '0';
			$opt_php_widgets = isset($get_utmce3['jwl_php_widget_field_id']) ? '1' : '0';
			// Count General opts (to pass to alert message)
			if(isset($get_utmce3['jwl_linebreak_field_id'])) { $count_general++; }
			if(isset($get_utmce3['jwl_shortcode_field_id'])) { $count_general++; }
			if(isset($get_utmce4['jwl_tinymce_excerpt'])) { $count_general++; }
			if(isset($get_utmce4['jwl_tinymce_excerpt_page'])) { $count_general++; }
			if(isset($get_utmce3['jwl_php_widget_field_id'])) { $count_general++; }
			
			// Build 'WP Edit -> Posts/Pages' options
			$opt_column_shortcodes = isset($get_utmce3['jwl_columns_field_id']) ? '1' : '0';
			$opt_disable_wpautop = isset($get_utmce3['jwl_autop_field_id']) ? '1' : '0';
			// Count Posts/Pages opts (to pass to alert message)
			if(isset($get_utmce3['jwl_columns_field_id'])) { $count_posts_pages++; }
			if(isset($get_utmce3['jwl_autop_field_id'])) { $count_posts_pages++; }
			
			// Build 'WP Edit -> User Specific' options
			$opt_save_scrollbar = isset($get_utmce3['jwl_cursor_field_id']) ? '1' : '0';
			$opt_id_column = isset($get_utmce3['jwl_postid_field_id']) ? '1' : '0';
			$opt_hide_text_tab = isset($get_utmce4['jwl_hide_html_tab']) ? '1' : '0';
			$opt_disable_dashboard_widget = isset($get_utmce4['jwl_dashboard_widget']) ? '0' : '1';
			// Count User Specific opts (to pass to alert message)
			if(isset($get_utmce3['jwl_cursor_field_id'])) { $count_user_specific++; }
			if(isset($get_utmce3['jwl_postid_field_id'])) { $count_user_specific++; }
			if(isset($get_utmce4['jwl_hide_html_tab'])) { $count_user_specific++; }
			if(isset($get_utmce4['jwl_dashboard_widget'])) { $count_user_specific++; }
			
			// Build 'WP Edit -> Extras' options
			$opt_signoff_text = isset($get_utmce3['jwl_signoff_field_id']) ? $get_utmce3['jwl_signoff_field_id'] : 'Please enter text here...';
			// Count Extras opts (to pass to alert message)
			if(isset($get_utmce3['jwl_signoff_field_id'])) { $count_extras++; }
			
			
			// Update WP Edit Buttons Tab
			if($add_buttons) {
				
				// We have to take the buttons found in utmce.. and move them from the wp edit container into row 1
				
				// Create array of container buttons
				$orig_buttons_array = explode(' ', 'fontselect fontsizeselect styleselect backcolor media rtl ltr table anchor code emoticons inserttime wp_page preview print searchreplace visualblocks subscript superscript image');  
				// Get difference between the two (from buttons found above)
				$diff_buttons = array_diff($orig_buttons_array, $add_buttons);  
				
				update_option('wp_edit_buttons', array(
				
					'toolbar1' => 'bold italic strikethrough bullist numlist blockquote alignleft aligncenter alignright link unlink wp_more fullscreen hr ' . implode(' ', $add_buttons), 
					'toolbar2' => 'formatselect underline alignjustify forecolor pastetext removeformat charmap outdent indent undo redo wp_help', 
					'toolbar3' => '', 
					'toolbar4' => '',
					'tmce_container' => implode(' ', $diff_buttons)  // Pass back all buttons not added to row1
				));
			}
			// Update WP Edit Global Tab
			update_option('wp_edit_global', array(
			
				'jquery_theme' => 'smoothness',
				'disable_admin_links' => $opt_admin_bar_links
			));
			// Update WP Edit General Tab
			update_option('wp_edit_general', array(
			
				'linebreak_shortcode' => $opt_linebreak_shortcode,
				'shortcodes_in_widgets' => $opt_shortcodes_in_widgets,
				'shortcodes_in_excerpts' => '0',
				'post_excerpt_editor' => $opt_post_excerpt,
				'page_excerpt_editor' => $opt_page_excerpt,
				'profile_editor' => '0',
				'php_widgets' => $opt_php_widgets
			));
			// Update WP Edit Posts/Pages Tab
			update_option('wp_edit_posts', array(
				
				'post_title_field' => 'Enter title here',
				'max_post_revisions' => '',
				'max_page_revisions' => '',
				'delete_revisions' => '0',
				'hide_admin_posts' => '',
				'hide_admin_pages' => '',
				'column_shortcodes' => $opt_column_shortcodes,
				'disable_wpautop' => $opt_disable_wpautop
			));
			// Update WP Edit User Specific Tab
			global $current_user;  // Get current user
			update_user_meta($current_user->ID, 'aaa_wp_edit_user_meta', array(
				
				'save_scrollbar' => $opt_save_scrollbar,
				'id_column' => $opt_id_column,
				'thumbnail_column' => '0',
				'hide_text_tab' => $opt_hide_text_tab,
				'default_visual_tab' => '0',
				'dashboard_widget' => $opt_disable_dashboard_widget,
				'enable_highlights' => '0',
				'draft_highlight' => '',
				'pending_highlight' => '',
				'published_highlight' => '',
				'future_highlight' => '',
				'private_highlight' => '',
			));
			// Update WP Edit Extras Tab
			update_option('wp_edit_extras', array(
			
				'signoff_text' => $opt_signoff_text,
				'enable_qr' => '0',
				'enable_qr_widget' => '0',
				'qr_colors' => array(
					'background_title' => 'e2e2e2',
					'background_content' => 'dee8e4',
					'text_color' => '000000',
					'qr_foreground_color' => 'c4d7ed',
					'qr_background_color' => '120a23',
					'title_text' => 'Enter title here...',
					'content_text' => 'Enter content here...'
				)
			));
			
			// Alert message
			function wp_edit_settings_converted_alert() {
				
				global $count_buttons, $count_global, $count_general, $count_posts_pages, $count_extras, $count_user_specific;
				
				echo '<div id="message" class="updated">';
					echo '<p>';
					_e('Plugin settings have successfully attempted to convert.','wp_edit_langs');
					echo '<br />';
					
					// If no settings were found to convert
					if($count_buttons == 0 && $count_global == 0 && $count_general == 0 && $count_posts_pages == 0 && $count_extras == 0 && $count_user_specific == 0) {
						
						_e('Unfortunately, no settings were found to convert.', 'wp_edit_langs');
					}
					// Else alert messages of conversion
					else {
						
						echo '<b>('.$count_buttons.')</b>';
						_e(' buttons added to row 1 in "Buttons" tab.', 'wp_edit_langs');
						echo '<br />';
						echo '<b>('.$count_global.')</b>';
						_e(' options updated in "Global" tab.', 'wp_edit_langs');
						echo '<br />';
						echo '<b>('.$count_general.')</b>';
						_e(' options updated in "General" tab.', 'wp_edit_langs');
						echo '<br />';
						echo '<b>('.$count_posts_pages.')</b>';
						_e(' options updated in "Posts/Pages" tab.', 'wp_edit_langs');
						echo '<br />';
						echo '<b>('.$count_user_specific.')</b>';
						_e(' options updated in "User Specific" tab.', 'wp_edit_langs');
						echo '<br />';
						echo '<b>('.$count_extras.')</b>';
						_e(' options updated in "Extras" tab.', 'wp_edit_langs');
						echo '<br /><br />';
						_e('Delete old Ultimate Tinymce database options? (Irreversible; but results in a cleaner database)', 'wp_edit_langs');
						echo '<br />';
					
						// Create form to delete old ultimate tinymce options
						echo '<form method="post">
							<input type="submit" value="'.__('Delete', 'wp_edit_langs').'" name="delete_old_utmce_tables"/><br /><br />
							<input type="submit" value="'.__('No Thanks', 'wp_edit_langs').'" name="delete_old_utmce_tables_no"/>
							</form>';
					}
					
					echo '</p>';
				echo '</div>';
			}
			add_action('admin_notices', 'wp_edit_settings_converted_alert');
		}
		// Delete old ultimate tinymce database tables (if user clicked button in alert notice after converting plugin options)
		if(isset($_POST['delete_old_utmce_tables'])) {
			
			function wp_edit_delete_utmce_tables_alert() {
				
				delete_option('jwl_options_group1','jwl_options_group1');
				delete_option('jwl_options_group3','jwl_options_group3');
				delete_option('jwl_options_group4','jwl_options_group4');
				
				echo '<div id="message" class="updated">';
					echo '<p>';
					_e('Ultimate Tinymce database options successfully deleted.', 'wp_edit_langs');
					echo '<br />';
					_e('Congratulations! You are now running solid with WP Edit.', 'wp_edit_langs');
					echo '</p>';
				echo '</div>';
			}
			add_action('admin_notices', 'wp_edit_delete_utmce_tables_alert');
		}
		
		// Reset plugin settings
		if (isset($_POST['reset_db_values'])) {
			
			if ( !isset($_POST['reset_db_values_nonce'])) {  // Verify nonce
					
				print __('Sorry, your nonce did not verify.', 'wp_edit_langs');
				exit;
			}
			else {
				
				// Get current user
				global $current_user;
				
				// Set DB values (from class vars)
				update_option('wp_edit_global', $this->global_options_global);
				update_option('wp_edit_buttons', $this->global_options_buttons);
				update_option('wp_edit_buttons_sidebars', $this->global_options_buttons_sidebars);
				update_option('wp_edit_general', $this->global_options_general);
				update_option('wp_edit_posts', $this->global_options_posts);
				update_option('wp_edit_editor', $this->global_options_editor);
				update_option('wp_edit_fonts', $this->global_options_fonts);
				update_option('wp_edit_widgets', $this->global_options_widgets);
				update_user_meta($current_user->ID, 'aaa_wp_edit_user_meta', $this->global_options_user_specific);
				update_option('wp_edit_extras', $this->global_options_extras);
		
				echo '<div id="message" class="updated"><p>';
				_e('Plugin settings have been restored to defaults.', 'wp_edit_langs');
				echo '</p></div>';
			}
		}
		
		
		/*
		****************************************************************
		If Import Settings was successful... let's alert a message
		****************************************************************
		*/
		if(isset($_GET['import']) && $_GET['import'] === 'true') {
			
			echo '<div id="message" class="updated"><p>';
			_e('Plugin settings have been successfully imported.' ,'wp_edit_langs');
			echo '</p></div>';
		}
	}
	
	
	/*
	****************************************************************
	Display Page
	****************************************************************
	*/
	public function options_do_page() {
		
		global $current_user;
		get_currentuserinfo();
		
        $options_buttons = get_option('wp_edit_buttons');
		$options_global = get_option('wp_edit_global');
		$options_general = get_option('wp_edit_general');
		$options_buttons_sidebars = get_option('wp_edit_buttons_sidebars');
		$options_posts = get_option('wp_edit_posts');
		$options_editor = get_option('wp_edit_editor');
		$options_fonts = get_option('wp_edit_fonts');
		$options_widgets = get_option('wp_edit_widgets');
		$options_extras = get_option('wp_edit_extras');
		$options_user_meta = get_user_meta($current_user->ID, 'aaa_wp_edit_user_meta', true);
		
		?>
        <div class="wrap">
        
        	<div id="icon-themes" class="icon32"></div>
        	<h2><?php _e('WP Edit', 'wp_edit_langs'); ?></h2>
            
            <?php 
			settings_errors(); 
			$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'buttons';
			?>
            
            <h2 class="nav-tab-wrapper">  
                <a href="?page=wp_edit_options&tab=buttons" class="nav-tab <?php echo $active_tab == 'buttons' ? 'nav-tab-active' : ''; ?>"><?php _e('Buttons', 'wp_edit_langs'); ?></a>
                <a href="?page=wp_edit_options&tab=global" class="nav-tab <?php echo $active_tab == 'global' ? 'nav-tab-active' : ''; ?>"><?php _e('Global', 'wp_edit_langs'); ?></a>
                <a href="?page=wp_edit_options&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e('General', 'wp_edit_langs'); ?></a>
                <a href="?page=wp_edit_options&tab=posts" class="nav-tab <?php echo $active_tab == 'posts' ? 'nav-tab-active' : ''; ?>"><?php _e('Posts/Pages', 'wp_edit_langs'); ?></a>
                <a href="?page=wp_edit_options&tab=editor" class="nav-tab <?php echo $active_tab == 'editor' ? 'nav-tab-active' : ''; ?>"><?php _e('Editor', 'wp_edit_langs'); ?></a>
                <a href="?page=wp_edit_options&tab=fonts" class="nav-tab <?php echo $active_tab == 'fonts' ? 'nav-tab-active' : ''; ?>"><?php _e('Fonts', 'wp_edit_langs'); ?></a>
                <a href="?page=wp_edit_options&tab=widgets" class="nav-tab <?php echo $active_tab == 'widgets' ? 'nav-tab-active' : ''; ?>"><?php _e('Widgets', 'wp_edit_langs'); ?></a>
                <a href="?page=wp_edit_options&tab=user_specific" class="nav-tab <?php echo $active_tab == 'user_specific' ? 'nav-tab-active' : ''; ?>"><?php _e('User Specific', 'wp_edit_langs'); ?></a>
                <a href="?page=wp_edit_options&tab=extras" class="nav-tab <?php echo $active_tab == 'extras' ? 'nav-tab-active' : ''; ?>"><?php _e('Extras', 'wp_edit_langs'); ?></a>
                <a href="?page=wp_edit_options&tab=database" class="nav-tab <?php echo $active_tab == 'database' ? 'nav-tab-active' : ''; ?>"><?php _e('Database', 'wp_edit_langs'); ?></a>
            </h2>  
            
            
            
			<?php 
			/*
			****************************************************************
			Buttons Tab
			****************************************************************
			*/
            if($active_tab == 'buttons'){
				
				?>
                <h3><?php _e('Drag and Drop Panel', 'wp_edit_langs'); ?></h3>
                <h3><?php _e('Editor Rows', 'wp_edit_langs'); ?></h3>
                
                <div id="main_cont">
                    <div class="left_cont">
                        <form method="post" action="">
                        
                        <?php
                        foreach ($options_buttons as $toolbar => $icons) {
							
							if($toolbar === 'toolbar2') {
								echo '<div class="block_tinymce_toolbars" style="width:87%;">';
							}
                        
                            if($toolbar !== 'tmce_container') {
                                                
                                echo '<div id="'.$toolbar.'" class="droppable ui-widget-header">';
                                
                                    if(!empty($icons)) {
                                        $icons = explode(' ', $icons);
                                    }
                                    
                                    if(is_array($icons)) {
                                    
                                        foreach ($icons as $icon) {
                                        
                                            $class = ''; $title = ''; $text = '';
                                            
											// WP Buttons included by default
                                            if($icon === 'bold') { $class = 'editor-bold'; $title = __('Bold', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'italic') { $class = 'editor-italic'; $title = __('Italic', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'strikethrough') { $class = 'editor-strikethrough'; $title = __('Strikethrough', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'bullist') { $class = 'editor-ul'; $title = __('Bullet List', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'numlist') { $class = 'editor-ol'; $title = __('Numbered List', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'blockquote') { $class = 'editor-quote'; $title = __('Blockquote', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'alignleft') { $class = 'editor-alignleft'; $title = __('Align Left', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'aligncenter') { $class = 'editor-aligncenter'; $title = __('Align Center', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'alignright') { $class = 'editor-alignright'; $title = __('Align Right', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'link') { $class = 'admin-links'; $title = __('Link', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'unlink') { $class = 'editor-unlink'; $title = __('Unlink', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'wp_more') { $class = 'editor-insertmore'; $title = __('More', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'fullscreen') { $class = 'editor-distractionfree'; $title = __('Distraction Free', 'wp_edit_langs'); $text = ''; }
                                            //if($icon === 'wp_adv') { $class = 'editor-kitchensink ui-state-disabled ui-state-default_lock'; $title = 'Kitchen Sink'; $text = ''; }
                                            
                                            if($icon === 'formatselect') { $class = ''; $title = __('Format Select', 'wp_edit_langs'); $text = 'Paragraph'; }
                                            if($icon === 'underline') { $class = 'editor-underline'; $title = __('Underline', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'alignjustify') { $class = 'editor-justify'; $title = __('Align Full', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'forecolor') { $class = 'editor-textcolor'; $title = __('Foreground Color', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'pastetext') { $class = 'editor-paste-text'; $title = __('Paste Text', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'removeformat') { $class = 'editor-removeformatting'; $title = __('Remove Format', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'charmap') { $class = 'editor-customchar'; $title = __('Character Map', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'outdent') { $class = 'editor-outdent'; $title = __('Outdent', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'indent') { $class = 'editor-indent'; $title = __('Indent', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'undo') { $class = 'undo'; $title = __('Undo', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'redo') { $class = 'redo'; $title = __('Redo', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'wp_help') { $class = 'editor-help'; $title = __('Help', 'wp_edit_langs'); $text = ''; }
											
											// WP Buttons not included by default
											if($icon === 'fontselect') { $class = ''; $title = __('Font Select', 'wp_edit_langs'); $text = 'Font Family'; }
											if($icon === 'fontsizeselect') { $class = ''; $title = __('Font Size Select', 'wp_edit_langs'); $text = 'Font Sizes'; }
											if($icon === 'styleselect') { $class = ''; $title = __('Formats', 'wp_edit_langs'); $text = 'Formats'; }
											if($icon === 'backcolor') { $class = ''; $title = __('Background Color Picker', 'wp_edit_langs'); $text = ''; }
											if($icon === 'media') { $class = 'format-video'; $title = __('Media', 'wp_edit_langs'); $text = ''; }
											if($icon === 'rtl') { $class = ''; $title = __('Text Direction Right to Left', 'wp_edit_langs'); $text = ''; }
											if($icon === 'ltr') { $class = ''; $title = __('Text Direction Left to Right', 'wp_edit_langs'); $text = ''; }
											if($icon === 'anchor') { $class = ''; $title = __('Anchor', 'wp_edit_langs'); $text = ''; }
											if($icon === 'code') { $class = ''; $title = __('HTML Code', 'wp_edit_langs'); $text = ''; }
											if($icon === 'emoticons') { $class = ''; $title = __('Emoticons', 'wp_edit_langs'); $text = ''; }
											if($icon === 'hr') { $class = ''; $title = __('Horizontal Rule', 'wp_edit_langs'); $text = ''; }
											if($icon === 'inserttime') { $class = ''; $title = __('Insert Date Time', 'wp_edit_langs'); $text = ''; }
											if($icon === 'wp_page') { $class = ''; $title = __('Page Break', 'wp_edit_langs'); $text = ''; }
											if($icon === 'preview') { $class = ''; $title = __('Preview', 'wp_edit_langs'); $text = ''; }
											if($icon === 'print') { $class = ''; $title = __('Print', 'wp_edit_langs'); $text = ''; }
											if($icon === 'searchreplace') { $class = ''; $title = __('Search and Replace', 'wp_edit_langs'); $text = ''; }
											if($icon === 'visualblocks') { $class = ''; $title = __('Show Blocks', 'wp_edit_langs'); $text = ''; }
											if($icon === 'subscript') { $class = ''; $title = __('Subscript', 'wp_edit_langs'); $text = ''; }
											if($icon === 'superscript') { $class = ''; $title = __('Superscript', 'wp_edit_langs'); $text = ''; }
											if($icon === 'p_tags_button') { $class = ''; $title = __('Paragraph Tag', 'wp_edit_langs'); $text = ''; }
											if($icon === 'line_break_button') { $class = ''; $title = __('Line Break', 'wp_edit_langs'); $text = ''; }
											if($icon === 'mailto') { $class = ''; $title = __('MailTo Link', 'wp_edit_langs'); $text = ''; }
											if($icon === 'loremipsum') { $class = ''; $title = __('Lorem Ipsum', 'wp_edit_langs'); $text = ''; }
											if($icon === 'colorpicker') { $class = ''; $title = __('Color Picker', 'wp_edit_langs'); $text = ''; }
											if($icon === 'shortcodes') { $class = ''; $title = __('Shortcodes', 'wp_edit_langs'); $text = ''; }
											if($icon === 'youTube') { $class = ''; $title = __('YouTube Video', 'wp_edit_langs'); $text = ''; }
											if($icon === 'clker') { $class = ''; $title = __('Clker Images', 'wp_edit_langs'); $text = ''; }
											if($icon === 'cleardiv') { $class = ''; $title = __('Clear Div', 'wp_edit_langs'); $text = ''; }
											if($icon === 'codemagic') { $class = ''; $title = __('Code Magic', 'wp_edit_langs'); $text = ''; }
											if($icon === 'acheck') { $class = ''; $title = __('Accessibility Checker', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'image_orig') { $class = 'format-image'; $title = __('Image', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'advlink') { $class = ''; $title = __('Insert/Edit Advanced Link', 'wp_edit_langs'); $text = ''; }
											
											// Custom Buttons
                                            
                                            echo '<li id="'.$icon.'" class="icon_button dashicons dashicons-'.$class.'" title="'.$title.'">'.$text.'</li>';
                                        }
                                    }
                                echo '</div>';
								
								if($toolbar === 'toolbar4') {
									echo '</div>';
								}
                                
                                echo '<input type="hidden" class="get_js_results" value="" name="disabled_ajax_save[]" />';
                                echo '<div style="margin-top:10px;"></div>';
                            }
                        
                        
                            if($toolbar == 'tmce_container') { // This is the tmce_container elements
                
                                echo '<div style="margin-top:60px;"></div>';
                                echo '<h3>';
								_e('Icon Placeholder Container', 'wp_edit_langs');
								echo '</h3>';
                                echo '<div id="tmce_container" class="ui-widget-content draggable_container sortable">';
                                
                                if(!empty($icons)) {
                                    $icons = explode(' ', $icons);
                                }
                                
                                if(is_array($icons)) {
                                
                                    foreach ($icons as $icon) {
                                    
                                        $class = ''; $title = ''; $text = '';
                                            
											// WP Buttons included by default
                                            if($icon === 'bold') { $class = 'editor-bold'; $title = __('Bold', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'italic') { $class = 'editor-italic'; $title = __('Italic', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'strikethrough') { $class = 'editor-strikethrough'; $title = __('Strikethrough', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'bullist') { $class = 'editor-ul'; $title = __('Bullet List', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'numlist') { $class = 'editor-ol'; $title = __('Numbered List', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'blockquote') { $class = 'editor-quote'; $title = __('Blockquote', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'alignleft') { $class = 'editor-alignleft'; $title = __('Align Left', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'aligncenter') { $class = 'editor-aligncenter'; $title = __('Align Center', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'alignright') { $class = 'editor-alignright'; $title = __('Align Right', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'link') { $class = 'admin-links'; $title = __('Link', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'unlink') { $class = 'editor-unlink'; $title = __('Unlink', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'wp_more') { $class = 'editor-insertmore'; $title = __('More', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'fullscreen') { $class = 'editor-distractionfree'; $title = __('Distraction Free', 'wp_edit_langs'); $text = ''; }
                                            //if($icon === 'wp_adv') { $class = 'editor-kitchensink ui-state-disabled ui-state-default_lock'; $title = 'Kitchen Sink'; $text = ''; }
                                            
                                            if($icon === 'formatselect') { $class = ''; $title = __('Format Select', 'wp_edit_langs'); $text = 'Paragraph'; }
                                            if($icon === 'underline') { $class = 'editor-underline'; $title = __('Underline', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'alignjustify') { $class = 'editor-justify'; $title = __('Align Full', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'forecolor') { $class = 'editor-textcolor'; $title = __('Foreground Color', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'pastetext') { $class = 'editor-paste-text'; $title = __('Paste Text', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'removeformat') { $class = 'editor-removeformatting'; $title = __('Remove Format', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'charmap') { $class = 'editor-customchar'; $title = __('Character Map', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'outdent') { $class = 'editor-outdent'; $title = __('Outdent', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'indent') { $class = 'editor-indent'; $title = __('Indent', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'undo') { $class = 'undo'; $title = __('Undo', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'redo') { $class = 'redo'; $title = __('Redo', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'wp_help') { $class = 'editor-help'; $title = __('Help', 'wp_edit_langs'); $text = ''; }
											
											// WP Buttons not included by default
											if($icon === 'fontselect') { $class = ''; $title = __('Font Select', 'wp_edit_langs'); $text = 'Font Family'; }
											if($icon === 'fontsizeselect') { $class = ''; $title = __('Font Size Select', 'wp_edit_langs'); $text = 'Font Sizes'; }
											if($icon === 'styleselect') { $class = ''; $title = __('Formats', 'wp_edit_langs'); $text = 'Formats'; }
											if($icon === 'backcolor') { $class = ''; $title = __('Background Color Picker', 'wp_edit_langs'); $text = ''; }
											if($icon === 'media') { $class = 'format-video'; $title = __('Media', 'wp_edit_langs'); $text = ''; }
											if($icon === 'rtl') { $class = ''; $title = __('Text Direction Right to Left', 'wp_edit_langs'); $text = ''; }
											if($icon === 'ltr') { $class = ''; $title = __('Text Direction Left to Right', 'wp_edit_langs'); $text = ''; }
											if($icon === 'anchor') { $class = ''; $title = __('Anchor', 'wp_edit_langs'); $text = ''; }
											if($icon === 'code') { $class = ''; $title = __('HTML Code', 'wp_edit_langs'); $text = ''; }
											if($icon === 'emoticons') { $class = ''; $title = __('Emoticons', 'wp_edit_langs'); $text = ''; }
											if($icon === 'hr') { $class = ''; $title = __('Horizontal Rule', 'wp_edit_langs'); $text = ''; }
											if($icon === 'inserttime') { $class = ''; $title = __('Insert Date Time', 'wp_edit_langs'); $text = ''; }
											if($icon === 'wp_page') { $class = ''; $title = __('Page Break', 'wp_edit_langs'); $text = ''; }
											if($icon === 'preview') { $class = ''; $title = __('Preview', 'wp_edit_langs'); $text = ''; }
											if($icon === 'print') { $class = ''; $title = __('Print', 'wp_edit_langs'); $text = ''; }
											if($icon === 'searchreplace') { $class = ''; $title = __('Search and Replace', 'wp_edit_langs'); $text = ''; }
											if($icon === 'visualblocks') { $class = ''; $title = __('Show Blocks', 'wp_edit_langs'); $text = ''; }
											if($icon === 'subscript') { $class = ''; $title = __('Subscript', 'wp_edit_langs'); $text = ''; }
											if($icon === 'superscript') { $class = ''; $title = __('Superscript', 'wp_edit_langs'); $text = ''; }
											if($icon === 'p_tags_button') { $class = ''; $title = __('Paragraph Tag', 'wp_edit_langs'); $text = ''; }
											if($icon === 'line_break_button') { $class = ''; $title = __('Line Break', 'wp_edit_langs'); $text = ''; }
											if($icon === 'mailto') { $class = ''; $title = __('MailTo Link', 'wp_edit_langs'); $text = ''; }
											if($icon === 'loremipsum') { $class = ''; $title = __('Lorem Ipsum', 'wp_edit_langs'); $text = ''; }
											if($icon === 'colorpicker') { $class = ''; $title = __('Color Picker', 'wp_edit_langs'); $text = ''; }
											if($icon === 'shortcodes') { $class = ''; $title = __('Shortcodes', 'wp_edit_langs'); $text = ''; }
											if($icon === 'youTube') { $class = ''; $title = __('YouTube Video', 'wp_edit_langs'); $text = ''; }
											if($icon === 'clker') { $class = ''; $title = __('Clker Images', 'wp_edit_langs'); $text = ''; }
											if($icon === 'cleardiv') { $class = ''; $title = __('Clear Div', 'wp_edit_langs'); $text = ''; }
											if($icon === 'codemagic') { $class = ''; $title = __('Code Magic', 'wp_edit_langs'); $text = ''; }
											if($icon === 'acheck') { $class = ''; $title = __('Accessibility Checker', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'image_orig') { $class = 'format-image'; $title = __('Image', 'wp_edit_langs'); $text = ''; }
                                            if($icon === 'advlink') { $class = ''; $title = __('Insert/Edit Advanced Link', 'wp_edit_langs'); $text = ''; }
										
										// Custom Buttons
                                        
                                        echo '<li id="'.$icon.'" class="icon_button dashicons dashicons-'.$class.'" title="'.$title.'">'.$text.'</li>';
                                    }
                                }
                                
                                echo '</div>';
                                echo '<input type="hidden" class="get_js_results" value="" name="disabled_ajax_save[]" />';
                            }
                        }
                        ?>
                
                        <p>
                        <?php
                        _e('Buttons may be dragged to Row 1 (enabling them); or to the Placeholder Container (removing them from the editor).', 'wp_edit_langs');
						echo '<br />';
                        _e('Buttons may also be sorted within Row 1.', 'wp_edit_langs');
						echo '<br />';
                        _e('Each time a button is dropped, an ajax call is made which updates all rows. This feature can be disabled with the additional option.', 'wp_edit_langs');
						echo '<br /><br />';
                        _e('Drag and Drop ability for all four rows of the editor is available in WP Edit Pro.', 'wp_edit_langs');
						?>
                        </p>
                        
                        <?php
                        echo '<div style="margin-top:20px;"></div>';
                        echo '<h3>';
						_e('Icons available in Pro', 'wp_edit_langs');
						echo '</h3>';
                        echo '<div id="tmce_container_pro" class="ui-widget-content draggable_container sortable">';
						
							echo '<li id="acheck" class="icon_button dashicons dashicons-" title="Accessibility Checker"></li>';
							echo '<li id="codemagic" class="icon_button dashicons dashicons-" title="Code Magic"></li>';
							echo '<li id="cleardiv" class="icon_button dashicons dashicons-" title="Clear Div"></li>';
							echo '<li id="clker" class="icon_button dashicons dashicons-" title="Clker Images"></li>';
							echo '<li id="youTube" class="icon_button dashicons dashicons-" title="YouTube Video"></li>';
							echo '<li id="shortcodes" class="icon_button dashicons dashicons-" title="Shortcodes"></li>';
							echo '<li id="colorpicker" class="icon_button dashicons dashicons-" title="Color Picker"></li>';
							echo '<li id="loremipsum" class="icon_button dashicons dashicons-" title="Lorem Ipsum"></li>';
							echo '<li id="mailto" class="icon_button dashicons dashicons-" title="MailTo Link"></li>';
							echo '<li id="line_break_button" class="icon_button dashicons dashicons-" title="Line Break"></li>';
							echo '<li id="p_tags_button" class="icon_button dashicons dashicons-" title="Paragraph Tag"></li>';
                       
						echo '</div>';
						?>
                    </div> <!-- end .left_cont -->
                    
                    <?php
						$disable_ajax = isset($options_buttons_sidebars['add_opts']['disable_ajax']) && $options_buttons_sidebars['add_opts']['disable_ajax'] === '1' ? 'checked="checked"' : '';
					?>
                    
                    <div class="right_cont">
                    
                        <div class="metabox">
                        	<div class="meta_title">
                            	<?php _e('Developer Note', 'wp_edit_langs'); ?>
                            </div>
                            <div class="meta_body">
                            	
                                <?php
                            	_e('This plugin is the culmination of two years experience; many, many hours of hard work and dedication; and a vast presence in the WordPress community.', 'wp_edit_langs');
								echo '<br /><br />';
                                _e('If you enjoy this plugin, please show your appreciation via a ', 'wp_edit_langs');
								echo '<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JGXMYPS69L6US">';
								_e('PayPal Donation', 'wp_edit_langs');
								echo '</a>.<br /><br />';
                                _e('Alternatively, the purchase of the ', 'wp_edit_langs');
								echo '<a target="_blank" href="http://wpeditpro.com">';
								_e('PRO Version', 'wp_edit_langs');
								echo '</a> ';
								_e('offers additional features and extended functionality; while also supporting the developer.', 'wp_edit_langs');
								?>
                                
                            </div>
                        </div>
                        <br /><br />
                    
                        <div class="metabox">
                        	<div class="meta_title">
                            	<?php _e('Additional Options', 'wp_edit_langs'); ?>
                            </div>
                            <div class="meta_body">
                            
                            	<table cellpadding="10">
                                <tbody>
                                    <tr><td>
                                        <?php _e('Restore all buttons to default.', 'wp_edit_langs'); ?>
                                    </td><td>
                                        <input type="button" id="reset_db_buttons" name="reset_db_buttons" value="<?php _e('Reset Buttons', 'wp_edit_langs'); ?>" />
                                    </td>
                                </tr>
                                    <tr><td>
                                        <?php _e('Disable ajax saving of buttons.', 'wp_edit_langs'); ?>
                                    </td><td>
                                        <input type="checkbox" id="disable_ajax" name="add_opts[disable_ajax]" <?php echo $disable_ajax; ?> />
                                    </td>
                                </tr>
                                    <tr><td>
                                        <input type="submit" value="<?php _e('Save Button Options', 'wp_edit_langs'); ?>" class="button button-primary" id="save_buttons_sidebars" name="save_buttons_sidebars">
                                    </td>
                                </tr>
                                </tbody>
                                </table>
                            </div>
                        </div>
                        <br /><br />
                    
						<?php
                            $menu_bar = isset($options_buttons_sidebars['tinymce_opts']['menu_bar']) && $options_buttons_sidebars['tinymce_opts']['menu_bar'] === '1' ? 'checked="checked"' : '';
                            $context_menu = isset($options_buttons_sidebars['tinymce_opts']['context_menu']) && $options_buttons_sidebars['tinymce_opts']['context_menu'] === '1' ? 'checked="checked"' : '';
                        ?>
                        
                        <div class="metabox">
                        	<div class="meta_title">
                            	<?php _e('TinyMCE Options', 'wp_edit_langs'); ?>
                            </div>
                            <div class="meta_body">
                            	
                                <div id="block_buttons_tinymce_metabox">
                                    <table cellpadding="10">
                                    <tbody>
                                    
                                    <tr><td>
                                            <?php _e('Enable editor menu bar.', 'wp_edit_langs'); ?>
                                        </td><td>
                                            <input type="checkbox" id="menu_bar" name="tinymce_opts[menu_bar]" <?php echo $menu_bar; ?> />
                                        </td>
                                    </tr>
                                    
                                    <tr><td>
                                            <?php _e('Enable editor context menu.', 'wp_edit_langs'); ?>
                                        </td><td>
                                            <input type="checkbox" id="context_menu" name="tinymce_opts[context_menu]" <?php echo $context_menu; ?> />
                                        </td>
                                    </tr>
                                    
                                    <tr><td>
                                            <input type="submit" value="<?php _e('Save TinyMCE Options', 'wp_edit_langs'); ?>" class="button button-primary" id="save_buttons_sidebars_tinymce" name="save_buttons_sidebars_tinymce">
                                        </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    </div> <!-- end .right_cont -->
                    
                </div> <!-- end #main_cont -->
                <?php
            }
			/*
			****************************************************************
			Global Tab
			****************************************************************
			*/
            else if($active_tab == 'global') {
				
				?>
                <h3><?php _e('Global Options', 'wp_edit_langs'); ?></h3>
                <p>
                <?php _e('These options control various items used throughout the plugin.', 'wp_edit_langs'); ?>
                </p>
                
                
				<?php
				$jquery_theme = isset($options_global['jquery_theme']) ? $options_global['jquery_theme'] : 'smoothness';
				$disable_admin_links = isset($options_global['disable_admin_links']) && $options_global['disable_admin_links'] === '1' ? 'checked="checked"' : '';
				?>
                
                <form method="post" action="">
                <table cellpadding="10">
                <tbody>
                <tr><td><?php _e('jQuery Theme', 'wp_edit_langs'); ?></td>
                    <td>
                    
                    <select id="jquery_theme" name="jquery_theme"/>
                	<?php
					$jquery_themes = array('base','black-tie','blitzer','cupertino','dark-hive','dot-luv','eggplant','excite-bike','flick','hot-sneaks','humanity','le-frog','mint-choc','overcast','pepper-grinder','redmond','smoothness','south-street','start','sunny','swanky-purse','trontastic','ui-darkness','ui-lightness','vader');
													
					foreach($jquery_themes as $jquery_theme) {
						$selected = ($options_global['jquery_theme']==$jquery_theme) ? 'selected="selected"' : '';
						echo "<option value='$jquery_theme' $selected>$jquery_theme</option>";
					}
					?>
					</select>
					<label for="jquery_theme"> <?php _e('Selects the jQuery theme for plugin alerts and notices.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Disable Admin Links', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="disable_admin_links" type="checkbox" value="1" name="disable_admin_links" <?php echo $disable_admin_links; ?> />
                    <label for="disable_admin_links"><?php _e('Disables the WP Edit top admin bar links.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                </tbody>
                </table>
                <br /><br />
                <input type="submit" value="<?php _e('Save Global Options', 'wp_edit_langs'); ?>" class="button button-primary" id="submit_global" name="submit_global">
                </form>
				<?php
            } 
			/*
			****************************************************************
			General Tab
			****************************************************************
			*/
            else if($active_tab == 'general'){
				
				?>
				<h3><?php _e('General Options', 'wp_edit_langs'); ?></h3>
                <p><?php _e('General plugin options.', 'wp_edit_langs'); ?></p>
                <form method="post" action="">
                
				<?php
				$linebreak_shortcode = isset($options_general['linebreak_shortcode']) && $options_general['linebreak_shortcode'] === '1' ? 'checked="checked"' : '';
				$shortcodes_in_widgets = isset($options_general['shortcodes_in_widgets']) && $options_general['shortcodes_in_widgets'] === '1' ? 'checked="checked"' : '';
				$shortcodes_in_excerpts = isset($options_general['shortcodes_in_excerpts']) && $options_general['shortcodes_in_excerpts'] === '1' ? 'checked="checked"' : '';
				$post_excerpt_editor = isset($options_general['post_excerpt_editor']) && $options_general['post_excerpt_editor'] === '1' ? 'checked="checked"' : '';
				$page_excerpt_editor = isset($options_general['page_excerpt_editor']) && $options_general['page_excerpt_editor'] === '1' ? 'checked="checked"' : '';
				$profile_editor = isset($options_general['profile_editor']) && $options_general['profile_editor'] === '1' ? 'checked="checked"' : '';
				$php_widgets = isset($options_general['php_widgets']) && $options_general['php_widgets'] === '1' ? 'checked="checked"' : '';
				?>
                
                <table cellpadding="8">
                <tbody>
                <tr><td><?php _e('Linebreak Shortcode', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="linebreak_shortcode" type="checkbox" value="1" name="linebreak_shortcode" <?php echo $linebreak_shortcode; ?> />
                    <label for="linebreak_shortcode"><?php _e('Use the [break] shortcode to insert linebreaks in the editor.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Shortcodes in Widgets', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="shortcodes_in_widgets" type="checkbox" value="1" name="shortcodes_in_widgets" <?php echo $shortcodes_in_widgets; ?> />
                    <label for="shortcodes_in_widgets"><?php _e('Use shortcodes in widget areas.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Shortcodes in Excerpts', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="shortcodes_in_excerpts" type="checkbox" value="1" name="shortcodes_in_excerpts" <?php echo $shortcodes_in_excerpts; ?> />
                    <label for="shortcodes_in_excerpts"><?php _e('Use shortcodes in excerpt areas.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('WP Edit Post Excerpt', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="post_excerpt_editor" type="checkbox" value="1" name="post_excerpt_editor" <?php echo $post_excerpt_editor; ?> />
                    <label for="post_excerpt_editor"><?php _e('Add the WP Edit editor to the Post Excerpt area.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('WP Edit Page Excerpt', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="page_excerpt_editor" type="checkbox" value="1" name="page_excerpt_editor" <?php echo $page_excerpt_editor; ?> />
                    <label for="page_excerpt_editor"><?php _e('Add the WP Edit editor to the Page Excerpt area.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Profile Editor', 'wp_edit_langs'); ?></td>
                    <td class="jwl_user_cell">
                        <input id="profile_editor" type="checkbox" value="1" name="profile_editor" <?php echo $profile_editor; ?> />
                        <label for="profile_editor"><?php _e('Use modified editor in profile biography field.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('PHP Widgets', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="php_widgets" type="checkbox" value="1" name="php_widgets" <?php echo $php_widgets; ?> />
                    <label for="php_widgets"><?php _e('Adds a new widget for PHP code.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                </tbody>
                </table>
                <br /><br />
                <input type="submit" value="<?php _e('Save General Options', 'wp_edit_langs'); ?>" class="button button-primary" id="submit_general" name="submit_general">
                </form>
                <?php
				
            }
			/*
			****************************************************************
			Posts/Pages Tab
			****************************************************************
			*/
            else if($active_tab == 'posts'){
				
				?>
				<h3><?php _e('Posts/pages Options', 'wp_edit_langs'); ?></h3>
                <p><?php _e('These options apply specifically to posts and pages.', 'wp_edit_langs'); ?></p>
                <form method="post" action="">
                
				<?php
				
				$post_title_field = isset($options_posts['post_title_field']) ? $options_posts['post_title_field'] : 'Enter title here';
				$column_shortcodes = isset($options_posts['column_shortcodes']) && $options_posts['column_shortcodes'] === '1' ? 'checked="checked"' : '';
				$disable_wpautop = isset($options_posts['disable_wpautop']) && $options_posts['disable_wpautop'] === '1' ? 'checked="checked"' : '';
				
				
				$max_post_revisions = isset($options_posts['max_post_revisions']) ? $options_posts['max_post_revisions'] : '';
				$max_page_revisions = isset($options_posts['max_page_revisions']) ? $options_posts['max_page_revisions'] : '';
				
				$hide_admin_posts = isset($options_posts['hide_admin_posts']) ? $options_posts['hide_admin_posts'] : '';
				$hide_admin_pages = isset($options_posts['hide_admin_pages']) ? $options_posts['hide_admin_pages'] : '';
				
				?>
                
                <table cellpadding="8">
                <tbody>
                <tr><td><?php _e('Post/Page Default Title', 'wp_edit_langs'); ?></td>
                    <td>
                    <input type="text" name="post_title_field" value="<?php echo $post_title_field ?>" />
                    <label for="post_title_field"><?php _e('Change the default "add new" post/page title field.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Column Shortcodes', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="column_shortcodes" type="checkbox" value="1" name="column_shortcodes" <?php echo $column_shortcodes; ?> />
                    <label for="column_shortcodes"><?php _e('Enable the column shortcodes functionality.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Disable wpautop()', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="disable_wpautop" type="checkbox" value="1" name="disable_wpautop" <?php echo $disable_wpautop; ?> />
                    <label for="disable_wpautop"><?php _e('Disable the filter responsible for removing p and br tags.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                </tbody>
                </table>
                
                <h3><?php _e('Page Revisions', 'wp_edit_langs'); ?></h3>
                <table cellpadding="8">
                <tbody>
                <tr><td><?php _e('Max Post Revisions', 'wp_edit_langs'); ?></td>
                    <td>
                    <input type="text" name="max_post_revisions" value="<?php echo $max_post_revisions ?>" />
                    <label for="max_post_revisions"><?php _e('Set max number of Post Revisions to store in database. (empty = unlimited)', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Max Page Revisions', 'wp_edit_langs'); ?></td>
                    <td>
                    <input type="text" name="max_page_revisions" value="<?php echo $max_page_revisions ?>" />
                    <label for="max_page_revisions"><?php _e('Set max number of Page Revisions to store in database. (empty = unlimited)', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Delete Revisions', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="delete_revisions" type="checkbox" value="1" name="delete_revisions" />
                    <label for="delete_revisions"><?php _e('Delete all database revisions.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Revisions DB Size', 'wp_edit_langs'); ?></td>
                	<td>
                    	<?php
						global $wpdb;
						$query = $wpdb->get_results( "SELECT * FROM $wpdb->posts WHERE post_type = 'revision'", ARRAY_A );
						$lengths = 0;
						foreach ($query as $row) {
							$lengths += strlen($row['post_content']);
						}
						_e('Current size of revisions stored in database:', 'wp_edit_langs');
						echo ' <strong>'.number_format($lengths/(1024*1024),3).' mb</strong>';
						?>
                    </td>
                </tr>
                </tbody>
                </table>
                
                <h3><?php _e('Hide Posts and Pages', 'wp_edit_langs'); ?></h3>
                <table cellpadding="8">
                <tbody>
                <tr><td><?php _e('Hide Admin Posts', 'wp_edit_langs'); ?></td>
                    <td>
                    <input type="text" name="hide_admin_posts" value="<?php echo $hide_admin_posts ?>" />
                    <label for="hide_admin_posts"><?php _e('Hide selected posts from admin view. ID comma separated (1,5,14,256)', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Hide Admin Pages', 'wp_edit_langs'); ?></td>
                    <td>
                    <input type="text" name="hide_admin_pages" value="<?php echo $hide_admin_pages ?>" />
                    <label for="hide_admin_pages"><?php _e('Hide selected pages from admin view. ID comma separated (1,5,14,256)', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                </tbody>
                </table>
                <br /><br />
                <input type="submit" value="<?php _e('Save Posts/Pages Options', 'wp_edit_langs'); ?>" class="button button-primary" id="submit_posts" name="submit_posts">
                </form>
                <?php
            }
			/*
			****************************************************************
			Editor Tab
			****************************************************************
			*/
            else if($active_tab == 'editor'){
				
                ?>
                <h3><?php _e('Editor Options', 'wp_edit_langs'); ?></h3>
                <p><?php _e('These options will override the initial editor defaults, which will load the editor using the values below.', 'wp_edit_langs'); ?></p>
                <form method="post" action="">
				<?php
				
				$enable_editor = isset($options_editor['enable_editor']) && $options_editor['enable_editor'] === '1' ? 'checked="checked"' : '';
				
				$editor_font = isset($options_editor['editor_font']) ? $options_editor['editor_font'] : 'Verdana, Arial, Helvetica, sans-serif';
                $editor_font_color = isset($options_editor['editor_font_color']) ? $options_editor['editor_font_color'] : '#000000';
                $editor_font_size = isset($options_editor['editor_font_size'])  ? $options_editor['editor_font_size'] : '13px';
                $editor_bg_color = isset($options_editor['editor_bg_color'])  ? $options_editor['editor_bg_color'] : '#FFFFFF';
                $editor_line_height = isset($options_editor['editor_line_height'])  ? $options_editor['editor_line_height'] : '19px';
                $editor_body_padding = isset($options_editor['editor_body_padding'])  ? $options_editor['editor_body_padding'] : '0px';
                $editor_body_margin = isset($options_editor['editor_body_margin'])  ? $options_editor['editor_body_margin'] : '10px';
                $editor_text_indent = isset($options_editor['editor_text_indent'])  ? $options_editor['editor_text_indent'] : '0px';
                $editor_text_direction = isset($options_editor['editor_text_direction'])  ? $options_editor['editor_text_direction'] : 'ltr';
				$editor_tinymce_px = isset($options_editor['editor_tinymce_px']) && $options_editor['editor_tinymce_px'] === '1' ? 'checked="checked"' : '';
				
				?>
                <div id="block_container_editor">
                    
                    <table id="block_container_editor_table" cellpadding="8">
                    <tbody>
                    
                    <tr><td><?php _e('Enable Editor Settings', 'wp_edit_langs'); ?></td>
                        <td>
                        <input id="enable_editor" type="checkbox" value="1" name="enable_editor" <?php echo $enable_editor; ?> />
                        <label for="enable_editor"><?php _e('Executes the values set below when initiating the content editor.', 'wp_edit_langs'); ?></label>
                        </td>
                    </tr>
                    
                    <tr><td><?php _e('Editor Font', 'wp_edit_langs'); ?></td>
                        <td class="jwl_user_cell">
                        <input id="editor_font" type="text" name="editor_font" value="<?php echo $editor_font; ?>" />
                        <label for="editor_font"><?php _e('Font Family (ex. Verdana, Arial, Helvetica, sans-serif)', 'wp_edit_langs'); ?></label>
                        </td>
                    </tr>
                    <tr><td><?php _e('Editor Font Color', 'wp_edit_langs'); ?></td>
                        <td class="jwl_user_cell">
                        <input id="editor_font_color" type="text" name="editor_font_color" value="<?php echo $editor_font_color; ?>" />
                        <label for="editor_font_color"><?php _e('Hex Number (ex. 000000)', 'wp_edit_langs'); ?></label>
                        </td>
                    </tr>
                    <tr><td><?php _e('Editor Font Size', 'wp_edit_langs'); ?></td>
                        <td class="jwl_user_cell">
                        <input id="editor_font_size" type="text" name="editor_font_size" value="<?php echo $editor_font_size; ?>" />
                        <label for="editor_font_size"><?php _e('Size (ex. 13px)', 'wp_edit_langs'); ?></label>
                        </td>
                    </tr>
                    <tr><td><?php _e('Editor Background Color', 'wp_edit_langs'); ?></td>
                        <td class="jwl_user_cell">
                        <input id="editor_bg_color" type="text" name="editor_bg_color" value="<?php echo $editor_bg_color; ?>" />
                        <label for="editor_bg_color"><?php _e('Hex Number (ex. FFFFFF)', 'wp_edit_langs'); ?></label>
                        </td>
                    </tr>
                    <tr><td><?php _e('Line Height', 'wp_edit_langs'); ?></td>
                        <td class="jwl_user_cell">
                        <input id="editor_line_height" type="text" name="editor_line_height" value="<?php echo $editor_line_height; ?>" />
                        <label for="editor_line_height"><?php _e('Size (ex. 19px)', 'wp_edit_langs'); ?></label>
                        </td>
                    </tr>
                    <tr><td><?php _e('Body Padding', 'wp_edit_langs'); ?></td>
                        <td class="jwl_user_cell">
                        <input id="editor_body_padding" type="text" name="editor_body_padding" value="<?php echo $editor_body_padding; ?>" />
                        <label for="editor_body_padding"><?php _e('Size (ex. 14px)', 'wp_edit_langs'); ?></label>
                        </td>
                    </tr>
                    <tr><td><?php _e('Body Margin', 'wp_edit_langs'); ?></td>
                        <td class="jwl_user_cell">
                        <input id="editor_body_margin" type="text" name="editor_body_margin" value="<?php echo $editor_body_margin; ?>" />
                        <label for="editor_body_margin"><?php _e('Size (ex. 10px)', 'wp_edit_langs'); ?></label>
                        </td>
                    </tr>
                    <tr><td><?php _e('Text Indent', 'wp_edit_langs'); ?></td>
                        <td class="jwl_user_cell">
                        <input id="editor_text_indent" type="text" name="editor_text_indent" value="<?php echo $editor_text_indent; ?>" />
                        <label for="editor_text_indent"><?php _e('Size (ex. 20px)', 'wp_edit_langs'); ?></label>
                        </td>
                    </tr>
                    <tr><td><?php _e('Text Direction', 'wp_edit_langs'); ?></td>
                        <td class="jwl_user_cell">
                        <input id="editor_text_direction" type="text" name="editor_text_direction" value="<?php echo $editor_text_direction; ?>" />
                        <label for="editor_text_direction"><?php _e('Direction (ex. ltr)', 'wp_edit_langs'); ?></label>
                        </td>
                    </tr>
                    </tbody>
                    </table>
                </div>
                <br /><br />
                <input type="submit" value="<?php _e('Restore Settings', 'wp_edit_langs'); ?>" class="button button-secondary" id="restore_editor" name="restore_editor">
                
                <h3><?php _e('TinyMCE Options', 'wp_edit_langs'); ?></h3>
                <p><?php _e('These options will adjust various parts of the TinyMCE initialization process.', 'wp_edit_langs'); ?></p>
                
                <table cellpadding="8">
                <tbody>
                <tr><td><?php _e('Change Font to "px"', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="editor_tinymce_px" type="checkbox" value="1" name="editor_tinymce_px" <?php echo $editor_tinymce_px; ?> />
                    <label for="editor_tinymce_px"><?php _e('Switches the default font size in the dropdown list from "pt" to "px".', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                </tbody>
                </table>
                <br /><br />
                <input type="submit" value="<?php _e('Save Editor Options', 'wp_edit_langs'); ?>" class="button button-primary" id="submit_editor" name="submit_editor">
                </form>
                <?php
            }
			/*
			****************************************************************
			Fonts Tab
			****************************************************************
			*/
            else if($active_tab == 'fonts') {
				
				?>
                <h3><?php _e('Fonts', 'wp_edit_langs'); ?></h3>
                <p><?php _e('These settings will enable various fonts in the editor.', 'wp_edit_langs'); ?></p>
                <form method="post" action="">
                
                <?php
				$enable_google_fonts = isset($options_fonts['enable_google_fonts']) && $options_fonts['enable_google_fonts'] === '1' ? 'checked="checked"' : '';
                $google_font_link = isset($options_fonts['google_font_link'])  ? $options_fonts['google_font_link'] : "<link href='http://fonts.googleapis.com/css?family=Freckle+Face' rel='stylesheet' type='text/css'>";
				
				$save_google_fonts = isset($options_fonts['save_google_fonts'])  ? $options_fonts['save_google_fonts'] : '';
                ?>
                
                <div id="block_container_editor_table">
                    <table cellpadding="8">
                    <tbody>
                    <tr><td><?php _e('Activate Google Fonts', 'wp_edit_langs'); ?></td>
                        <td>
                        <input id="enable_google_fonts" type="checkbox" value="1" name="enable_google_fonts" <?php echo $enable_google_fonts; ?> />
                        <label for="enable_google_fonts"><?php _e('Turns on/off Google Webfonts.', 'wp_edit_langs'); ?></label>
                        </td>
                    </tr>
                    </tbody>
                    </table>
                    
                   
                    <div class="metabox-holder"> 
                        <div class="postbox">
                        <h3><span><?php _e('Build Font List', 'wp_edit_langs'); ?></span></h3>
                        
                            <div class="inside">
                                <?php
                                echo '<strong>';
								_e('Step 1:', 'wp_edit_langs');
								echo '</strong> ';
								_e('Visit ', 'wp_edit_langs');
								echo '<a target="_blank" href="http://www.google.com/fonts">';
								_e('Google Webfonts', 'wp_edit_langs');
								echo '</a> ';
								_e('to see how fonts are displayed in the browser.', 'wp_edit_langs'); 
								echo '<br />';
                                echo '<strong>';
								_e('Step 2:', 'wp_edit_langs');
								echo '</strong> ';
								_e('Once desired fonts are selected, choose them (one at a time) from the dropdown list below (sorted alphabetically).', 'wp_edit_langs');
								echo '<br /><br />';
                                
                                
                                // Get full font list from google api
                                $google_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyDFQ7lednDw4HDGay68VSctbHg9HuFLo9U';
                                $response = wp_remote_get($google_api_url);
                             
                                if(is_wp_error($response['body'])) {
                                    _e('Something went wrong!', 'wp_edit_langs');
                                }
                                else {
                                    
                                    $json_fonts = json_decode($response['body'], true);
                                    
                                    $items = $json_fonts['items'];
                                    $str = '';
                                    
                                    //Build font dropdown list
                                    echo '<select id="google_dropdown">';
                                    foreach ($items as $item) {
                                        
                                        $str = $item['family'];
                                        echo '<option value="'.$str.'">'.$str.'</option>';
                                    }
                                    echo '</select>';
                                }	
                                echo '<br /><br />';
                                
                                echo '<strong>Note:</strong> ';
								_e('As fonts are selected, they will appear in the "Active Fonts" section below.', 'wp_edit_langs');
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                    <div class="metabox-holder"> 
                        <div class="postbox">
                        <h3><span><?php _e('Active Fonts', 'wp_edit_langs'); ?></span></h3>
                        
                            <div class="inside">
                            
                                <?php
                                echo '<div id="google_fonts">';
                                    
                                    echo '<div id="active_font_list">';
                                    
                                        echo '<span class="google_active_font_title">';
										_e('Current Font List', 'wp_edit_langs');
										echo '</span>';
                                        echo '<div id="google_fonts_placeholder"><ul>';
                                        
                                            // This is our hidden li element area
                                            if(is_array($save_google_fonts)) {
                                                
                                                foreach ($save_google_fonts as $font) {
                                                    
                                                    echo '<li class="active_font"><input type="hidden" name="save_google_fonts['.$font.']" value="'.$font.'" />'.$font.'</li>';
                                                }
                                            }
                                        echo '</ul></div>';
                                    echo '</div>';
                                    
                                    
                                    echo '<div id="google_fonts_trashbin">';
                                        
                                        echo '<span class="google_active_font_title">';
										_e('Trash Bin', 'wp_edit_langs');
										echo '</span>';
                                        echo '<div id="trash_bin_wrapper">';
                                            echo '<div><span id="google_font_trash"></span><br />';
											_e('Drag fonts here to delete...', 'wp_edit_langs');
											echo '</div>';
                                        echo '</div>';
                                    echo '</div>';
                                    
                                    echo '<div style="clear:both;"></div><br /><br />';
                                    echo '<input type="submit" value="';
									_e('Save Font Options', 'wp_edit_langs');
									echo '" class="button button-primary" id="submit_fonts_new" name="submit_fonts">';
                                    
                                echo '</div>';
                                echo '<br />';
                                _e('Remember to save the options when finished.', 'wp_edit_langs'); 
                            ?>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <?php	
            }
			/*
			****************************************************************
			Widgets Tab
			****************************************************************
			*/
            else if($active_tab == 'widgets') {
				
				?>
                <h3><?php _e('Widget Options', 'wp_edit_langs'); ?></h3>
                <p><?php _e('These options specifically affect how widgets are handled.', 'wp_edit_langs'); ?></p>
                
                <form method="post" action="">
                    <div id="block_container_widgets">
                        
                        <?php
                        $widget_builder = isset($options_widgets['widget_builder']) && $options_widgets['widget_builder'] === '1' ? 'checked="checked"' : '';
                        ?>
                        
                        <table id="block_container_editor_table" cellpadding="8">
                        <tbody>
                        <tr><td><?php _e('Widget Builder', 'wp_edit_langs'); ?></td>
                            <td>
                            <input id="widget_builder" type="checkbox" value="1" name="widget_builder" <?php echo $widget_builder; ?> />
                            <label for="widget_builder"><?php _e('Enables the powerful widget builder.', 'wp_edit_langs'); ?></label>
                            </td>
                        </tr>
                        </tbody>
                        </table>
                    <br /><br />
                    </div>
                    
                    <br /><br />
                    <input type="submit" value="<?php _e('Save Widget Options', 'wp_edit_langs'); ?>" class="button button-primary" id="submit_widgets" name="submit_widgets">
                </form>
				<?php			
            }
			/*
			****************************************************************
			User Specific Tab
			****************************************************************
			*/
            else if($active_tab == 'user_specific') {
                
                ?>
                <h3><?php _e('User Specific Options', 'wp_edit_langs'); ?></h3>
                <p><?php _e('These options are stored in individual user meta; meaning each user can set these options independlently from one another.', 'wp_edit_langs'); ?></p>
                <form method="post" action="">
                
                <?php
                $save_scrollbar = isset($options_user_meta['save_scrollbar']) && $options_user_meta['save_scrollbar'] === '1' ? 'checked="checked"' : '';
                $id_column = isset($options_user_meta['id_column']) && $options_user_meta['id_column'] === '1' ? 'checked="checked"' : '';
                $thumbnail_column = isset($options_user_meta['thumbnail_column']) && $options_user_meta['thumbnail_column'] === '1' ? 'checked="checked"' : '';
                $hide_text_tab = isset($options_user_meta['hide_text_tab']) && $options_user_meta['hide_text_tab'] === '1' ? 'checked="checked"' : '';
                $default_visual_tab = isset($options_user_meta['default_visual_tab']) && $options_user_meta['default_visual_tab'] === '1' ? 'checked="checked"' : '';
                $dashboard_widget = isset($options_user_meta['dashboard_widget']) && $options_user_meta['dashboard_widget'] === '1' ? 'checked="checked"' : '';
                
                $enable_highlights = isset($options_user_meta['enable_highlights']) && $options_user_meta['enable_highlights'] === '1' ? 'checked="checked"' : '';
                $draft_highlight = isset($options_user_meta['draft_highlight']) ? $options_user_meta['draft_highlight'] : '#FFFFFF';
                $pending_highlight = isset($options_user_meta['pending_highlight'])  ? $options_user_meta['pending_highlight'] : '#FFFFFF';
                $published_highlight = isset($options_user_meta['published_highlight'])  ? $options_user_meta['published_highlight'] : '#FFFFFF';
                $future_highlight = isset($options_user_meta['future_highlight'])  ? $options_user_meta['future_highlight'] : '#FFFFFF';
                $private_highlight = isset($options_user_meta['private_highlight'])  ? $options_user_meta['private_highlight'] : '#FFFFFF';
                ?>
                
                
                <table cellpadding="8">
                <tbody>
                <tr><td><?php _e('Save Scrollbar', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="save_scrollbar" type="checkbox" value="1" name="wp_edit_user_specific[save_scrollbar]" <?php echo $save_scrollbar; ?> />
                    <label for="save_scrollbar"><?php _e('Saves the editor srollbar position when editing long posts (TEXT mode).', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('ID Column', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="id_column" type="checkbox" value="1" name="wp_edit_user_specific[id_column]" <?php echo $id_column; ?> />
                    <label for="id_column"><?php _e('Adds a column to post/page list view for displaying the post/page ID.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Thumbnail Column', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="thumbnail_column" type="checkbox" value="1" name="wp_edit_user_specific[thumbnail_column]" <?php echo $thumbnail_column; ?> />
                    <label for="thumbnail_column"><?php _e('Adds a column to post/page list view for displaying thumbnails.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Hide TEXT Tab', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="hide_text_tab" type="checkbox" value="1" name="wp_edit_user_specific[hide_text_tab]" <?php echo $hide_text_tab; ?> />
                    <label for="hide_text_tab"><?php _e('Hide the editor TEXT tab from view.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Default VISUAL Tab', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="default_visual_tab" type="checkbox" value="1" name="wp_edit_user_specific[default_visual_tab]" <?php echo $default_visual_tab; ?> />
                    <label for="default_visual_tab"><?php _e('Always display VISUAL tab when editor loads.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Disable Dashboard Widget', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="dashboard_widget" type="checkbox" value="1" name="wp_edit_user_specific[dashboard_widget]" <?php echo $dashboard_widget; ?> />
                    <label for="dashboard_widget"><?php _e('Disables Ultimate Tinymce Pro News Feed dashboard widget.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                </tbody>
                </table>
                
                <h3><?php _e('Post/Page Highlight Colors', 'wp_edit_langs'); ?></h3>
                <p><?php _e('These options will allow each user to customize highlight colors for each post/page status.', 'wp_edit_langs'); ?><br />
                <?php _e('Meaning.. saved posts can be yellow, published posts can be blue, etc.', 'wp_edit_langs'); ?></p>
                
                <table cellpadding="8">
                <tbody>
                <tr><td><?php _e('Enable Highlights', 'wp_edit_langs'); ?></td>
                    <td>
                    <input id="enable_highlights" type="checkbox" value="1" name="wp_edit_user_specific[enable_highlights]" <?php echo $enable_highlights; ?> />
                    <label for="enable_highlights"><?php _e('Enable the Highlight Options below.', 'wp_edit_langs'); ?></label>
                    </td>
                </tr>
                <tr><td><?php _e('Draft Highlight', 'wp_edit_langs'); ?></td>
                    <td class="jwl_user_cell">
                    <input id="draft_highlight" type="text" name="wp_edit_user_specific[draft_highlight]" class="color_field" value="<?php echo $draft_highlight; ?>" />
                    </td>
                </tr>
                <tr><td><?php _e('Pending Highlight', 'wp_edit_langs'); ?></td>
                    <td class="jwl_user_cell">
                    <input id="pending_highlight" type="text" name="wp_edit_user_specific[pending_highlight]" class="color_field" value="<?php echo $pending_highlight; ?>" />
                    </td>
                </tr>
                <tr><td><?php _e('Published Highlight', 'wp_edit_langs'); ?></td>
                    <td class="jwl_user_cell">
                    <input id="published_highlight" type="text" name="wp_edit_user_specific[published_highlight]" class="color_field" value="<?php echo $published_highlight; ?>" />
                    </td>
                </tr>
                <tr><td><?php _e('Future Highlight', 'wp_edit_langs'); ?></td>
                    <td class="jwl_user_cell">
                    <input id="future_highlight" type="text" name="wp_edit_user_specific[future_highlight]" class="color_field" value="<?php echo $future_highlight; ?>" />
                    </td>
                </tr>
                <tr><td><?php _e('Private Highlight', 'wp_edit_langs'); ?></td>
                    <td class="jwl_user_cell">
                    <input id="private_highlight" type="text" name="wp_edit_user_specific[private_highlight]" class="color_field" value="<?php echo $private_highlight; ?>" />
                    </td>
                </tr>
                </tbody>
                </table>
                <?php 
                
                submit_button(); 
                ?></form><?php
            }
			/*
			****************************************************************
			Extras Tab
			****************************************************************
			*/
			else if($active_tab == 'extras')  {
				
				$enable_qr = isset($options_extras['enable_qr']) && $options_extras['enable_qr'] === '1' ? 'checked="checked"' : '';
				$enable_qr_widget = isset($options_extras['enable_qr_widget']) && $options_extras['enable_qr_widget'] === '1' ? 'checked="checked"' : '';
				
                $background_title = isset($options_extras['qr_colors']['background_title']) ? $options_extras['qr_colors']['background_title'] : 'e2e2e2';
                $background_content = isset($options_extras['qr_colors']['background_content'])  ? $options_extras['qr_colors']['background_content'] : 'dee8e4';
                $text_color = isset($options_extras['qr_colors']['text_color'])  ? $options_extras['qr_colors']['text_color'] : '000000';
                $qr_foreground_color = isset($options_extras['qr_colors']['qr_foreground_color'])  ? $options_extras['qr_colors']['qr_foreground_color'] : 'c4d7ed';
                $qr_background_color = isset($options_extras['qr_colors']['qr_background_color'])  ? $options_extras['qr_colors']['qr_background_color'] : '120a23';
                $title_text = isset($options_extras['qr_colors']['title_text'])  ? $options_extras['qr_colors']['title_text'] : 'Enter title here...';
                $content_text = isset($options_extras['qr_colors']['content_text'])  ? $options_extras['qr_colors']['content_text'] : 'Enter content here...';
				
				?>
                <h3><?php _e('Extra Options', 'wp_edit_langs'); ?></h3>
                <form method="post" action="">
                    <h3><?php _e('Signoff Text', 'wp_edit_langs'); ?></h3>
                    <p><?php _e('Use the editor below to create a content chunk that can be inserted anywhere using the', 'wp_edit_langs'); ?> <strong>[signoff]</strong> <?php _e('shortcode.', 'wp_edit_langs'); ?></p>
                    
                    <table cellpadding="8" width="100%">
                    <tbody>
                    <tr><td>
						<?php
                        $content = isset($options_extras['signoff_text']) ? $options_extras['signoff_text'] : 'Please enter text here...';
                        $editor_id = 'wp_edit_signoff';
                        $args = array('textarea_rows' => 10, 'width' => '100px');
                        wp_editor( $content, $editor_id, $args );
                        ?>
                    </td></tr>
                    </tbody>
                    </table>
                    
                    <h3><?php _e('QR Codes', 'wp_edit_langs'); ?></h3>
                    <p>
                       <?php _e('Please use the options below to setup your personalized QR Code.', 'wp_edit_langs'); ?><br />
                    </p>
                    
                    <div id="block_container_qr_codes" class="metabox-holder">
                    
                        <table cellpadding="8">
                        <tbody>
                        <tr><td><?php _e('Enable QR Codes', 'wp_edit_langs'); ?></td>
                            <td class="jwl_user_cell">
                                <input id="enable_qr" type="checkbox" value="1" name="enable_qr" <?php echo $enable_qr; ?> />
                                <label for="enable_qr"><?php _e('A global option which enables all QR Code functionality.', 'wp_edit_langs'); ?></label>
                            </td>
                        </tr>
                        <tr><td><?php _e('Enable QR Widgets', 'wp_edit_langs'); ?></td>
                            <td class="jwl_user_cell">
                                <input id="enable_qr_widget" type="checkbox" value="1" name="enable_qr_widget" <?php echo $enable_qr_widget; ?> />
                                <label for="enable_qr_widget"><?php _e('Adds new widget for creating QR codes.', 'wp_edit_langs'); ?></label>
                            </td>
                        </tr>
                        </tbody>
                        </table>
                    
                    
                        <div class="postbox">
                        
                            <h3><span><?php _e('Design QR Code', 'wp_edit_langs'); ?></span></h3>
                            <div class="inside">
                            
                                <div style="width:100%;">
                                
                                    <div style="width:25%;float:left;">
                                        <table cellpadding="3">
                                        <tbody>
                                        <tr><td><?php _e('Title Background', 'wp_edit_langs'); ?></td>
                                            <td class="jwl_user_cell">
                                            <input id="background_title" type="text" name="qr_colors[background_title]" class="color_field" value="#<?php echo $background_title; ?>" />
                                            </td>
                                        </tr>
                                        <tr><td><?php _e('Content Background', 'wp_edit_langs'); ?></td>
                                            <td class="jwl_user_cell">
                                            <input id="background_content" type="text" name="qr_colors[background_content]" class="color_field" value="#<?php echo $background_content; ?>" />
                                            </td>
                                        </tr>
                                        <tr><td><?php _e('Text Color', 'wp_edit_langs'); ?></td>
                                            <td class="jwl_user_cell">
                                            <input id="text_color" type="text" name="qr_colors[text_color]" class="color_field" value="#<?php echo $text_color; ?>" />
                                            </td>
                                        </tr>
                                        <tr><td><?php _e('QR Foreground Color', 'wp_edit_langs'); ?></td>
                                            <td class="jwl_user_cell">
                                            <input id="qr_foreground_color" type="text" name="qr_colors[qr_foreground_color]" class="color_field" value="#<?php echo $qr_foreground_color; ?>" />
                                            </td>
                                        </tr>
                                        <tr><td><?php _e('QR Background Color', 'wp_edit_langs'); ?></td>
                                            <td class="jwl_user_cell">
                                            <input id="qr_background_color" type="text" name="qr_colors[qr_background_color]" class="color_field" value="#<?php echo $qr_background_color; ?>" />
                                            </td>
                                        </tr>
                                        </tbody>
                                        </table>
                                    </div>
                                    
                                    <div id="qr_container" style="width:40%;float:left;">
                                        <?php _e('This is a preview area.  Changes will not be written to the database until the "Save Changes" button is clicked.', 'wp_edit_langs'); ?><br /><br />
                                        <div style="border:1px solid #ddd;padding:5px;background-color:#<?php echo $background_title; ?>;">
                                            <?php _e('Preview! (<em>Click "Save Changes" to update</em>)', 'wp_edit_langs'); ?>
                                         </div>
                                         <div style="display:block;padding:5px;border:1px solid #ddd;background-color:#<?php echo $background_content; ?>;">
                                            <div style="display:inline-block;">
                                                <div style="float:left;margin:3px 10px 0 10px;">
                                                    <script type="text/javascript">
                                                    var uri=window.location.href;document.write("<img src=\'http://api.qrserver.com/v1/create-qr-code/?data="+encodeURI(uri)+"&size=75x75&color=<?php echo $qr_background_color; ?>&bgcolor=<?php echo $qr_foreground_color; ?>\'/>");
                                                    </script>
                                                </div>
                                                <div style="margin-left:10px;color:#<?php echo $text_color; ?>;">
                                                    <?php _e('This is preview text. Use the editor below to create custom text for your posts/pages.', 'wp_edit_langs'); ?><br /><br />
                                                    <?php _e('It is strongly suggested to use highly contrasting colors for the background and foreground QR colors.', 'wp_edit_langs'); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div style="clear:both;margin-bottom:20px;"></div>
                                
                                
                                <table cellpadding="3">
                                <tbody>
                                <tr><td><?php _e('Title Text', 'wp_edit_langs'); ?></td>
                                    <td class="jwl_user_cell">
                                    <input id="title_text" type="text" name="qr_colors[title_text]" value="<?php echo $title_text; ?>" style="width:600px;" />
                                    </td>
                                </tr>
                                <tr><td><?php _e('Content', 'wp_edit_langs'); ?></td>
                                    <td class="jwl_user_cell">
                                    <textarea id="content_text" type="text" name="qr_colors[content_text]" value="<?php echo $content_text; ?>" style="width:600px;"><?php echo $content_text; ?></textarea>
                                    </td>
                                </tr>
                                </tbody>
                                </table>
                            
                            </div>
                        </div>
                    </div>
                    
                    <input type="submit" value="Save Changes" class="button button-primary" id="submit_extras" name="submit_extras">
                </form>
                <?php
			}
			/*
			****************************************************************
			Database Tab
			****************************************************************
			*/
			else {
				?>
                <h3><?php _e('Database Options', 'wp_edit_langs'); ?></h3>
                
                <div class="metabox-holder">
                
                    <div class="postbox">
                        <h3><span><?php _e('Convert Options from Ultimate Tinymce', 'wp_edit_langs'); ?></span></h3>
                        <div class="inside">
                            <p><?php 
							_e('Convert any options used by Ultimate Tinymce to WP Edit.', 'wp_edit_langs');
							echo '<br />';
							_e('Since so much has changed, not all options may convert successfully.', 'wp_edit_langs');
							echo '<br /><em>';
							_e('There will be no harm done to any other plugin or theme options.', 'wp_edit_langs');
							echo '</em>'; 
							?></p>
                            <form method="post">
                                <p><input type="submit" value="<?php _e('Convert Options', 'wp_edit_langs'); ?>" class="button button-primary" id="submit_extras" name="submit_convert_opts"></p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                
                    <div class="postbox">
                        <h3><span><?php _e('Export WP Edit Options', 'wp_edit_langs'); ?></span></h3>
                        <div class="inside">
                            <p><?php _e('Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'wp_edit_langs'); ?></p>
                            <form method="post">
                                <p><input type="hidden" name="database_action" value="export_settings" /></p>
                                <p>
                                <?php wp_nonce_field( 'database_action_export_nonce', 'database_action_export_nonce' ); ?>
                                <?php submit_button( __('Export', 'wp_edit_langs'), 'primary', 'submit', false ); ?>
                                </p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                                 
                    <div class="postbox">
                        <h3><span><?php _e('Import WP Edit Options', 'wp_edit_langs'); ?></span></h3>
                        <div class="inside">
                            <p><?php _e('Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'wp_edit_langs'); ?></p>
                            <form method="post" enctype="multipart/form-data">
                                <p><input type="file" name="import_file"/></p>
                                <p>
                                <input type="hidden" name="database_action" value="import_settings" />
                                <?php wp_nonce_field( 'database_action_import_nonce', 'database_action_import_nonce' ); ?>
                                <?php submit_button( __('Import', 'wp_edit_langs'), 'primary', 'submit', false ); ?>
                            	</p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                                 
                    <div class="postbox">
                        <h3><span><?php _e('Reset WP Edit Options', 'wp_edit_langs'); ?></span></h3>
                        <div class="inside">
                            <p><?php _e('Reset all plugin settings to their original default states.', 'wp_edit_langs'); ?></p>
                            <form method="post" action="">
                                <?php wp_nonce_field( 'reset_db_values_nonce', 'reset_db_values_nonce' ); ?>
                                <input class="button-primary" name="reset_db_values" type="submit" value="<?php _e('Reset Settings', 'wp_edit_langs'); ?>" />
                            	</p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                    
                    <div class="postbox">
                        <h3><span><?php _e('Uninstall WP Edit (Completely)', 'wp_edit_langs'); ?></span></h3>
                        <div class="inside">
                            <p><?php _e('Designed by intention, this plugin will not delete the associated database tables when activating and deactivating.', 'wp_edit_langs'); ?><br />
							   <?php _e('This ensures the data is kept safe when troubleshooting other WordPress conflicts.', 'wp_edit_langs'); ?><br />
							   <?php _e('In order to completely uninstall the plugin, AND remove all associated database tables, please use the option below.', 'wp_edit_langs'); ?><br />
							</p>
                            <form method="post" action="">
								<?php wp_nonce_field('wp_edit_uninstall_nonce_check','wp_edit_uninstall_nonce'); ?>
                                <input id="plugin" name="plugin" type="hidden" value="wp-edit/main.php" />
                                <input name="uninstall_confirm" id="uninstall_confirm" type="checkbox" value="1" /><label for="uninstall_confirm"></label> <strong><?php _e('Please confirm before proceeding','wp_edit_langs'); ?><br /><br /></strong>
                                <input class="button-primary" name="uninstall" type="submit" value="<?php _e('Uninstall',''); ?>" />
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                    
                </div><!-- .metabox-holder -->	
                <?php
			}
            ?>  
        </div>
        
        <?php
	}
	
	public function plugin_settings_link($links) {
		
		$settings_link = '<a href="admin.php?page=wp_edit_options">Settings</a>';
		$settings_link2 = '<a href="http://wpeditpro.com">Go Pro!</a>';
  		array_push( $links, $settings_link, $settings_link2 );
  		return $links;
	}
	
	
	/*
	****************************************************************
	Export Settings
	****************************************************************
	*/
	public function process_settings_export() {
		
		global $current_user;
		
		if( empty( $_POST['database_action'] ) || 'export_settings' != $_POST['database_action'] )
			return;
		 
		if( ! wp_verify_nonce( $_POST['database_action_export_nonce'], 'database_action_export_nonce' ) )
			return;
		 
		if( ! current_user_can( 'manage_options' ) )
			return;
		 
		
		// Get DB values
		$options_global = get_option('wp_edit_global');
		$options_buttons = get_option('wp_edit_buttons');
		$options_buttons_sidebars = get_option('wp_edit_buttons_sidebars');
		$options_general = get_option('wp_edit_general');
		$options_posts = get_option('wp_edit_posts');
		$options_editor = get_option('wp_edit_editor');
		$options_fonts = get_option('wp_edit_fonts');
		$options_widgets = get_option('wp_edit_widgets');
		$options_user_specific = get_user_meta($current_user->ID, 'aaa_wp_edit_user_meta', true);
		$options_extras = get_option('wp_edit_extras');
		
		$options_export_array = array(
			'wp_edit_global' => $options_global, 
			'wp_edit_buttons' => $options_buttons, 
			'wp_edit_buttons_sidebars' => $options_buttons_sidebars, 
			'wp_edit_general' => $options_general, 
			'wp_edit_posts' => $options_posts, 
			'wp_edit_editor' => $options_editor, 
			'wp_edit_fonts' => $options_fonts, 
			'wp_edit_widgets' => $options_widgets, 
			'wp_edit_user_specific' => $options_user_specific, 
			'wp_edit_extras' => $options_extras
		);
		 
		ignore_user_abort( true );
		 
		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=wp_edit_settings_export-' . date( 'm-d-Y' ) . '.json' );
		header( "Expires: 0" );
		 
		echo json_encode( $options_export_array );
		exit;
	}
	
	/*
	****************************************************************
	Import Settings
	****************************************************************
	*/
	public function process_settings_import() {
		
		if( empty( $_POST['database_action'] ) || 'import_settings' != $_POST['database_action'] )
			return;
		 
		if( ! wp_verify_nonce( $_POST['database_action_import_nonce'], 'database_action_import_nonce' ) )
			return;
		 
		if( ! current_user_can( 'manage_options' ) )
			return;
		 
		$extension = end( explode( '.', $_FILES['import_file']['name'] ) );
		 
		if( $extension != 'json' ) {
			wp_die( __('Please upload a valid .json file', 'wp_edit_langs' ) );
		}
		 
		$import_file = $_FILES['import_file']['tmp_name'];
		 
		if( empty( $import_file ) ) {
			wp_die( __('Please upload a file to import', 'wp_edit_langs') );
		}
		 
		// Retrieve the settings from the file and convert the json object to an array.
		$settings = (array) json_decode( file_get_contents( $import_file ), true );
		foreach ($settings as $key => $value) {
			
			echo $key;
			$value = (array) $value;
			
			update_option($key, $value);
		}
		 
		// Redirect to database page with added parameter = true
		wp_safe_redirect( admin_url( 'admin.php?page=wp_edit_options&tab=database&import=true' ) ); 
		exit;
	}
	
	/*
	****************************************************************
	Redirect after plugin activation
	****************************************************************
	*/
	public function wp_edit_redirect() {
		
		$re_url = admin_url( 'admin.php?page=wp_edit_options', 'http' );
		if (get_option('wp_edit_activation_redirect', false)) {
			
			delete_option('wp_edit_activation_redirect');
			wp_redirect($re_url);
		}
	}
}
$wp_edit = new wp_edit();





/*
**********************************
Build post/page metabox
**********************************
*/
$jwl_prefix = 'jwl_';  // Set prefix
$jwl_meta_box = array(  // Build metabox
	'id' => 'jwl-meta-box',
	'title' => __('WP Edit', 'wp_edit_langs'),
	'page' => 'post',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array( // Set fields for saving
		array(
			'name' => __('Disable QR for this post/page:', 'wp_edit_langs'),
			'id' => $jwl_prefix . 'qr_meta_checkbox',
			'type' => 'checkbox',
			'desc' => __('Disables QR only for this post/page.', 'wp_edit_langs')
		),
		array(
			'name' => __('QR Title:', 'wp_edit_langs'),
			'id' => $jwl_prefix . 'qr_meta_title',
			'type' => 'input'
		),
		array(
			'name' => __('QR Content:', 'wp_edit_langs'),
			'id' => $jwl_prefix . 'qr_meta_content',
			'type' => 'textarea'
		)
	)
);
// Add meta box
function wp_edit_add_box() {
	
	global $jwl_meta_box;
	add_meta_box($jwl_meta_box['id'], $jwl_meta_box['title'], 'wp_edit_show_box', $jwl_meta_box['page'], $jwl_meta_box['context'], $jwl_meta_box['priority']); // For Posts
	add_meta_box($jwl_meta_box['id'], $jwl_meta_box['title'], 'wp_edit_show_box', 'page', $jwl_meta_box['context'], $jwl_meta_box['priority']); // For Pages
}
add_action('admin_menu', 'wp_edit_add_box');


// Callback function to show fields in meta box
function wp_edit_show_box() {
	
	global $jwl_meta_box, $post;
	
	// Use nonce for verification
	echo '<input type="hidden" name="jwl_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" /><h3>';
	_e('QR Code Options:', 'wp_edit_langs');
	echo '</h3><p>'.__('These options are post/page specific.', 'wp_edit_langs').'<br />';
	echo __('They will override corresponding global options set on the admin WP Edit settings page.', 'wp_edit_langs').'</p>';
	
	
	$options_qr = get_option('wp_edit_extras');
	$options_qr = $options_qr['enable_qr'];
	
	if ($options_qr == '1') {
		echo '<table class="form-table">';
	} 
	else {
		echo '<table class="form-table" style="display:none;">';
	}
		
	foreach ($jwl_meta_box['fields'] as $field) {
		// get current post meta data
		$meta = get_post_meta($post->ID, $field['id'], true);
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td>';
				
			switch ($field['type']) {
				
				case 'checkbox':
					echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta=='on' ? ' checked="checked"' : '', ' /> ' . $field['desc'];
					break;
				case 'input':
					echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="60" />';
					break;
				case 'textarea':
					echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>';
					break;
			}
		echo     '</td></tr>';
	}
	echo '</table>';
	
	if ($options_qr != '1') {
		?><p style="color:#999999"><?php printf(__('Deactivated. If required, please activate via the <a href="%1$s">admin settings page</a>.', 'wp_edit_langs'), 'admin.php?page=wp_edit_options&tab=extras'); ?></p><?php 
	}
}
// Save data from meta box
add_action('save_post', 'wp_edit_save_data');
function wp_edit_save_data($post_id) {
	
	global $jwl_meta_box;
	// verify nonce
	if (( !isset( $_POST['jwl_meta_box_nonce'] ) || !wp_verify_nonce($_POST['jwl_meta_box_nonce'], basename(__FILE__)))) {
		return $post_id;
	}
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	
	
	foreach ($jwl_meta_box['fields'] as $field) {
		
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
	
}




/*
****************************************************************
AJAX Call to update toolbar buttons
****************************************************************	
*/
add_action( 'wp_ajax_wp_edit_wp_ajax', 'wp_edit_wp_ajax_callback' );
function wp_edit_wp_ajax_callback() {
	
	$run_query = $_POST['run_query'];
	$query_status = __('Ajax is working... but having trouble connecting with the database.', 'wp_edit_langs');
	
	// If sortable rows have changed
	if($run_query === 'log_from' || $run_query === 'log_to') {

		$ids = $_POST['ids'];
		$toolbar = $_POST['toolbar'];
		
		$options_buttons = get_option('wp_edit_buttons');
		$options_buttons[$toolbar] = $ids;
		update_option('wp_edit_buttons', $options_buttons);
		
		$query_status = 'Success';
	}
	
	// If reset rows button was clicked - buttons tab
	if($run_query === 'reset_db_buttons') {
		
		$reset_buttons = array(
			'toolbar1' => 'bold italic strikethrough bullist numlist blockquote alignleft aligncenter alignright link unlink wp_more fullscreen hr', 
			'toolbar2' => 'formatselect underline alignjustify forecolor pastetext removeformat charmap outdent indent undo redo wp_help', 
			'toolbar3' => '', 
			'toolbar4' => '',
			'tmce_container' => 'fontselect fontsizeselect styleselect backcolor media rtl ltr table anchor code emoticons inserttime wp_page preview print searchreplace visualblocks subscript superscript image_orig advlink'
		);
		
		update_option('wp_edit_buttons', $reset_buttons);
		
		$query_status = 'Success';
	}
	
	// If restore settings was clicked - editor tab
	if($run_query === 'restore_editor') {
		
		$options_restore_editor = array(
			'enable_editor' => '0',
			'editor_font' => 'Verdana, Arial, Helvetica, sans-serif',
			'editor_font_color' => '000000',
			'editor_font_size' => '13px',
			'editor_line_height' => '19px',
			'editor_body_padding' => '0px',
			'editor_body_margin' => '10px',
			'editor_text_direction' => 'ltr',
			'editor_text_indent' => '0px',
			'editor_bg_color' => 'FFFFFF'
		);
		
		update_option('wp_edit_editor', $options_restore_editor);
		
		$query_status = 'Success';
	}
		
	// Return response
	$response = json_encode(array('query_status' => $query_status));
	header( "Content-Type: application/json" );
	echo $response;

	die();
}


/*
****************************************************************
Initialize the tinymce editor with the selected buttons... 
The HEART of the plugin!
****************************************************************	
*/

function wp_edit_tinymce_init_various_values($init) {
	
	// Init table ability
	$init['tools'] = 'inserttable';
	
	// Init editor font px or pt
	$opts_editor = get_option('wp_edit_editor');
	$tinymce_px = (isset($opts_editor['editor_tinymce_px']) && $opts_editor['editor_tinymce_px'] === '1') ? '1' : '0';
	if($tinymce_px === '1') {
		$new_px = '6px 8px 9px 10px 11px 12px 13px 14px 15px 16px 18px 20px 22px 24px 28px 32px 48px 72px';
		if(empty($init['fontsize_formats'])) {
			$init['fontsize_formats'] = $new_px;
		}
		else {
			$init['fontsize_formats'] = $init['fontsize_formats'].' '.$new_px;
		}
	}
	else {
		$new_pt = '6pt 8pt 10pt 12pt 14pt 16pt 18pt 20pt 22pt 24pt 26pt 28pt 30pt 32pt 34pt 36pt 48pt 72pt';
		if(empty($init['fontsize_formats'])) {
			$init['fontsize_formats'] = $new_pt;
		}
		else {
			$init['fontsize_formats'] = $init['fontsize_formats'].' '.$new_pt;
		}
	}
	
	return $init;
}
add_filter( 'tiny_mce_before_init', 'wp_edit_tinymce_init_various_values' );


function wp_edit_init_tinymce() {
	
	// Build extra plugins array
	add_filter( 'mce_external_plugins', 'wp_edit_mce_external_plugins' );
	
	// Get options and set appropriate tinymce toolbars
	$options_buttons = get_option('wp_edit_buttons');
	foreach ($options_buttons as $key => $value) {
		
		// Magic is happening right here...
		if($key == 'tmce_container') { return; }
		if($key == 'toolbar1') { add_filter( 'mce_buttons', 'wp_edit_add_mce' ); }
	}
}
add_action('init', 'wp_edit_init_tinymce');

// Build extra plugins array
function wp_edit_mce_external_plugins($init) {
	
	$init['directionality'] = plugins_url() . '/wp-edit/plugins/directionality/plugin.min.js';
	$init['table'] = plugins_url() . '/wp-edit/plugins/table/plugin.min.js';
	$init['anchor'] = plugins_url() . '/wp-edit/plugins/anchor/plugin.min.js';
	$init['code'] = plugins_url() . '/wp-edit/plugins/code/plugin.min.js';
	$init['emoticons'] = plugins_url() . '/wp-edit/plugins/emoticons/plugin.min.js';
	$init['hr'] = plugins_url() . '/wp-edit/plugins/hr/plugin.min.js';
	$init['insertdatetime'] = plugins_url() . '/wp-edit/plugins/insertdatetime/plugin.min.js';
	$init['preview'] = plugins_url() . '/wp-edit/plugins/preview/plugin.min.js';
	$init['print'] = plugins_url() . '/wp-edit/plugins/print/plugin.min.js';
	$init['searchreplace'] = plugins_url() . '/wp-edit/plugins/searchreplace/plugin.min.js';
	$init['visualblocks'] = plugins_url() . '/wp-edit/plugins/visualblocks/plugin.min.js';
	$init['image_orig'] = plugins_url() . '/wp-edit/plugins/image_orig/plugin.min.js';
	$init['advlink'] = plugins_url() . '/wp-edit/plugins/advlink/plugin.js';
	
	return $init;
}

/**************************************************************************************************************************************************************
//**  Okay... for each of the tinymce button rows.. we must perform a series of steps to prevent overwriting other buttons 
//**  added by other plugins or themes.  
//**
//**  Step 1:  We get our saved options for the specific toolbar.
//**  Step 2:  We explode our saved buttons into an array
//**  Step 3:  We have to compare our saved array (which is only defualt WP buttons and anything we add).. against the wp array coming into the filter.
//**           This is the issue with overwriting other buttons.  We have to take the difference (which is buttons we have not added), by comparing our
//**           saved array agains the filtered array.  The difference will consist of buttons added by other plugins or themes.
//**  Step 4:  Now that we have the difference.. we can merge the array onto the end of our custom array.
//**  Step 5:  Magic!  Now we have our user sorted buttons... with any other plugin or theme buttons appended to the end of the respective row.
/**************************************************************************************************************************************************************/

function wp_edit_add_mce($buttons) {
	
	$options = get_option('wp_edit_buttons');
	$options_toolbar1 = $options['toolbar1'];
	$default_wp_array_toolbar1 = array('bold','italic','strikethrough','bullist','numlist','blockquote','alignleft','aligncenter','alignright','link','unlink','wp_more','fullscreen','hr');
	$array_back = array();
	
	// First, we explode the toolbar in the database
	$options_toolbar1 = explode(' ', $options_toolbar1);
	
	// Next, we get the difference between ($options['toolbar1']) and ($buttons)
	$array_diff = array_diff($buttons, $options_toolbar1);
	
	// Now, we take the array and loop it to find original buttons
	if($array_diff) {
		
		foreach($array_diff as $array) {
			
			// If the button is NOT in the original array (WP buttons), we know it is another plugin or theme button..
			if(!in_array($array, $default_wp_array_toolbar1)) {
				
				// Create the new array of additional buttons to pass back to end of toolbar
				$array_back[] = $array;
			}
		}
	}
	
	// Merge the difference onto the end of our saved buttons
	$merge_buttons = array_merge($options_toolbar1, $array_back);
	
	return $merge_buttons;
}




/*
****************************************************************
Include functions for running global options
****************************************************************
*/
include( plugin_dir_path( __FILE__ ) . '/functions.php');

/*
****************************************************************
Include functions for running user specific options
****************************************************************
*/
include( plugin_dir_path( __FILE__ ) . '/user_functions.php');

?>