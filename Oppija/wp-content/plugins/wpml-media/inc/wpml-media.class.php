<?php

class WPML_Media
{
	private static $settings;
	private static $settings_option_key = '_wpml_media';
	public $languages;
	public $parents;
	public $unattached;

	function __construct( $ext = false )
	{
		add_action( 'plugins_loaded', array( $this, 'init' ), 2 );
	}

	public static function has_settings()
	{
		return get_option( self::$settings_option_key );
	}

	function init()
	{
		$this->plugin_localization();

		$dependencies = new WPML_Media_Dependencies();
		if ( !$dependencies->check() ) {
			return false;
		}

		WPML_Media_Upgrade::run();

		self::init_settings();

		$this->overrides();

		global $wpdb;
		global $sitepress, $pagenow;

		if ( !self::get_setting( 'starting_help' ) && ( empty( $_GET[ 'page' ] ) || $_GET[ 'page' ] != 'wpml-media' ) ) {

			$total_attachments = $wpdb->get_var( "
                SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'attachment' AND ID NOT IN
                (SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'wpml_media_processed')" );

			if ( $total_attachments ) {
				if ( count( $sitepress->get_active_languages() ) > 1 ) {
					add_action( 'admin_notices', array( $this, 'first_time_notice' ) );
				}
			} else {
				self::update_setting( 'starting_help', 1 );
			}

		}

		$this->languages = null;

		if ( is_admin() ) {

			add_action( 'admin_head', array( $this, 'js_scripts' ) );

			if ( 1 < count( $sitepress->get_active_languages() ) ) {

				add_action( 'admin_menu', array( $this, 'menu' ) );
				add_filter( 'manage_media_columns', array( $this, 'manage_media_columns' ), 10, 1 );
				add_action( 'manage_media_custom_column', array( $this, 'manage_media_custom_column' ), 10, 2 );
				//add_filter('manage_upload_sortable_columns', array($this, 'manage_upload_sortable_columns'));
				add_action( 'parse_query', array( $this, 'parse_query' ) );
				//add_filter( 'posts_where', array( $this, 'posts_where_filter' ) );
				add_filter( 'views_upload', array( $this, 'views_upload' ) );
				add_action( 'icl_post_languages_options_after', array( $this, 'language_options' ) );

				// Post/page save actions
				add_action( 'save_post', array( $this, 'save_post_actions' ), 10, 2 );
				add_action( 'icl_make_duplicate', array( $this, 'make_duplicate' ), 10, 4 );
				add_action( 'updated_postmeta', array( $this, 'updated_postmeta' ), 10, 4 );

				add_action( 'add_attachment', array( $this, 'save_attachment_actions' ) );
				add_action( 'add_attachment', array( $this, 'save_translated_attachments' ) );
				add_action( 'edit_attachment', array( $this, 'save_attachment_actions' ) );

				// Attachment filters
				add_filter( 'wp_update_attachment_metadata', array( $this, 'update_attachment_metadata' ), 10, 2 );

				//wp_delete_file file filter
				add_filter( 'wp_delete_file', array( $this, 'delete_file' ) );
				remove_action( 'delete_post', array( $sitepress, 'delete_post_actions' ) );
				add_action( 'delete_post', array( $this, 'delete_post_actions' ) );

				if ( $pagenow == 'media-upload.php' ) {
					//Add the language filter to the media library
					add_action( 'media_upload_library', array( $this, 'language_filter' ), 99 );
				}

				if ( $pagenow == 'media.php' ) {
					add_action( 'admin_footer', array( $this, 'media_language_options' ) );
				}

				if ( $pagenow == 'upload.php' ) {
					//Add the language filter to the media library (language_filter): /wp-admin/upload.php
					add_action( 'admin_footer', array( $this, 'language_filter_upload_page' ) );
				}

				add_action( 'wp_ajax_wpml_media_dismiss_starting_help', array( $this, 'dismiss_wpml_media_starting_help' ) );

				add_action( 'wp_ajax_wpml_media_set_initial_language', array( $this, 'batch_set_initial_language' ) );
				add_action( 'wp_ajax_wpml_media_translate_media', array( $this, 'batch_translate_media' ) );
				add_action( 'wp_ajax_wpml_media_duplicate_media', array( $this, 'batch_duplicate_media' ) );
				add_action( 'wp_ajax_wpml_media_duplicate_featured_images', array( $this, 'batch_duplicate_featured_images' ) );

				add_action( 'wp_ajax_wpml_media_mark_processed', array( $this, 'batch_mark_processed' ) );
				add_action( 'wp_ajax_wpml_media_scan_prepare', array( $this, 'batch_scan_prepare' ) );

				add_action( 'wp_ajax_wpml_media_set_content_prepare', array( $this, 'set_content_defaults_prepare' ) );
				add_action( 'wp_ajax_wpml_media_set_content_defaults', array( $this, 'set_content_defaults' ) );

				add_action( 'wp_ajax_set-post-thumbnail', array( $this, 'ajax_set_post_thumbnail' ), 0 );
				add_action( 'wp_ajax_find_posts', array( $this, 'find_posts_filter' ), 0 );
			}

		}

		//add_filter('get_post_metadata', array($this, 'get_post_metadata'), 10, 4);
		add_filter( 'WPML_filter_link', array( $this, 'filter_link' ), 10, 2 );
		add_filter( 'icl_ls_languages', array( $this, 'icl_ls_languages' ), 10, 1 );
		add_action( 'icl_pro_translation_saved', array( $this, 'icl_pro_translation_saved' ), 10, 1 );

		return null;
	}

	function plugin_localization()
	{
		load_plugin_textdomain( 'wpml-media', false, WPML_MEDIA_FOLDER . '/locale' );
	}

	/**
	 *    Needed by class init and by all static methods that use self::$settings
	 */
	public static function init_settings()
	{
		if ( !self::$settings )
			self::$settings = get_option( self::$settings_option_key );

		$default_settings = array(
			'version'              => false,
			'starting_help'        => false,
			'new_content_settings' => array(
				'always_translate_media' => true,
				'duplicate_media'        => true,
				'duplicate_featured'     => true
			)
		);

		if ( !self::$settings ) {
			self::$settings = $default_settings;
		}
	}

	/**
	 *    This method, called on 'plugins_loaded' action, overrides or replaces WPML default behavior
	 */
	public function overrides()
	{
		global $sitepress, $pagenow;

		//Removes the WPML language metabox on media and replace it with the custom one
		remove_action( 'admin_head', array( $sitepress, 'post_edit_language_options' ) );
		if ( $pagenow == 'post.php' || $pagenow == 'post-new.php' ) {
			add_action( 'admin_head', array( $this, 'post_edit_language_options' ) );
		}

		//Removes the default WPML post_join and post_where filters
		remove_action( 'posts_join', array( $sitepress, 'posts_join_filter' ), 10 );
		remove_action( 'posts_where', array( $sitepress, 'posts_where_filter' ), 10 );
		//... and use the custom ones
		add_filter( 'posts_join', array( $this, 'posts_join_filter' ), 10, 2 );
		add_filter( 'posts_where', array( $this, 'posts_where_filter' ), 10, 2 );

	}

	public static function get_setting( $name, $default = false )
	{
		self::init_settings();
		if ( !isset( self::$settings[ $name ] ) || !self::$settings[ $name ] )
			return $default;

		return self::$settings[ $name ];
	}

	public static function update_setting( $name, $value )
	{
		self::init_settings();
		self::$settings[ $name ] = $value;

		return update_option( self::$settings_option_key, self::$settings );
	}

	function post_edit_language_options()
	{
		global $post, $sitepress, $pagenow;

		//Removes the language metabox on media
		if ( isset( $_POST[ 'wp-preview' ] ) && $_POST[ 'wp-preview' ] == 'dopreview' || is_preview() ) {
			$is_preview = true;
		} else {
			$is_preview = false;
		}

		//If not a media admin page, call the default WPML post_edit_language_options() method
		if ( !( $pagenow == 'upload.php' || $pagenow == 'media-upload.php' || is_attachment() || $is_preview || ( isset( $post ) && $post->post_type == 'attachment' ) ) ) {
			$sitepress->post_edit_language_options();
		}

	}

	function __destruct()
	{
		return;
	}

	function first_time_notice()
	{
		?>
		<div class="updated message">
			<p>
				<?php _e( 'WPML Media Translation needs to set languages to existing media in your site.', 'wpml-media' ) ?>
				<a href="<?php echo admin_url( 'admin.php?page=wpml-media' ) ?>" class="button-secondary"><?php _e( 'Set media languages', 'wpml-media' ) ?></a>

				<a id="wpml_media_dismiss_1" style="float: right;" href="#"
				   onclick="jQuery.ajax({url:ajaxurl,method:'POST',data:{action:'wpml_media_dismiss_starting_help'},success:function(){jQuery('#wpml_media_dismiss_1').closest('.message').fadeOut()}}); return false;"><?php _e( "Dismiss", 'wpml-media' ) ?></a>
			</p>
		</div>
	<?php
	}

	function dismiss_wpml_media_starting_help()
	{
		self::update_setting( 'starting_help', 1 );
		exit;
	}

	function set_content_defaults_prepare()
	{
		$response = array( 'message' => __( 'Started...', 'wpml-media' ) );
		echo json_encode( $response );
		exit;
	}

	function set_content_defaults()
	{
		$always_translate_media = $_POST[ 'always_translate_media' ];
		$duplicate_media        = $_POST[ 'duplicate_media' ];
		$duplicate_featured     = $_POST[ 'duplicate_featured' ];

		$content_defaults_option = array(
			'always_translate_media' => $always_translate_media == 'true',
			'duplicate_media'        => $duplicate_media == 'true',
			'duplicate_featured'     => $duplicate_featured == 'true'
		);

		$result = self::update_setting( 'new_content_settings', $content_defaults_option );

		$response = array(
			'result'  => $result,
			'message' => __( 'Default settings stores.', 'wpml-media' )
		);
		echo json_encode( $response );
		exit;
	}

	function batch_scan_prepare()
	{
		global $wpdb;

		$response = array();
		$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key='wpml_media_processed'" );

		$response[ 'message' ] = __( 'Started...', 'wpml-media' );

		echo json_encode( $response );
		exit;
	}

	function batch_set_initial_language()
	{
		global $wpdb, $sitepress;

		$default_language = $sitepress->get_default_language();
		$limit            = 10;

		$response    = array();
		$attachments = $wpdb->get_col( "
            SELECT SQL_CALC_FOUND_ROWS ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND ID NOT IN
            (SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE element_type='post_attachment') LIMIT {$limit}" );

		$found = $wpdb->get_var( "SELECT FOUND_ROWS()" );

		foreach ( $attachments as $attachment_id ) {
			$sitepress->set_element_language_details( $attachment_id, 'post_attachment', false, $default_language );
		}
		$response[ 'left' ] = max( $found - $limit, 0 );
		if ( $response[ 'left' ] ) {
			$response[ 'message' ] = sprintf( __( 'Setting language to media. %d left', 'wpml-media' ), $response[ 'left' ] );
		} else {
			$response[ 'message' ] = sprintf( __( 'Setting language to media: done!', 'wpml-media' ), $response[ 'left' ] );
		}

		echo json_encode( $response );
		exit;
	}

	function batch_translate_media()
	{
		global $wpdb, $sitepress;

		$response = array();

		$limit = 10;

		$active_languages = count( $sitepress->get_active_languages() );
		$sql              = "
            SELECT SQL_CALC_FOUND_ROWS p1.ID, p1.post_parent
            FROM {$wpdb->prefix}icl_translations t
            INNER JOIN {$wpdb->posts} p1
            	ON t.element_id = p1.ID
            LEFT JOIN {$wpdb->prefix}icl_translations tt
            	ON t.trid = tt.trid
			WHERE t.element_type LIKE 'post_attachment'
				AND t.source_language_code IS null
			GROUP BY p1.ID, p1.post_parent
			HAVING count(tt.language_code) < $active_languages
            LIMIT {$limit}
        ";
		$attachments      = $wpdb->get_results( $sql );

		$found = $wpdb->get_var( "SELECT FOUND_ROWS()" );

		if ( $attachments ) {
			foreach ( $attachments as $attachment ) {
				$lang = $sitepress->get_element_language_details( $attachment->ID, 'post_attachment' );
				if ( $lang->language_code == $sitepress->get_default_language() ) {

					$this->translate_attachments( $attachment->ID, $lang->language_code );

				}
			}
		}

		$response[ 'left' ] = max( $found - $limit, 0 );;
		if ( $response[ 'left' ] ) {
			$response[ 'message' ] = sprintf( __( 'Translating media. %d left', 'wpml-media' ), $response[ 'left' ] );
		} else {
			$response[ 'message' ] = sprintf( __( 'Translating media: done!', 'wpml-media' ), $response[ 'left' ] );
		}

		echo json_encode( $response );
		exit;
	}

	function translate_attachments( $attachment_id, $source_language )
	{
		$content_defaults = self::get_setting( 'new_content_settings' );
		if ( !empty( $source_language ) && $content_defaults[ 'always_translate_media' ] ) {

			global $sitepress;

			$default_language_attachment_id = false;
			$trid                           = $sitepress->get_element_trid( $attachment_id, 'post_attachment' );
			if ( $trid ) {
				$translations         = $sitepress->get_element_translations( $trid, 'post_attachment', true, true );
				$translated_languages = false;
				foreach ( $translations as $translation ) {
					//Get the default language attachment ID
					if ( $translation->language_code == $sitepress->get_default_language() ) {
						$default_language_attachment_id = $translation->element_id;
					}
					//Store already translated versions
					$translated_languages[ ] = $translation->language_code;
				}
				// Attachment in default language is missing
				if ( !$default_language_attachment_id && $source_language != $sitepress->get_default_language() ) {
					$attachment = get_post( $attachment_id );
					self::create_duplicate_attachment( $attachment_id, $attachment->post_parent, $sitepress->get_default_language() );
					//Start over
					$this->translate_attachments( $attachment_id, $source_language );
				} else {
					//Attachment in default language is present
					if ( $default_language_attachment_id ) {
						$original = get_post( $default_language_attachment_id );
						$codes    = array_keys( $sitepress->get_active_languages() );
						foreach ( $codes as $code ) {
							//If translation is not present, create it
							if ( !in_array( $code, $translated_languages ) ) {
								self::create_duplicate_attachment( $attachment_id, $original->post_parent, $code );
							}
						}
					}
				}
			}
		}

	}

	function create_duplicate_attachment( $attachment_id, $parent_id, $target_language )
	{
		global $sitepress;

		$duplicated_attachment_id = false;
		$translated_parent_id     = false;

		$trid            = $sitepress->get_element_trid( $attachment_id, 'post_attachment' );
		$source_language = null;
		if ( $trid ) {
			//Get the source language of the attachment, just in case is from a language different than the default
			$source_language         = $sitepress->get_language_for_element( $attachment_id, 'post_attachment' );
			$attachment_translations = $sitepress->get_element_translations( $trid, 'post_attachment', true, true );
			foreach ( $attachment_translations as $attachment_translation ) {
				if ( $attachment_translation->language_code == $target_language ) {
					$duplicated_attachment_id = $attachment_translation->element_id;
					$duplicated_attachment    = get_post( $duplicated_attachment_id );
					$translated_parent_id     = $duplicated_attachment->post_parent ? $duplicated_attachment->post_parent : $parent_id;
					if ( $translated_parent_id ) {
						$parent_post = get_post( $translated_parent_id );

						if ( $parent_post ) {
							$parent_id_language_code = $sitepress->get_language_for_element( $parent_post->ID, 'post_' . $parent_post->post_type );
							if ( $parent_id_language_code != $target_language ) {
								$translated_parent_id = icl_object_id( $parent_post->ID, $parent_post->post_type, false, $target_language );
							} else {
								$translated_parent_id = $parent_post->ID;
							}
						}
					}
					break;
				} else {
					if ( $parent_id ) {
						$parent_post             = get_post( $parent_id );
						$parent_id_language_code = $sitepress->get_language_for_element( $parent_post->ID, 'post_' . $parent_post->post_type );
						if ( $parent_id_language_code != $target_language ) {
							$translated_parent_id = icl_object_id( $parent_post->ID, $parent_post->post_type, false, $target_language );
						} else {
							$translated_parent_id = $parent_post->ID;
						}
					} else {
						$translated_parent_id = false;
					}
				}
			}
		}

		if ( $duplicated_attachment_id ) {
			$post              = get_post( $duplicated_attachment_id );
			$post->post_parent = $translated_parent_id;
			if ( $this->is_valid_post_type( $post->post_type ) ) {
				wp_update_post( $post );
			}

		} else {
			$post = get_post( $attachment_id );
			//Do not attach this media if _wpml_media_duplicate is not set
			$post->post_parent        = $translated_parent_id;
			$post->ID                 = null;
			$duplicated_attachment_id = wp_insert_post( $post );

			$sitepress->set_element_language_details( $duplicated_attachment_id, 'post_attachment', $trid, $target_language, $source_language );
		}

		// duplicate the post meta data.
		//$meta = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );
		//update_post_meta( $duplicated_attachment_id, '_wp_attachment_metadata', $meta );
		update_post_meta( $duplicated_attachment_id, 'wpml_media_processed', 1 );
		$attached_file = get_post_meta( $attachment_id, '_wp_attached_file', true );
		update_post_meta( $duplicated_attachment_id, '_wp_attached_file', $attached_file );

		$attachment_metadata = get_post_meta( $attachment_id, '_wp_attachment_metadata', true );

		//Update _wp_attachment_metadata FROM the original
		if ( $attachment_metadata ) {
			update_post_meta( $duplicated_attachment_id, '_wp_attachment_metadata', $attachment_metadata );
		} else {
			//Update _wp_attachment_metadata TO the original
			$attachment_metadata = get_post_meta( $duplicated_attachment_id, '_wp_attachment_metadata', true );
			if ( $attachment_metadata ) {
				update_post_meta( $attachment_id, '_wp_attachment_metadata', $attachment_metadata );
			} else {
				//Tries to find the first _wp_attachment_metadata available, then updates all translations
				$trid = $sitepress->get_element_trid( $attachment_id, 'post_attachment' );
				if ( $trid ) {
					$translations = $sitepress->get_element_translations( $trid, 'post_attachment', true, true );
					foreach ( $translations as $translation ) {
						$attachment_metadata = get_post_meta( $translation->element_id, '_wp_attachment_metadata', true );
						if ( $attachment_metadata ) {
							break;
						}
					}
					if ( $attachment_metadata ) {
						$this->update_attachment_metadata( $attachment_metadata, $attachment_id );
					}
				}
			}
		}
		do_action( 'wpml_media_create_duplicate_attachment', $attachment_id, $duplicated_attachment_id );

		return $duplicated_attachment_id;
	}

	function is_valid_post_type( $post_type )
	{
		global $wp_post_types;

		$post_types = array_keys( (array)$wp_post_types );

		return in_array( $post_type, $post_types );
	}

	function update_attachment_metadata( $data, $attachment_id )
	{
		global $sitepress;
		$trid = $sitepress->get_element_trid( $attachment_id, 'post_attachment' );
		if ( $trid ) {
			$translations = $sitepress->get_element_translations( $trid, 'post_attachment', true, true );
			foreach ( $translations as $translation ) {
				if ( $translation->language_code != $sitepress->get_default_language() ) {
					update_post_meta( $translation->element_id, '_wp_attachment_metadata', $data );
				}
			}
		}

		return $data;
	}

	function batch_duplicate_media()
	{
		global $wpdb;

		$limit = 10;

		$response = array();

		$attachments = $wpdb->get_results( "
            SELECT SQL_CALC_FOUND_ROWS p1.ID, p1.post_parent
            FROM {$wpdb->posts} p1
            WHERE post_type = 'attachment'
            AND ID NOT IN
            	(SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'wpml_media_processed')
            ORDER BY p1.ID ASC LIMIT {$limit}
        " );
		$found       = $wpdb->get_var( "SELECT FOUND_ROWS()" );

		if ( $attachments ) {
			foreach ( $attachments as $attachment ) {
				$this->create_duplicated_media( $attachment );
			}
		}

		$response[ 'left' ] = max( $found - $limit, 0 );
		if ( $response[ 'left' ] ) {
			$response[ 'message' ] = sprintf( __( 'Duplicating media. %d left', 'wpml-media' ), $response[ 'left' ] );
		} else {
			$response[ 'message' ] = sprintf( __( 'Duplicating media: done!', 'wpml-media' ), $response[ 'left' ] );
		}

		echo json_encode( $response );
		exit;
	}

	/**
	 * @param $attachment WP_Post
	 */
	function create_duplicated_media( $attachment )
	{
		global $wpdb, $sitepress;

		static $parents_processed = array();

		if ( $attachment->post_parent && !in_array( $attachment->post_parent, $parents_processed ) ) {

			// see if we have translations.
			$post_type = $wpdb->get_var( "SELECT post_type FROM {$wpdb->posts} WHERE ID = $attachment->post_parent" );
			$trid      = $wpdb->get_var( "SELECT trid FROM {$wpdb->prefix}icl_translations WHERE element_id={$attachment->post_parent} AND element_type = 'post_$post_type'" );
			if ( $trid ) {

				update_post_meta( $attachment->post_parent, '_wpml_media_duplicate', 1 );

				$attachments = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_parent = $attachment->post_parent" );

				$translations = $sitepress->get_element_translations( $trid, 'post_' . $post_type );
				foreach ( $translations as $translation ) {
					//Check that the post is not marked as duplicate of another post
					$is_duplicate = get_post_meta( $translation->element_id, '_icl_lang_duplicate_of' );
					//if ( !$is_duplicate ) {
						if ( $translation->element_id && $translation->element_id != $attachment->post_parent ) {

							$attachments_in_translation = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'attachment' AND post_parent = $translation->element_id" );
							if ( sizeof( $attachments_in_translation ) == 0 ) {
								// only duplicate attachments if there a none already.
								foreach ( $attachments as $attachment_id ) {
									// duplicate the attachment
									self::create_duplicate_attachment( $attachment_id, $translation->element_id, $translation->language_code );
								}
							}
						}
					//}
				}
			}

			$parents_processed[ ] = $attachment->post_parent;

		} else {
			// no parent - set to default language

			$target_language = $sitepress->get_default_language();

			//Getting the trid and language, just in case image translation already exists
			$trid = $sitepress->get_element_trid( $attachment->ID, 'post_attachment' );
			if ( $trid ) {
				$target_language = $sitepress->get_language_for_element( $attachment->ID, 'post_attachment' );
			}

			$sitepress->set_element_language_details( $attachment->ID, 'post_attachment', $trid, $target_language );

		}
		update_post_meta( $attachment->ID, 'wpml_media_processed', 1 );
	}

	function batch_duplicate_featured_images()
	{
		$limit = 10;

		$response = array();

		$found = $this->duplicate_featured_images( $limit );

		$response[ 'left' ] = max( $found - $limit, 0 );
		if ( $response[ 'left' ] ) {
			$response[ 'message' ] = sprintf( __( 'Duplicating featured images. %d left', 'wpml-media' ), $response[ 'left' ] );
		} else {
			$response[ 'message' ] = sprintf( __( 'Duplicating featured images: done!', 'wpml-media' ), $response[ 'left' ] );
		}

		echo json_encode( $response );
		exit;
	}

	static function duplicate_featured_images( $limit = 0 )
	{
		global $wpdb, $sitepress;

		$count = 0;

		$featured_images_sql = "SELECT * FROM {$wpdb->postmeta} WHERE meta_key = '_thumbnail_id'";
		if ( $limit > 0 ) {
			$featured_images_sql .= " LIMIT {$limit}";
		}
		$featured_images = $wpdb->get_results( $featured_images_sql );
		$processed       = $wpdb->get_var( "SELECT FOUND_ROWS()" );

		$thumbnails = array();
		foreach ( $featured_images as $featured ) {
			$thumbnails[ $featured->post_id ] = $featured->meta_value;
		}

		if ( sizeof( $thumbnails ) ) {
			//Posts IDs with found featured images
			$post_ids = implode( ', ', array_keys( $thumbnails ) );
			$posts    = $wpdb->get_results( "SELECT ID, post_type FROM {$wpdb->posts} WHERE ID in ({$post_ids})" );
			foreach ( $posts as $post ) {
				$row = $wpdb->get_row( "SELECT trid, source_language_code FROM {$wpdb->prefix}icl_translations WHERE element_id={$post->ID} AND element_type = 'post_$post->post_type'" );
				if ( $row && $row->trid && ( $row->source_language_code == null || $row->source_language_code == "" ) ) {
					update_post_meta( $post->ID, '_wpml_media_featured', 1 );

					$translations = $sitepress->get_element_translations( $row->trid, 'post_' . $post->post_type );
					foreach ( $translations as $translation ) {
						if ( $translation->element_id != $post->ID ) {
							//Check that the post is not marked as duplicate of another post
							$is_duplicate = get_post_meta( $translation->element_id, '_icl_lang_duplicate_of' );
							//if ( !$is_duplicate ) {
								if ( !in_array( $translation->element_id, array_keys( $thumbnails ) ) ) {

									// translation doesn't have a featured image
									$t_thumbnail_id = icl_object_id( $thumbnails[ $post->ID ], 'attachment', false, $translation->language_code );
									if ( $t_thumbnail_id == null ) {
										$dup_att_id     = self::create_duplicate_attachment( $thumbnails[ $post->ID ], $translation->element_id, $translation->language_code );
										$t_thumbnail_id = $dup_att_id;
									}

									if ( $t_thumbnail_id != null ) {
										update_post_meta( $translation->element_id, '_thumbnail_id', $t_thumbnail_id );
									}
									$count += 1;
								} elseif ( $thumbnails[ $post->ID ] ) {
									update_post_meta( $translation->element_id, '_thumbnail_id', $thumbnails[ $post->ID ] );
								}
							//}
							//Double check that there is a _thumbnail_id set and in case update _wpml_media_featured
							if ( get_post_meta( $translation->element_id, '_thumbnail_id', true ) ) {
								update_post_meta( $translation->element_id, '_wpml_media_featured', 1 );
							}
						}
					}
				}

			}
		}

		return $processed;
	}

	function batch_mark_processed()
	{
		global $wpdb;

		$response = array();
		$atts     = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type='attachment'" );
		foreach ( $atts as $att ) {
			update_post_meta( $att, 'wpml_media_processed', 1 );
		}

		self::update_setting( 'starting_help', 1 );

		$response[ 'message' ] = __( 'Done!', 'wpml-media' );

		echo json_encode( $response );

		exit;


	}

	function ajax_set_post_thumbnail()
	{
		$post_id = isset( $_POST[ 'post_id' ] ) ? $_POST[ 'post_id' ] : false;

		//$this->sync_attachments( $pidd, $post );
		$this->sync_post_thumbnail( $post_id );

		//exit;
	}

	function sync_post_thumbnail( $post_id )
	{
		global $sitepress;

		if ( $post_id && get_post_meta( $post_id, '_wpml_media_featured', true ) ) {

			$thumbnail_id = isset( $_POST[ 'thumbnail_id' ] ) ? $_POST[ 'thumbnail_id' ] : get_post_meta( $post_id, '_thumbnail_id', true );
			$trid         = $sitepress->get_element_trid( $post_id, 'post_' . get_post_type( $post_id ) );
			$translations = $sitepress->get_element_translations( $trid, 'post_' . get_post_type( $post_id ) );

			// is original
			$is_original = false;
			foreach ( $translations as $translation ) {
				if ( $translation->original == 1 && $translation->element_id == $post_id ) {
					$is_original = true;
				}
			}

			if ( $is_original ) {
				foreach ( $translations as $language => $translation ) {
					if ( !$translation->original && $translation->element_id ) {
						//Check that the post is not marked as duplicate of another post
						$is_duplicate = get_post_meta( $translation->element_id, '_icl_lang_duplicate_of' );
						//if ( !$is_duplicate ) {
							if ( !$thumbnail_id || $thumbnail_id == "-1" ) {
								delete_post_meta( $translation->element_id, '_thumbnail_id' );
							} else {
								$translated_thumbnail_id = icl_object_id( $thumbnail_id, 'attachment', false, $translation->language_code );
								update_post_meta( $translation->element_id, '_thumbnail_id', $translated_thumbnail_id );
							}

						//}
					}
				}
			}
		}

	}

	function find_posts_filter()
	{
		add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
	}

	function pre_get_posts( $query )
	{
		$query->query[ 'suppress_filters' ]      = 0;
		$query->query_vars[ 'suppress_filters' ] = 0;
	}

	function media_language_options()
	{
		global $sitepress;
		$att_id       = $_GET[ 'attachment_id' ];
		$translations = $sitepress->get_element_translations( $att_id, 'post_attachment' );
		$current_lang = '';
		foreach ( $translations as $lang => $id ) {
			if ( $id == $att_id ) {
				$current_lang = $lang;
				unset( $translations[ $lang ] );
				break;
			}
		}

		$active_languages = icl_get_languages( 'orderby=id&order=asc&skip_missing=0' );
		$lang_links       = '';

		if ( $current_lang ) {

			$lang_links = '<strong>' . $active_languages[ $current_lang ][ 'native_name' ] . '</strong>';

		}

		foreach ( $translations as $lang => $id ) {
			$lang_links .= ' | <a href="' . admin_url( 'media.php?attachment_id=' . $id . '&action=edit' ) . '">' . $active_languages[ $lang ][ 'native_name' ] . '</a>';
		}


		echo '<div id="icl_lang_options" style="display:none">' . $lang_links . '</div>';
	}

	function icl_pro_translation_saved( $new_post_id )
	{
		global $wpdb;

		//$post_type = $wpdb->get_var("SELECT post_type FROM {$wpdb->posts} WHERE ID = " . $new_post_id);
		$trid = $_POST[ 'trid' ];
		$lang = $_POST[ 'lang' ];

		$source_lang = $wpdb->get_var( "SELECT language_code FROM {$wpdb->prefix}icl_translations WHERE trid={$trid} AND source_language_code IS NULL" );

		$this->duplicate_post_attachments( $new_post_id, $trid, $source_lang, $lang );
	}

	function duplicate_post_attachments( $pidd, $icl_trid, $source_lang = null, $lang = null )
	{
		global $wpdb, $sitepress;
		if ( $icl_trid == "" ) {
			return;
		}

		if ( !$source_lang ) {
			$source_lang = $wpdb->get_var( "SELECT source_language_code FROM {$wpdb->prefix}icl_translations WHERE element_id = $pidd AND trid = $icl_trid" );
		}

		// exception for making duplicates. language info not set when this runs and creating the duplicated posts 1/3
		if ( isset( $_POST[ 'icl_ajx_action' ] ) && $_POST[ 'icl_ajx_action' ] == 'make_duplicates' && isset( $_POST[ 'icl_post_language' ] ) ) {
			$source_lang = $wpdb->get_var( $wpdb->prepare( "SELECT language_code FROM {$wpdb->prefix}icl_translations WHERE element_id = %d AND trid = %d", $_POST[ 'post_id' ], $icl_trid ) );
			$lang        = $_POST[ 'icl_post_language' ];

		}

		if ( $source_lang == null || $source_lang == "" ) {
			// This is the original see if we should copy to translations

			$duplicate = get_post_meta( $pidd, '_wpml_media_duplicate', true );
			$featured  = get_post_meta( $pidd, '_wpml_media_featured', true );
			if ( $duplicate || $featured ) {
				$translations = $wpdb->get_col( "SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE trid = $icl_trid" );

				foreach ( $translations as $element_id ) {
					//Check that the post is not marked as duplicate of another post
					$is_duplicate = get_post_meta( $element_id, '_icl_lang_duplicate_of' );
					//if ( !$is_duplicate ) {
						if ( $element_id && $element_id != $pidd ) {
							$duplicate_t = $duplicate;
							if ( $duplicate_t ) {
								// See if the translation is marked for duplication
								$duplicate_t = get_post_meta( $element_id, '_wpml_media_duplicate', true );
							}

							$lang = $wpdb->get_var( "SELECT language_code FROM {$wpdb->prefix}icl_translations WHERE element_id = $element_id AND trid = $icl_trid" );
							if ( $duplicate_t || $duplicate_t == '' ) {
								$source_attachments = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_parent = $pidd AND post_type = 'attachment'" );
								$attachments        = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_parent = $element_id AND post_type = 'attachment'" );

								foreach ( $source_attachments as $source_attachment_id ) {
									$found = false;
									foreach ( $attachments as $attachment_id ) {
										$translation_attachment_id = icl_object_id( $attachment_id, 'attachment', false, $lang );
										if ( $translation_attachment_id ) {
											//If attachment has no parent, treat it as not found
											$parent_post = get_post( $translation_attachment_id );
											if ( !$parent_post ) {
												$found = true;
											}
											break;
										}
									}

									if ( !$found ) {
										self::create_duplicate_attachment( $source_attachment_id, $element_id, $lang );
									}
								}
							}

							$featured_t = $featured;
							if ( $featured_t ) {
								// See if the translation is marked for duplication
								$featured_t = get_post_meta( $element_id, '_wpml_media_featured', true );
							}
							if ( $featured_t || $featured_t == '' ) {
								$thumbnail_id = get_post_meta( $pidd, '_thumbnail_id', true );
								if ( $thumbnail_id ) {
									$t_thumbnail_id = icl_object_id( $thumbnail_id, 'attachment', false, $lang );
									if ( $t_thumbnail_id == null ) {
										$dup_att_id     = self::create_duplicate_attachment( $thumbnail_id, $element_id, $lang );
										$t_thumbnail_id = $dup_att_id;
									}

									if ( $t_thumbnail_id != null ) {
										update_post_meta( $element_id, '_thumbnail_id', $t_thumbnail_id );
									}
								}
							}
						}
					//}
				}
			}

		} else {
			// This is a translation.

			// exception for making duplicates. language info not set when this runs and creating the duplicated posts 2/3
			if ( isset( $_POST[ 'icl_ajx_action' ] ) && $_POST[ 'icl_ajx_action' ] == 'make_duplicates' ) {
				$source_id = $_POST[ 'post_id' ];
			} else {
				$source_id = $wpdb->get_var( "SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE language_code = '$source_lang' AND trid = $icl_trid" );
			}

			if ( !$lang ) {
				$lang = $wpdb->get_var( "SELECT language_code FROM {$wpdb->prefix}icl_translations WHERE element_id = $pidd AND trid = $icl_trid" );
			}

			// exception for making duplicates. language info not set when this runs and creating the duplicated posts 3/3
			if ( isset( $_POST[ 'icl_ajx_action' ] ) && $_POST[ 'icl_ajx_action' ] == 'make_duplicates' ) {
				$duplicate = get_post_meta( $source_id, '_wpml_media_duplicate', true );
			} else {
				$duplicate = get_post_meta( $pidd, '_wpml_media_duplicate', true );
				if ( !$duplicate ) {
					// check the original state
					$duplicate = get_post_meta( $source_id, '_wpml_media_duplicate', true );
				}
			}

			if ( $duplicate ) {
				$source_attachments = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_parent = $source_id AND post_type = 'attachment'" );

				foreach ( $source_attachments as $source_attachment_id ) {
					$translation_attachment_id = icl_object_id( $source_attachment_id, 'attachment', false, $lang );

					if ( !$translation_attachment_id ) {
						$is_duplicate = get_post_meta( $pidd, '_icl_lang_duplicate_of' );
						//if ( !$is_duplicate ) {
							self::create_duplicate_attachment( $source_attachment_id, $pidd, $lang );
						//}
					} else {
						$translated_attachment = get_post( $translation_attachment_id );
						if ( $translated_attachment && !$translated_attachment->post_parent ) {
							$translated_attachment->post_parent = $pidd;
							wp_update_post( $translated_attachment );
						}
					}

				}
			}

			$featured = get_post_meta( $pidd, '_wpml_media_featured', true );
			if ( $featured === "" ) {
				// check the original state
				$featured = get_post_meta( $source_id, '_wpml_media_featured', true );
			}

			if ( $featured ) {
				$thumbnail_id = get_post_meta( $source_id, '_thumbnail_id', true );
				if ( $thumbnail_id ) {
					$t_thumbnail_id = icl_object_id( $thumbnail_id, 'attachment', false, $lang );
					if ( $t_thumbnail_id == null ) {
						$dup_att_id     = self::create_duplicate_attachment( $thumbnail_id, $pidd, $lang );
						$t_thumbnail_id = $dup_att_id;
					}

					if ( $t_thumbnail_id != null ) {
						update_post_meta( $pidd, '_thumbnail_id', $t_thumbnail_id );
					}
				}

			}

		}

	}

	/**
	 * @param $pidd int
	 * @param $post wp_post
	 */
	function save_post_actions( $pidd, $post )
	{
		if ( $post->post_type != 'attachment' && $post->post_status != "auto-draft" ) {
			$this->sync_attachments( $pidd, $post );
			$this->sync_post_thumbnail( $pidd );
		}
	}

	/**
	 * @param $pidd int
	 * @param $post wp_post
	 */
	function sync_attachments( $pidd, $post )
	{
		if ( $post->post_type == 'attachment' || $post->post_status == "auto-draft" ) return;

		global $wpdb, $sitepress;

		list( $post_type, $post_status ) = $wpdb->get_row( "SELECT post_type, post_status FROM {$wpdb->posts} WHERE ID = " . $pidd, ARRAY_N );

		//checking - if translation and not saved before
		if ( isset( $_GET[ 'trid' ] ) && !empty( $_GET[ 'trid' ] ) && $post_status == 'auto-draft' ) {

			//get source language
			if ( isset( $_GET[ 'source_lang' ] ) && !empty( $_GET[ 'source_lang' ] ) ) {
				$src_lang = $_GET[ 'source_lang' ];
			} else {
				$src_lang = $sitepress->get_default_language();
			}

			//get source id
			$src_id = $wpdb->get_var( "SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE trid={$_GET['trid']} AND language_code='{$src_lang}'" );

			//delete exist auto-draft post media
			$results     = $wpdb->get_results( "SELECT p.id FROM {$wpdb->posts} AS p LEFT JOIN {$wpdb->posts} AS p1 ON p.post_parent = p1.id WHERE p1.post_status = 'auto-draft'", ARRAY_A );
			$attachments = array();
			if ( !empty( $results ) ) {
				foreach ( $results as $result ) {
					$attachments[ ] = $result[ "id" ];
				}
				if ( !empty( $attachments ) ) {
					$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE id IN (" . join( ',', $attachments ) . ")" );
					$wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE post_id IN (" . join( ',', $attachments ) . ")" );
				}
			}

			//checking - if set duplicate media
			if ( get_post_meta( $src_id, '_wpml_media_duplicate', true ) ) {
				//duplicate media before first save
				$this->duplicate_post_attachments( $pidd, $_GET[ 'trid' ], $src_lang, $sitepress->get_language_for_element( $pidd, 'post_' . $post_type ) );
			}
		}

		// exceptions
		if (
			!$sitepress->is_translated_post_type( $post_type )
			|| isset( $_POST[ 'autosave' ] )
			|| ( isset( $_POST[ 'post_ID' ] ) && $_POST[ 'post_ID' ] != $pidd ) || ( isset( $_POST[ 'post_type' ] ) && $_POST[ 'post_type' ] == 'revision' )
			|| $post_type == 'revision'
			|| get_post_meta( $pidd, '_wp_trash_meta_status', true )
			|| ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] == 'restore' )
			|| $post_status == 'auto-draft'
		) {
			return;
		}

		if ( isset( $_POST[ 'icl_trid' ] ) ) {
			$content_defaults_option = self::get_setting( 'new_content_settings' );
			$duplicate = false;
			$featured = false;
			if($content_defaults_option) {
				if ( isset( $_POST[ 'icl_duplicate_attachments' ] ) ) {
					$duplicate = intval( $_POST[ 'icl_duplicate_attachments' ] );
				}
				if ( isset( $_POST[ 'icl_duplicate_featured_image' ] ) ) {
					$featured = intval( $_POST[ 'icl_duplicate_featured_image' ] );
				}
			}

			// save the post from the edit screen.
			update_post_meta( $pidd, '_wpml_media_duplicate', $duplicate );
			update_post_meta( $pidd, '_wpml_media_featured', $featured );

			$icl_trid = $_POST[ 'icl_trid' ];
		} else {
			// get trid from database.
			$icl_trid = $wpdb->get_var( "SELECT trid FROM {$wpdb->prefix}icl_translations WHERE element_id={$pidd} AND element_type = 'post_$post_type'" );
		}

		if ( $icl_trid ) {
			$language_details = $sitepress->get_element_language_details( $pidd, 'post_' . $post_type );

			// In some cases the sitepress cache doesn't get updated (e.g. when posts are created with wp_insert_post()
			// Only in this case, the sitepress cache will be cleared so we can read the element language details
			if ( !$language_details ) {
				$sitepress->icl_translations_cache->clear();
				$language_details = $sitepress->get_element_language_details( $pidd, 'post_' . $post_type );
			}
			if ( $language_details ) {
				$this->duplicate_post_attachments( $pidd, $icl_trid, $language_details->source_language_code, $language_details->language_code );
			}
		}
	}

	function make_duplicate( $master_post_id, $target_lang, $post_array, $target_post_id )
	{
		global $wpdb, $sitepress;

		//Get Master Post attachments
		$master_post_attachment_ids = $wpdb->get_col( "SELECT ID FROM $wpdb->posts WHERE post_parent = $master_post_id AND post_type = 'attachment'" );

		if ( $master_post_attachment_ids ) {
			foreach ( $master_post_attachment_ids as $master_post_attachment_id ) {

				$attachment_trid = $sitepress->get_element_trid( $master_post_attachment_id, 'post_attachment' );

				//Get attachment translation
				$attachment_translations = $sitepress->get_element_translations( $attachment_trid, 'post_attachment' );

				$translated_attachment_id = false;
				foreach ( $attachment_translations as $attachment_translation ) {
					if ( $attachment_translation->language_code == $target_lang ) {
						$translated_attachment_id = $attachment_translation->element_id;
						break;
					}
				}

				if ( !$translated_attachment_id ) {
					$translated_attachment_id = $this->create_duplicate_attachment( $master_post_attachment_id, wp_get_post_parent_id( $master_post_id ), $target_lang );
				}

				if ( $translated_attachment_id ) {
					//Set the parent post, if not already set
					$translated_attachment = get_post( $translated_attachment_id );
					if ( !$translated_attachment->post_parent ) {
						$prepared_query = $wpdb->prepare( "UPDATE {$wpdb->posts} SET post_parent=%d WHERE ID=%d", $target_post_id, $translated_attachment_id );
						$wpdb->query( $prepared_query );
					}
				}

			}
		}
	}

	//Synchronizing post metas with all translations

	function updated_postmeta( $meta_id, $object_id, $meta_key, $meta_value )
	{
		if ( in_array( $meta_key, array( '_wpml_media_duplicate', '_wpml_media_featured' ) ) ) {
			global $sitepress;
			$el_type      = 'post_' . get_post_type( $object_id );
			$trid         = $sitepress->get_element_trid( $object_id, $el_type );
			$translations = $sitepress->get_element_translations( $trid, $el_type, true, true );
			foreach ( $translations as $translation ) {
				if ( $translation->element_id != $object_id ) {
					$t_meta_value = get_post_meta( $translation->element_id, $meta_key, true );
					if ( $t_meta_value != $meta_value ) {
						update_post_meta( $translation->element_id, $meta_key, $meta_value );
					}
				}
			}
		}
	}

	function save_attachment_actions( $post_id )
	{
		global $wpdb, $sitepress;

		$media_language = $sitepress->get_language_for_element( $post_id, 'post_attachment' );
		$trid           = false;
		if ( !empty( $media_language ) ) {
			$trid = $sitepress->get_element_trid( $post_id, 'post_attachment' );
		}
		if ( empty( $media_language ) ) {
			$parent_post = $wpdb->get_row( $wpdb->prepare(
											   "SELECT p2.ID, p2.post_type FROM $wpdb->posts p1 JOIN $wpdb->posts p2 ON p1.post_parent =p2.ID WHERE p1.ID=%d"
											   , $post_id ) );

			if ( $parent_post ) {
				$media_language = $sitepress->get_language_for_element( $parent_post->ID, 'post_' . $parent_post->post_type );
			}

			if ( empty( $media_language ) ) {
				$media_language = $sitepress->get_admin_language_cookie();
			}
			if ( empty( $media_language ) ) {
				$media_language = $sitepress->get_default_language();
			}

		}
		if ( !empty( $media_language ) ) {
			$sitepress->set_element_language_details( $post_id, 'post_attachment', $trid, $media_language );
		}
	}

	function save_translated_attachments( $post_id )
	{
		global $sitepress;
		$language_details = $sitepress->get_element_language_details( $post_id, 'post_attachment' );
		$this->translate_attachments( $post_id, $language_details->language_code );
	}

	function language_options()
	{
		global $icl_meta_box_globals, $wpdb;

		$translation   = false;
		$source_id     = null;
		$translated_id = null;
		if ( sizeof( $icl_meta_box_globals[ 'translations' ] ) > 0 ) {
			if ( !isset( $icl_meta_box_globals[ 'translations' ][ $icl_meta_box_globals[ 'selected_language' ] ] ) ) {
				// We are creating a new translation
				$translation = true;
				// find the original
				foreach ( $icl_meta_box_globals[ 'translations' ] as $trans_data ) {
					if ( $trans_data->original == '1' ) {
						$source_id = $trans_data->element_id;
						break;
					}
				}
			} else {
				$trans_data = $icl_meta_box_globals[ 'translations' ][ $icl_meta_box_globals[ 'selected_language' ] ];
				// see if this is an original or a translation.
				if ( $trans_data->original == '0' ) {
					// double check that it's not the original
					// This is because the source_language_code field in icl_translations is not always set to null.

					$source_language_code = $wpdb->get_var( "SELECT source_language_code FROM {$wpdb->prefix}icl_translations WHERE translation_id = $trans_data->translation_id" );
					$translation          = !( $source_language_code == "" || $source_language_code == null );
					if ( $translation && isset($icl_meta_box_globals[ 'translations' ][ $source_language_code ]) ) {
						$source_id     = $icl_meta_box_globals[ 'translations' ][ $source_language_code ]->element_id;
						$translated_id = $trans_data->element_id;
					} else {
						$source_id = $trans_data->element_id;
					}
				} else {
					$source_id = $trans_data->element_id;
				}
			}
		}

		//This is a translation with no original content
		if($translation && !$translated_id) return;

		echo '<br /><br /><strong>' . __( 'Media attachments', 'wpml-media' ) . '</strong>';

		$checked = '';
		if ( $translation ) {
			//This is a translation
			if ( $translated_id ) {
				$duplicate = get_post_meta( $translated_id, '_wpml_media_duplicate', true );
				//If not set, or false, always overrides with the source/original setting
				if ( !$duplicate ) {
					// use the source/original state
					$duplicate = get_post_meta( $source_id, '_wpml_media_duplicate', true );
				}
				$featured = get_post_meta( $translated_id, '_wpml_media_featured', true );
				//If not set, or false, always overrides with the source/original setting
				if ( !$featured ) {
					// use the source/state
					$featured = get_post_meta( $source_id, '_wpml_media_featured', true );
				}

			} else {
				// This is a new translation, use the source/original settings.
				$duplicate = get_post_meta( $source_id, '_wpml_media_duplicate', true );
				$featured  = get_post_meta( $source_id, '_wpml_media_featured', true );
			}

			if ( $duplicate ) {
				$checked = ' checked="checked"';
			}
			echo '<br /><label><input name="icl_duplicate_attachments" type="checkbox" value="1" ' . $checked . '/>&nbsp;' . __( 'Duplicate uploaded media from original', 'wpml-media' ) . '</label>';

			if ( $featured ) {
				$checked = ' checked="checked"';
			} else {
				$checked = '';
			}
			echo '<br /><label><input name="icl_duplicate_featured_image" type="checkbox" value="1" ' . $checked . '/>&nbsp;' . __( 'Duplicate featured image from original', 'wpml-media' ) . '</label>';
		} else {
			//This is the source/original content
			$content_defaults_option = self::get_setting( 'new_content_settings' );
			if ( $content_defaults_option && !isset( $_GET[ 'post' ] ) ) {
				$duplicate = $content_defaults_option[ 'duplicate_media' ];
				$featured  = $content_defaults_option[ 'duplicate_featured' ];
			} else {
				$duplicate = get_post_meta( $source_id, '_wpml_media_duplicate', true );
				$featured  = get_post_meta( $source_id, '_wpml_media_featured', true );
			}

			if ( $duplicate ) {
				$checked = ' checked="checked"';
			}
			echo '<br /><label><input name="icl_duplicate_attachments" type="checkbox" value="1" ' . $checked . '/>&nbsp;' . __( 'Duplicate uploaded media to translations', 'wpml-media' ) . '</label>';

			if ( $featured ) {
				$checked = ' checked="checked"';
			} else {
				$checked = '';
			}
			echo '<br /><label><input name="icl_duplicate_featured_image" type="checkbox" value="1" ' . $checked . '/>&nbsp;' . __( 'Duplicate featured image to translations', 'wpml-media' ) . '</label>';
		}
	}

	function manage_media_columns( $posts_columns )
	{
		if ( isset( $_REQUEST[ 'lang' ] ) && $_REQUEST[ 'lang' ] == 'all' )
			$posts_columns[ 'language' ] = __( 'Language', 'wpml-media' );

		return $posts_columns;
	}

	function manage_media_custom_column( $column_name, $id )
	{
		if ( isset( $_REQUEST[ 'lang' ] ) && $_REQUEST[ 'lang' ] == 'all' && $column_name == 'language' ) {
			global $sitepress;
			if ( !empty( $this->languages[ $id ] ) ) {
				echo $sitepress->get_display_language_name( $this->languages[ $id ], $sitepress->get_admin_language() );
			} else {
				echo __( 'None', 'wpml-media' );
			}
		}
	}

	function parse_query( $q )
	{
		global $pagenow;
		if ( $pagenow == 'upload.php' || $pagenow == 'media-upload.php' || ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'query-attachments' ) ) {

			$this->_get_lang_info();

		}
	}

