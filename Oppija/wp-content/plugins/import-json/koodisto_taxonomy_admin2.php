<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div> 
      <h2>Koodisto update</h2>
         
      
</div>

<?php
function update_custom_taxonomy() {
        
        $updateTime = date("F j, Y, g:i a");
        
        // Choose correct json feed: 
        $feeds = array(
            'oph-koulutus' => 'http://localhost/demoWP/test-feed.json',
            'oph-ammattiluokitus' => 'https://virkailija.opintopolku.fi/koodisto-service/rest/json/ammattiluokitus/koodi',
            'oph-koulutusaste' => 'https://virkailija.opintopolku.fi/koodisto-service/rest/json/koulutusasteoph2002/koodi',
        );
     
        foreach($feeds as $selectedTaxonomy => $json){
        
        $data = json_decode($json, true);
        
            // Get all id numbers from json feed
            for($l=0; $l<2;++$l) {
                $koulutusNro = $data[$l]['koodiArvo']; 
                
                // Fetch 'kieli' which have FI or SV values
                foreach ($data[$l]['metadata'] as $metatieto){
                    if($metatieto['kieli'] == 'FI' or $metatieto['kieli'] == 'SV') {
                       
                        // Fetch 'nimi' and 'kieli' node
                        $lang = $metatieto['kieli'];
                        $koulutusNimi = $metatieto['nimi'];
                        $newTerm = $koulutusNro."_".$koulutusNimi;
                        $newSlug = $koulutusNro.'_'.$lang;             
                        
                        if ((preg_match("/00$/", $koulutusNro) && $selectedTaxonomy == 'oph-koulutus') // only those nodes which ends with 00
                                || ($selectedTaxonomy == 'oph-ammattiluokitus')
                                || ($selectedTaxonomy == 'oph-koulutusaste')) {

                            // Insert terms in taxonomies
                            wp_insert_term(
                                    $newTerm, 
                                    $selectedTaxonomy,
                                    array(
                                        'slug' => $newSlug,
                                        'description' => $updateTime,
                                    ),
                                    
                                    add_action( 'created_term', 'wpse_added_term', 10, 3 )
                            );                           
                        }
                    }                 
                }
            }
        }
}

add_action('update_custom_taxonomies', 'update_custom_taxonomy');

/*
 * Get created terms
 */

function wpse_added_term($term_id, $tt_id, $tax) {
    
    $addedTerm = get_term_by('id', $term_id, $tax, ARRAY_N);
        echo $addedTerm[1].'<br />';
 
} 
?>  