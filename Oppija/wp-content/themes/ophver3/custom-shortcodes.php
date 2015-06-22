<?php


function os_raises() {
    
    global $post; 
    $post_id = $post->ID; 
    
    $b = have_rows('os-raise');
    
    
    $output = '';
    
    if( have_rows('os-raise') ) : 

    $counter = 0;
    $field_object = get_field_object('cm_content');
    $total_rows = count($field_object['value']);   

    $output .= '<div class="os-wrapper">';
    
        while ( have_rows('os-raise') ) : the_row();

            $counter++;
    
            $title = get_sub_field('os-raise-title');
            $content = get_sub_field('os-raise-content');
            $img = get_sub_field('os-raise-img');
            $button_txt = get_sub_field('os-raise-button');
            $button_link = get_sub_field('os-raise-button-link');
    
        
            $output .= '
                    <div class="col-sm-13 col-md-13 col-lg-13">';

            if($img) {

                $output .= '<div class="os-box-img">
                                <img src="' . $img['url'] . '" />
                            </div>';
            }        
                        
                        
            $output .= '<h3>' . $title . '</h3>
                    
                        <div class="os-box-content">'
                            . $content;

            if($button_txt) {
               $output .= '<a class="btn btn-secondary" href="' . $button_link . '">' . $button_txt . '</a> ';
            }
            
             
            $output .= '</div>';


            if($counter == 1) {
                $output .= '<hr class="os" />';
            }

            $output .= '</div>';

        endwhile;
    
    $output .= '</div>';
    
    endif; 
    
       

    return $output;
}

function show_school_add() {

    $oppilaitostyyppi42 = file_get_contents('https://virkailija.opintopolku.fi/organisaatio-service/rest/organisaatio/hae?oppilaitostyyppi=oppilaitostyyppi_42%231');
    $oppilaitostyyppi41 = file_get_contents('https://virkailija.opintopolku.fi/organisaatio-service/rest/organisaatio/hae?oppilaitostyyppi=oppilaitostyyppi_41%231');
    
    $object_oppilaitos42 = json_decode($oppilaitostyyppi42);
    $object_oppilaitos41 = json_decode($oppilaitostyyppi41);
    
    $oids42 = $object_oppilaitos42->organisaatiot;
    $oids41 = $object_oppilaitos41->organisaatiot;

    $oids = array();

    // Yliopistot
    for ($i=0; $i < count($oids42); $i++) { 
        $oids[] = $oids42[$i]->oid;  
    }

    // Ammattikorkeakoulut
    for ($j=0; $j < count($oids41); $j++) { 
        $oids[] = $oids41[$j]->oid;
    }

    foreach ($oids as $itemoid) {

        echo 'oid: ' . $itemoid . '<br />';
        $getinfo = file_get_contents('https://virkailija.opintopolku.fi/organisaatio-service/rest/organisaatio/' . $itemoid);
        $info = json_decode($getinfo);

        $contact = $info->kayntiosoite;
        $oid_title = $info->nimi->fi;
        
        $add = $contact->osoite;
        $postnro = $contact->postinumeroUri;
        $postoffice = $contact->postitoimipaikka;

        echo $oid_title . '<br />';
        echo $add . '<br />';
        echo $postnro . '<br />';
        echo $postoffice . '<br />';
        echo '-------<br />';

    } 


}