	function _get_lang_info()
	{
		global $wpdb;

		// get the attachment languages.
		//if query-attachments need display all attachments
		if ( ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'query-attachments' ) ) {
			$results = $wpdb->get_results( "SELECT ID, post_parent FROM {$wpdb->posts} WHERE post_type='attachment'" );
		} else {
			//don't display attachments auto-draft posts
			$results = $wpdb->get_results( "SELECT p.ID, p.post_parent FROM {$wpdb->posts} AS p LEFT JOIN {$wpdb->posts} AS p1 ON p.post_parent = p1.id WHERE p1.post_status <> 'auto-draft' AND p.post_type='attachment'" );
		}
		$this->parents    = array();
		$this->unattached = array();
		foreach ( $results as $result ) {
			$this->parents[ $result->ID ] = $result->post_parent;
			if ( !$result->post_parent ) {
				$this->unattached[ ] = $result->ID;
			}
		}
		if ( ( isset( $_POST[ 'action' ] ) && $_POST[ 'action' ] == 'query-attachments' ) ) {
			//don't display attachments auto-draft posts
			$results = $wpdb->get_results( "
											SELECT p.id, t.language_code
											FROM {$wpdb->posts} AS p
												LEFT JOIN {$wpdb->posts} AS p1 ON p.post_parent = p1.id
												INNER JOIN {$wpdb->prefix}icl_translations AS t ON p.id = t.element_id
											WHERE p1.post_status <> 'auto-draft'
												AND t.element_type='post_attachment'
										" );
		} else {
			$results = $wpdb->get_results( "
											SELECT p.id, t.language_code
											FROM {$wpdb->posts} AS p
												INNER JOIN {$wpdb->prefix}icl_translations AS t ON p.id = t.element_id
											WHERE t.element_type='post_attachment'
										" );
		}


		$this->languages = array();
		foreach ( $results as $result ) {
			$this->languages[ $result->id ] = $result->language_code;
		}

		// determine list of att without language set (with their parents)
		foreach ( $this->parents as $att_id => $parent_id ) {
			if ( !isset( $this->languages[ $att_id ] ) && isset( $parent_languages[ $parent_id ] ) ) {
				$missing_languages[ $att_id ] = $parent_id;
			}
		}
		// get language of their parents
		if ( !empty( $missing_languages ) ) {
			$results = $wpdb->get_results( "
                SELECT p.ID, t.language_code
                FROM {$wpdb->posts} p JOIN {$wpdb->prefix}icl_translations t ON p.ID = t.element_id AND t.element_type = CONCAT('post_', p.post_type)
                WHERE p.ID IN(" . join( ',', $missing_languages ) . ")
            " );
			foreach ( $results as $row ) {
				$parent_languages[ $row->ID ] = $row->language_code;
			}
		}

		// set language of their parents
		if ( isset( $parent_languages ) )
			foreach ( $this->parents as $att_id => $parent_id ) {
				if ( !isset( $this->languages[ $att_id ] ) ) {
					$this->languages[ $att_id ] = $parent_languages[ $parent_id ];
				}
			}

	}

	/**
	 *Add a filter to fix the links for attachments in the language switcher so
	 *they point to the corresponding pages in different languages.
	 */
	function filter_link( $url, $lang_info )
	{
		return $url;
	}

	function icl_ls_languages( $w_active_languages )
	{
		static $doing_it = false;

		if ( is_attachment() && !$doing_it ) {
			$doing_it = true;
			// Always include missing languages.
			$w_active_languages = icl_get_languages( 'skip_missing=0' );
			$doing_it           = false;
		}

		return $w_active_languages;
	}

	function get_post_metadata( $value, $object_id, $meta_key, $single )
	{
		if ( $meta_key == '_thumbnail_id' ) {

			global $wpdb;

			$thumbnail = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$object_id} AND meta_key = '{$meta_key}'" );

			if ( $thumbnail == null ) {
				// see if it's available in the original language.

				$post_type = $wpdb->get_var( "SELECT post_type FROM {$wpdb->posts} WHERE ID = $object_id" );
				$trid      = $wpdb->get_row( "SELECT trid, source_language_code FROM {$wpdb->prefix}icl_translations WHERE element_id={$object_id} AND element_type = 'post_$post_type'" );
				if ( $trid ) {

					global $sitepress;

					$translations = $sitepress->get_element_translations( $trid->trid, 'post_' . $post_type );
					if ( isset( $translations[ $trid->source_language_code ] ) ) {
						$translation = $translations[ $trid->source_language_code ];
						// see if the original has a thumbnail.
						$thumbnail = $wpdb->get_var( "SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$translation->element_id} AND meta_key = '{$meta_key}'" );
						if ( $thumbnail ) {
							$value = $thumbnail;
						}
					}
				}
			} else {
				$value = $thumbnail;
			}

		}

		return $value;
	}

	function menu()
	{
		$top_page = apply_filters( 'icl_menu_main_page', basename( ICL_PLUGIN_PATH ) . '/menu/languages.php' );

		add_submenu_page( $top_page,
						  __( 'Media translation', 'wpml-media' ),
						  __( 'Media translation', 'wpml-media' ), 'manage_options',
						  'wpml-media', array( $this, 'menu_content' ) );
	}

	function menu_content()
	{
		global $wpdb;

		//Used by management.php
		$orphan_attachments = $wpdb->get_var( "
            SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'attachment' AND ID NOT IN
             (SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE element_type='post_attachment') " );


		include WPML_MEDIA_PATH . '/menu/management.php';
	}

	function js_scripts()
	{
		global $pagenow;
		if ( $pagenow == 'media.php' ) {
			wp_enqueue_script( 'wpml-media-language-options', WPML_MEDIA_URL . '/res/js/language_options.js', array(), WPML_MEDIA_VERSION, true );
		}
		if ( isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'wpml-media' ) {
			wp_enqueue_script( 'wpml-media-settings', WPML_MEDIA_URL . '/res/js/settings.js', array(), WPML_MEDIA_VERSION, true );
		}
	}

	function language_filter()
	{
		global $sitepress;

		$lang_code = null;
		if ( isset( $_GET[ 'lang' ] ) ) {
			$lang_code = $_GET[ 'lang' ];
		} else {
			if ( method_exists( $sitepress, 'get_admin_language_cookie' ) ) {
				$lang_code = $sitepress->get_admin_language_cookie();
			}
		}

		$active_languages = $sitepress->get_active_languages();

		$active_languages[ ] = array( 'code' => 'all', 'display_name' => __( 'All languages', 'sitepress' ) );
		$language_items      = array();
		foreach ( $active_languages as $lang ) {
			if ( $lang[ 'code' ] == $lang_code ) {
				$px = '<strong>';
				$sx = ' <span class="count">(' . $lang[ 'code' ] . ')<\/span><\/strong>';
			} else {
				$px = '<a href="' . $_SERVER[ 'REQUEST_URI' ] . '&lang=' . $lang[ 'code' ] . '">';
				$sx = '<\/a> <span class="count">(' . $lang[ 'code' ] . ')<\/span>';
			}
			$language_items[ ] = $px . $lang[ 'display_name' ] . $sx;
		}

		wp_enqueue_script( 'wpml-media-language-options', WPML_MEDIA_URL . '/res/js/language_options.js', array(), WPML_MEDIA_VERSION, true );
		wp_localize_script( 'wpml-media-language-options', 'language_items', $language_items );
	}

	//check if the image is not duplicated to another post before deleting it physically

	function views_upload( $views )
	{
		global $sitepress, $wpdb, $pagenow;

		if ( $pagenow == 'upload.php' ) {
			//get current language
			$lang = $sitepress->get_current_language();

			foreach ( $views as $key => $view ) {
				if ( $lang != 'all' ) {
					$sql = "
						SELECT COUNT(p.id)
						FROM {$wpdb->posts} AS p
							INNER JOIN {$wpdb->prefix}icl_translations AS t
								ON p.id = t.element_id
						WHERE p.post_type = 'attachment'
						AND t.element_type='post_attachment'
						AND t.language_code='" . $lang . "'
					";

					switch ( $key ) {
						case 'all';
							break;
						case 'detached':
							$sql .= " AND p.post_parent = 0 ";
							break;
						default:
							$sql .= " AND p.post_mime_type LIKE '" . $key . "%'";
					}

					$res = $wpdb->get_col( $sql );
					//replace count
					$view = preg_replace( '/\((\d+)\)/', '(' . $res[ 0 ] . ')', $view );
				}
				//replace href link
				if ( $key == 'all' ) {
					$views[ $key ] = preg_replace( '/(href=["\'])([\s\S]+?)(["\'])/', '$1$2?lang=' . $lang . '$3', $view );
				} else {
					$views[ $key ] = preg_replace( '/(href=["\'])([\s\S]+?)(["\'])/', '$1$2&lang=' . $lang . '$3', $view );
				}
			}
		}

		return $views;
	}

	function language_filter_upload_page()
	{
		global $sitepress, $wpdb;

		//get language code
		if ( isset( $_GET[ 'lang' ] ) ) {
			$lang_code = $_GET[ 'lang' ];
		} else {
			if ( method_exists( $sitepress, 'get_admin_language_cookie' ) ) {
				$lang_code = $sitepress->get_admin_language_cookie();
			}
			if ( empty( $lang_code ) ) {
				$lang_code = $sitepress->get_default_language();
			}
		}

		$active_languages = $sitepress->get_active_languages();

		$active_languages[ ] = array( 'code' => 'all', 'display_name' => __( 'All languages', 'sitepress' ) );

		$langc[ 'all' ] = 0;
		$language_items = array();
		foreach ( $active_languages as $lang ) {
			//select all attachments
			$sql = "
				SELECT COUNT(p.id)
				FROM {$wpdb->posts} AS p
				INNER JOIN {$wpdb->prefix}icl_translations AS t
					ON t.element_id = p.id
				WHERE p.post_type = 'attachment'
				AND t.element_type ='post_attachment'
				AND t.language_code='" . $lang[ 'code' ] . "'
			";
			//select detached attachments
			if ( isset( $_GET[ 'detached' ] ) )
				$sql .= " AND p.post_parent = 0 ";
			//select mime type(image,etc) attachments
			if ( isset( $_GET[ 'post_mime_type' ] ) )
				$sql .= " AND p.post_mime_type LIKE '" . $_GET[ 'post_mime_type' ] . "%'";
			$res = $wpdb->get_col( $sql );

			//count attachments
			if ( $lang[ 'code' ] != 'all' )
				$langc[ $lang[ 'code' ] ] = $res[ 0 ];
			$langc[ 'all' ] += $res[ 0 ];

			//generation language block
			if ( $lang[ 'code' ] == $lang_code ) {
				$px = '<strong>';
				$sx = ' <span class="count">(' . $langc[ $lang[ 'code' ] ] . ')</span></strong>';
			} else {
				if ( isset( $_GET[ 'post_mime_type' ] ) ) {
					$px = '<a href="?post_mime_type=' . $_GET[ 'post_mime_type' ] . '&lang=' . $lang[ 'code' ] . '">';
				} elseif ( isset( $_GET[ 'detached' ] ) ) {
					$px = '<a href="?detached=' . $_GET[ 'detached' ] . '&lang=' . $lang[ 'code' ] . '">';
				} else {
					$px = '<a href="?lang=' . $lang[ 'code' ] . '">';
				}
				$sx = '</a> <span class="count">(' . $langc[ $lang[ 'code' ] ] . ')</span>';
			}
			$language_items[ ] = $px . $lang[ 'display_name' ] . $sx;
		}

		wp_enqueue_script( 'wpml-media-language-options', WPML_MEDIA_URL . '/res/js/language_options.js', array(), WPML_MEDIA_VERSION, true );
		wp_localize_script( 'wpml-media-language-options', 'language_items', $language_items );

	}

	function delete_file( $file )
	{
		if ( $file ) {
			global $wpdb;
			//get file name from full name
			$file_name = preg_replace( '/^(.+)\-\d+x\d+(\.\w+)$/', '$1$2', $file );
			$file_name = preg_replace( '/^[\s\S]+(\/.+)$/', '$1', $file_name );
			//check file name in DB
			$attachment = $wpdb->get_row( "SELECT pm.meta_id, pm.post_id FROM {$wpdb->postmeta} AS pm WHERE pm.meta_value LIKE '%" . $file_name . "'" );
			//if exist return NULL(do not delete physically)
			if ( !empty( $attachment ) ) {
				$file = null;
			}
		}

		return $file;
	}

	//Overrides default $sitepress behavior

	function delete_post_actions( $post_id )
	{
		global $wpdb, $sitepress;

		$sitepress_settings = $sitepress->get_settings();

		static $deleted_posts;

		if ( isset( $deleted_posts[ $post_id ] ) ) {
			return; // avoid infinite loop
		}

		if ( $sitepress_settings[ 'sync_delete' ] ) {
			$post_type = $wpdb->get_var( "SELECT post_type FROM {$wpdb->posts} WHERE ID={$post_id}" );

			if ( empty( $deleted_posts ) && $post_type == 'attachment' ) {
				$trid         = $sitepress->get_element_trid( $post_id, 'post_' . $post_type );
				$translations = $sitepress->get_element_translations( $trid, 'post_' . $post_type );
				foreach ( $translations as $t ) {
					$deleted_posts[ ] = $post_id;
					wp_delete_post( $t->element_id );
				}
			}
		}
		$sitepress->delete_post_actions( $post_id );
	}

	function posts_join_filter( $join, $query )
	{
		global $wpdb, $wp_taxonomies, $sitepress;

		// determine post type
		$db = debug_backtrace();
		// exception - recent posts widget
		$post_type = false;
		if ( $db[ 3 ][ 'function' ] == 'get_posts' && isset( $db[ 5 ][ 'file' ] ) && basename( $db[ 5 ][ 'file' ] ) == 'default-widgets.php' ) {
			$post_type = 'post';
		} else {
			foreach ( $db as $k => $o ) {
				if ( $o[ 'function' ] == 'apply_filters_ref_array' && $o[ 'args' ][ 0 ] == 'posts_join' ) {
					$post_type = esc_sql( $o[ 'args' ][ 1 ][ 1 ]->query_vars[ 'post_type' ] );
					break;
				}
			}
		}

		if ( $post_type == 'any' || 'all' == $sitepress->get_current_language() ) {
			$post_type_join = "LEFT";
		} else {
			$post_type_join = "";
		}

		if ( is_array( $post_type ) ) {
			$post_types = array();
			foreach ( $post_type as $post_type_item ) {
				if ( $sitepress->is_translated_post_type( $post_type_item ) ) {
					$post_types[ ] = esc_sql( 'post_' . $post_type_item );
				}
			}
			if ( !empty( $post_types ) ) {
				$join .= " {$post_type_join} JOIN {$wpdb->prefix}icl_translations t ON {$wpdb->posts}.ID = t.element_id
                     AND t.element_type IN ('" . join( "','", $post_types ) . "') JOIN {$wpdb->prefix}icl_languages l ON t.language_code=l.code AND l.active=1";
			}
		} elseif ( $post_type ) {
			if ( $sitepress->is_translated_post_type( $post_type ) ) {
				$join .= " {$post_type_join} JOIN {$wpdb->prefix}icl_translations t ON {$wpdb->posts}.ID = t.element_id
                     AND t.element_type = 'post_{$post_type}' JOIN {$wpdb->prefix}icl_languages l ON t.language_code=l.code AND l.active=1";
			} elseif ( $post_type == 'any' ) {
				$join .= " {$post_type_join} JOIN {$wpdb->prefix}icl_translations t ON {$wpdb->posts}.ID = t.element_id
                     AND t.element_type LIKE 'post\\_%' {$post_type_join} JOIN {$wpdb->prefix}icl_languages l ON t.language_code=l.code AND l.active=1";
			}
		} else {

			if ( is_tax() && is_main_query() ) {
				$tax            = get_query_var( 'taxonomy' );
				$taxonomy_types = $wp_taxonomies[ $tax ]->object_type;

				foreach ( $taxonomy_types as $k => $v ) {
					if ( !$sitepress->is_translated_post_type( $v ) )
						unset( $taxonomy_types[ $k ] );
				}
			} else {
				$taxonomy_types = array_keys( $sitepress->get_translatable_documents( false ) );
			}

			if ( !empty( $taxonomy_types ) ) {
				foreach ( $taxonomy_types as $k => $v )
					$taxonomy_types[ $k ] = 'post_' . $v;
				$post_types_list = "'" . join( "','", $taxonomy_types ) . "'";
				$join .= " {$post_type_join} JOIN {$wpdb->prefix}icl_translations t ON {$wpdb->posts}.ID = t.element_id
                     AND t.element_type IN ({$post_types_list}) JOIN {$wpdb->prefix}icl_languages l ON t.language_code=l.code AND l.active=1";
			}
		}


		return $join;
	}

	function posts_where_filter( $where, $query )
	{
		global $wp_taxonomies, $sitepress;
		//exceptions

		//$post_type = get_query_var('post_type');

		// determine post type
		$db = debug_backtrace();
		foreach ( $db as $o ) {
			if ( $o[ 'function' ] == 'apply_filters_ref_array' && $o[ 'args' ][ 0 ] == 'posts_where' ) {
				$post_type = $o[ 'args' ][ 1 ][ 1 ]->query_vars[ 'post_type' ];
				break;
			}
		}

		// case of taxonomy archive
		if ( empty( $post_type ) && is_tax() ) {
			$tax       = get_query_var( 'taxonomy' );
			$post_type = $wp_taxonomies[ $tax ]->object_type;
			foreach ( $post_type as $k => $v ) {
				if ( !$sitepress->is_translated_post_type( $v ) )
					unset( $post_type[ $k ] );
			}
			if ( empty( $post_type ) )
				return $where; // don't filter
		}

		if ( !$post_type )
			$post_type = 'post';

		if ( is_array( $post_type ) && !empty( $post_type ) ) {
			$none_translated = true;
			foreach ( $post_type as $ptype ) {
				if ( $sitepress->is_translated_post_type( $ptype ) ) {
					$none_translated = false;
				}
			}
			if ( $none_translated )
				return $where;
		} else {
			if ( !$sitepress->is_translated_post_type( $post_type ) && 'any' != $post_type ) {
				return $where;
			}
		}

		if ( 'all' != $sitepress->get_current_language() ) {
			if ( 'any' == $post_type ) {
				$condition = " AND (t.language_code='" . esc_sql( $sitepress->get_current_language() ) . "' OR t.language_code IS NULL )";
			} else {
				$condition = " AND t.language_code='" . esc_sql( $sitepress->get_current_language() ) . "'";
			}
		} else {
			$condition = '';
		}

		$where .= $condition;

		return $where;
	}


}
