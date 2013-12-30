<?php
/*
Plugin Name: OPH Taxonomy importer
Description: Import Taxonomies From CSV
Author: Mikko Alander
Author URI: http://playamobile.com/
Version: 0.2
Stable tag: 0.2
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

if ( !defined('WP_LOAD_IMPORTERS') )
	return;

// Load Importer API
require_once ABSPATH . 'wp-admin/includes/import.php';

if ( !class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) )
		require_once $class_wp_importer;
}

/**
 * RSS Importer
 *
 * @package WordPress
 * @subpackage Importer
 */

/**
 * RSS Importer
 *
 * Will process a RSS feed for importing posts into WordPress. This is a very
 * limited importer and should only be used as the last resort, when no other
 * importer is available.
 *
 * @since unknown
 */
if ( class_exists( 'WP_Importer' ) ) {
class Oph_Taxonomy_Import extends WP_Importer {

	var $posts = array ();
	var $file;

	var $import_taxonomy;

	function header() {
		echo '<div class="wrap">';
		screen_icon();
		echo '<h2>'.__('Import Taxonomy', 'oph-importer').'</h2>';
	}

	function footer() {
		echo '</div>';
	}

	function select_taxonomy()
	{
		?>
			<div class="narrow">
				<p><?php echo  __('Select Taxonomy') ?></p>
				<form action="admin.php?import=oph-taxonomy&amp;step=1">
					<select name="import_taxonomy">
					<?php $taxonomies = get_taxonomies();
					foreach ($taxonomies as $t) : ?>
						<option value="<?php echo $t ?>"><?php echo $t ?></option>
					<?php endforeach ?>
					</select>
					
					<input type="hidden" name="import" value="oph-taxonomy" />
					<input type="hidden" name="step" value="1" />
					<input type="submit" value="<?php _e('Next') ?>" />
				</form>
			</div>
		<?php
	}

	function upload_form() {
		echo '<div class="narrow">';
		echo '<p>'.__('Hello! This importer is for importing Taxonomies from CSV -file', 'oph-importer').'</p>';
		wp_import_upload_form("admin.php?import=oph-taxonomy&amp;step=2&import_taxonomy=" . $this->import_taxonomy);
		echo '</div>';
	}

	function _normalize_tag( $matches ) {
		return '<' . strtolower( $matches[1] );
	}

	function get_from_csv() {
		global $wpdb;

		set_magic_quotes_runtime(0);
		mb_internal_encoding('UTF-8');

		if (( $fp = fopen($this->file, "r")) !== FALSE ) {
			while (( $cols = fgetcsv($fp, 1000, ",")) !== FALSE) {
					
				$term = trim($cols[1]);
				$id = trim($cols[0]);
				
				$term_name = $id. "_" . $term;
				//import only parents if koulutus
				
				if ( $this->import_taxonomy == "oph-koulutus") {
					if ((int)$id % 100 == 0 ) {
						wp_insert_term( $term_name, $this->import_taxonomy , array('slug' => $term_name ) );
					}
				} else {
					wp_insert_term( $term_name, $this->import_taxonomy , array('slug' => $term_name ) );
				}
			}			
		} else echo "Unable to open file: " . $this->file;
	}

	function import() {
		$file = wp_import_handle_upload();
		if ( isset($file['error']) ) {
			echo $file['error'];
			return;
		}

		$this->file = $file['file'];
		$result = $this->get_from_csv();
				
		if ( is_wp_error( $result ) )
			return $result;
		wp_import_cleanup($file['id']);
		do_action('import_done', 'rss');

		echo '<h3>';
		printf(__('All done. <a href="%s">Have fun!</a>', 'oph-importer'), get_option('home'));
		echo '</h3>';
	}

	function dispatch() {
		if (empty ($_GET['step']))
			$step = 0;
		else
			$step = (int) $_GET['step'];

		$this->header();

		switch ($step) {
			case 0:
				$this->select_taxonomy();
				break;
			case 1 :
				$this->import_taxonomy = $_REQUEST['import_taxonomy'];
				$this->upload_form();
				break;
			case 2 :
				$this->import_taxonomy = $_REQUEST['import_taxonomy'];						
				check_admin_referer('import-upload');
				$result = $this->import();
				if ( is_wp_error( $result ) )
					echo $result->get_error_message();
				break;
		}

		$this->footer();
	}

	function Oph_taxonomy_Import() {
		// Nothing.
	}
}

$rss_import = new Oph_taxonomy_Import();
register_importer('oph-taxonomy', __('OPH Taxonomies', 'oph-importer'), __('Import taxonomies from CSV.', 'oph-importer'), array ($rss_import, 'dispatch'));

} // class_exists( 'WP_Importer' )

function oph_taxonomy_importer_init() {
    load_plugin_textdomain( 'oph-importer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'oph_taxonomy_importer_init' );


?>