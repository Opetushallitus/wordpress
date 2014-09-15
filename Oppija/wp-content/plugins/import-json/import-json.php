<?php
/* Plugin Name: OPH JSON importer
Plugin URI:
Description: Import JSON to custom taxonomies.
Version: 1.0
Author: Anniina Salmi
Author URI: 
License: GPLv2 or later




/*
 * Change 'Description' title to 'Last update'
 */


function theme_columns($theme_columns) {
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name' => __('Name'),
        //'thumbnail' => __('Thumbnail'),
        'description' => __('Last update'),
        'version' => __('Version'),
        'slug' => __('Slug'),
        'posts' => __('Posts')
        );
    return $new_columns;
}

add_filter("manage_edit-oph-koulutustyyppi_columns", 'theme_columns'); 
add_filter("manage_edit-oph-koulutus_columns", 'theme_columns'); 


add_filter("manage_oph-koulutustyyppi_custom_column", 'manage_custom_columns_koulutustyyppi', 10, 3);
add_filter("manage_oph-koulutus_custom_column", 'manage_custom_columns_koulutus', 10, 3);
 
function manage_custom_columns_koulutustyyppi($content, $column_name, $theme_id) {    
    global $post;
    
    switch ($column_name) {
        case 'version': 
            $content = get_option('oph-koulutustyyppi_'.$theme_id.'_koodisto_version' );
            break;
        default:
            break;
    }
    return $content;    
} 

function manage_custom_columns_koulutus($content, $column_name, $theme_id) {    
    global $post;
    
    switch ($column_name) {
        case 'version': 
            $content = get_option('oph-koulutus_'.$theme_id.'_koodisto_version' );
            break;
        default:
            break;
    }
    return $content;    
} 


/*
 * Add update manager to the admin panel: Settings -> Koodisto update
 */

function koodisto_taxonomy_menu() {
    add_options_page( 'My Plugin Options', 'Koodisto update', 'manage_options', 'koodisto-taxonomy-update', 'koodisto_taxonomy_admin' );
}

function koodisto_taxonomy_admin(){
    if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
        include('koodisto_taxonomy_admin.php');
}

add_action( 'admin_menu', 'koodisto_taxonomy_menu' );


function update_oph_koodisto($selectedTaxonomy) {
    
        if($_POST['koodisto_hidden'] == 'Y') {
            error_log('Submit button update');
            $selectedTaxonomy = $_POST['koodisto_taxonomy'];
        } else {
            error_log('Cron update '.$selectedTaxonomy);
            //$selectedTaxonomy = 'oph-koulutustyyppi';
        }
        
        error_log('Taxonomy selected');

        $updateTime = date("F j, Y, g:i a");
        
        // Choose json feed:
        if($selectedTaxonomy == 'oph-koulutus') {
            $json = file_get_contents('https://virkailija.opintopolku.fi/koodisto-service/rest/json/koulutus/koodi');
        }
        
        if($selectedTaxonomy == 'oph-koulutustyyppi') {
            $json = file_get_contents('https://virkailija.opintopolku.fi/koodisto-service/rest/json/koulutustyyppifasetti/koodi');            
        }
        
        $data = json_decode($json, true);
        
        $searchAllTerms = get_terms($selectedTaxonomy, array('hide_empty' => false));
                        
        foreach ($searchAllTerms as $term) {
            $termInTaxonomy = $term->name;
          }
        
            // Get all id numbers from json feed
            for($l=0; $l<count($data);++$l) {
                $koulutusNro = $data[$l]['koodiUri'];
                
                // Fetch 'kieli' which have FI or SV values
                foreach ($data[$l]['metadata'] as $metatieto){
                        if($metatieto['kieli'] == 'FI' /*or $metatieto['kieli'] == 'SV' */) {
   
                        $currentVersion =  $data[$l]['koodisto']['koodistoVersios'][0];
                        
                        // Fetch 'nimi' and 'kieli' node
                        $lang = $metatieto['kieli'];
                        $koulutusNimi = $metatieto['nimi'];
                        $newTerm = $koulutusNimi;
                        $newSlug = $koulutusNro.'_'.$currentVersion;

                        if ($selectedTaxonomy) {

                            
                            // Insert terms in taxonomies
                            $fi_term = wp_insert_term(
                                    $newTerm, 
                                    $selectedTaxonomy,
                                    array(
                                        'slug' => $newSlug/*.'_fi'*/,
                                        'description' => $updateTime,
                                    )
                            );
                                            
                            if(is_wp_error($fi_term)) {
                                $error_string = $fi_term->get_error_message();
                                echo '<div id="message" class="error"><p>' . $error_string . '</p> </div>';
                            } /* if(is_wp_error($sv_term)) {
                                $error_string = $sv_term->get_error_message();
                                echo '<div id="message" class="error"><p>' . $error_string . '</p> </div>';
                            }  */ else {
                                                               
                                extract($fi_term);
                                
                                $tax_version = $selectedTaxonomy.'_'.$term_id.'_koodisto_version';
                            
                                add_option($tax_version, $currentVersion, '', 'no' );
                                add_option('_'.$tax_version, 'field_5339407599189', '', 'no' );
                                
                                $added_term = get_term_by('id', $term_id, $selectedTaxonomy);
                                
                                echo 'Lisätty termi: '.$added_term->name.'<br />';
                            }
                        }
                    }                 
                }
            }
        ?>  
        <div class="updated"><p><strong>Updated</strong></p></div>  
        <?php             
    } 








/*
 * Schedule Cron 
 */

register_activation_hook( __FILE__, 'prefix_activation' );
/**
 * On activation, set a time, frequency and name of an action hook to be scheduled.
 */
function prefix_activation() {
	wp_schedule_event( time(), 'every5min', 'prefix_hourly_event_hook' );
}

add_action( 'prefix_hourly_event_hook', 'prefix_do_this_hourly' );
/**
 * On the scheduled action hook, run the function.
 */
function prefix_do_this_hourly() {
	update_oph_koodisto('oph-koulutustyyppi');
        update_oph_koodisto('oph-koulutus');
}

register_deactivation_hook( __FILE__, 'prefix_deactivation' );
/**
 * On deactivation, remove all functions from the scheduled action hook.
 */
function prefix_deactivation() {
	wp_clear_scheduled_hook( 'prefix_hourly_event_hook' );
}


add_filter( 'cron_schedules', 'myprefix_add_weekly_cron_schedule' );
function myprefix_add_weekly_cron_schedule( $schedules ) {
    $schedules['every5min'] = array(
        'interval' => 300, 
        'display'  => __( 'Every 5 minutes' ),
    );
 
    return $schedules;
}