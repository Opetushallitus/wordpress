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

function show_school_add($type) {

    if(ICL_LANGUAGE_CODE == 'fi') {
        $lang = 'kieli_fi#1';
        $langShort = 'fi';
    } else {
         $lang = 'kieli_sv#1';
         $langShort = 'sv';
    }

    // [oph-uniapp-addresses edu="university|appliedscience"]
    $chooseType = shortcode_atts(array(
        'edu' => 'university'
        ), $type);

    if($chooseType['edu'] == 'university') {
        $oppilaitostyyppi = file_get_contents('https://virkailija.opintopolku.fi/organisaatio-service/rest/organisaatio/hae?oppilaitostyyppi=oppilaitostyyppi_42%231');
    } else {
        $oppilaitostyyppi = file_get_contents('https://virkailija.opintopolku.fi/organisaatio-service/rest/organisaatio/hae?oppilaitostyyppi=oppilaitostyyppi_41%231');
    }
       
    $jsonObject = json_decode($oppilaitostyyppi);
    
    $oids = $jsonObject->organisaatiot;

    $oidsArray = array();

    for ($j=0; $j < count($oids); $j++) { 
        $oidsArray[] = $oids[$j]->oid;
    }

    $output = '';
    $output .= '<div class="oph-school-listing">';

    foreach ($oidsArray as $itemoid) {

        $getinfo = file_get_contents('https://virkailija.opintopolku.fi/organisaatio-service/rest/organisaatio/' . $itemoid);
        $info = json_decode($getinfo);

        foreach ($info->metadata->yhteystiedot as $contactinfo) {

            if($contactinfo->tyyppi == 'puhelin' && $contactinfo->kieli == $lang) {
                $phone = $contactinfo->numero;  
            }

            if($contactinfo->www && $contactinfo->kieli == $lang) {
                $www = $contactinfo->www;
            }

            if($contactinfo->email && $contactinfo->kieli == $lang) {
                $email = $contactinfo->email;
            }
        }

        foreach ($info->yhteystiedot as $addresstype) {
            
            if($addresstype->osoiteTyyppi == 'kaynti' && $addresstype->kieli == $lang) {
                $visitAddress = $addresstype->osoite . ', ' . preg_replace('/(posti_)/', '', $addresstype->postinumeroUri) . ' ' . $addresstype->postitoimipaikka;
            }            

            if($addresstype->osoiteTyyppi == 'posti' && $addresstype->kieli == $lang) {
                $postAddress = $addresstype->osoite . ', ' . preg_replace('/(posti_)/', '', $addresstype->postinumeroUri) . ' ' . $addresstype->postitoimipaikka;
            }
        }


        if($info->nimi->$langShort) {
            $schoolName = $info->nimi->$langShort;
        } 
        if (!$info->nimi->$langShort && $langShort == 'sv') { 
            $schoolName = $info->nimi->fi;
        }

        $output .= '<h3>' . $schoolName . '</h3>';

        $output .= '<ul>';

            $output .= '<li>';
                if($visitAddress) {
                    $output .=  __('Visiting address', 'html5blank') . ': ' . $visitAddress;
                } 
            $output .= '</li>';

            $output .= '<li>';
                if($postAddress) {
                    $output .= __('Post address', 'html5blank') . ': ' . $postAddress;
                } 
            $output .= '</li>';

            $output .= '<li>';
                if($phone) {
                    $output .=  __('Phone', 'html5blank') . ': ' . $phone;
                }
            $output .= '</li>';

            $output .= '<li>';
                if($email) {
                    $output .= $email;
                }
            $output .= '</li>';

            $output .= '<li>';
                if($www) {
                    $output .= '<a href="' . $www . '">'. $www .'</a>';
                }
            $output .= '</li>';

        $output .= '</ul>';
        

    } 

    $output .= '</div>';

    return $output;
}