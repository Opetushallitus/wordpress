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

    $http_opts = [
      "http" => [
          "method" => "GET",
          "header" => "Caller-id: wordpress-testi\r\n"
      ]
    ];
    $context = stream_context_create($http_opts);

    if(ICL_LANGUAGE_CODE == 'fi') {
        $lang = 'kieli_fi#1';
        $langShort = 'fi';
    }

    if(ICL_LANGUAGE_CODE == 'sv') {
        $lang = 'kieli_sv#1';
        $langShort = 'sv';
    }

    if (!function_exists('icl_object_id') ) {
        $lang = 'kieli_en#1';
        $langShort = 'en';
    }

    // [oph-uniapp-addresses edu="university|appliedscience"]
    $chooseType = shortcode_atts(array(
        'edu' => 'university'
        ), $type);

    if($chooseType['edu'] == 'university') {
        $oppilaitostyyppi = file_get_contents('https://virkailija.opintopolku.fi/organisaatio-service/rest/organisaatio/hae?oppilaitostyyppi=oppilaitostyyppi_42%231', false, $context);
    } else {
        $oppilaitostyyppi = file_get_contents('https://virkailija.opintopolku.fi/organisaatio-service/rest/organisaatio/hae?oppilaitostyyppi=oppilaitostyyppi_41%231', false, $context);
    }

    $jsonObject = json_decode($oppilaitostyyppi);
    
    $oids = $jsonObject->organisaatiot;

    $oidsArray = array();

    for ($j=0; $j < count($oids); $j++) { 
        $eduTitle = $oids[$j]->children[0]->nimi->$langShort;

        $artsUniversity = $oids[$j]->children[0]->oid;

        if($artsUniversity == '1.2.246.562.10.97096148164') {
            $subEdus = $oids[$j]->children[0]->children;
            foreach ($subEdus as $subEdu) {
                $eduTitle = $subEdu->nimi->$langShort;
                $artsUniversityOid = $subEdu->oid;
                $oidsArray[$artsUniversityOid] = $eduTitle;
            }

        }

        unset($oidsArray['1.2.246.562.10.97096148164']);

        if($eduTitle) {
            $oidsArray[$oids[$j]->children[0]->oid] = $eduTitle;
        }

        if (!$eduTitle && $langShort == 'sv') { 
            $oidsArray[$oids[$j]->children[0]->oid] = $oids[$j]->children[0]->nimi->fi;
        }

        if (!$eduTitle && $langShort == 'fi') { 
            $oidsArray[$oids[$j]->children[0]->oid] = $oids[$j]->children[0]->nimi->sv;
        }

        if (!$eduTitle && $langShort == 'en') { 
            $oidsArray[$oids[$j]->children[0]->oid] = $oids[$j]->children[0]->nimi->sv;
        }

    }

    asort($oidsArray);

    $output = '';
    $output .= '<div class="oph-school-listing">';

    foreach ($oidsArray as $itemoid => $itemName) {

        $getinfo = file_get_contents('https://virkailija.opintopolku.fi/organisaatio-service/rest/organisaatio/' . $itemoid, false, $context);
        $info = json_decode($getinfo);

        $visitAddress = '';
        $postAddress = '';
        $email = '';
        $phone = '';
        $www = '';
        //$lang = '';

        $chooseInfo = count($info->metadata->yhteystiedot);

        if($chooseInfo <= 1) {
            $data = $info->yhteystiedot;
        } else {
            $data = $info->metadata->yhteystiedot;
        }         

        foreach ($data as $contactinfo) {

            /*
            if($lang == 'kieli_fi#1' && $itemoid != '1.2.246.562.10.64582714578') {
                $lang = 'kieli_sv#1';
            }

            if($itemoid == '1.2.246.562.10.58083501534' && $langShort == 'fi') {
                $lang = 'kieli_fi#1';
            } 

            
            if($contactinfo->kieli == NULL || !($contactinfo->kieli == $lang)) {
                $lang = 'kieli_fi#1';
            }*/

            if($contactinfo->www && $contactinfo->kieli == $lang) {
                $www = $contactinfo->www;
            } elseif ($contactinfo->www && $contactinfo->kieli == 'kieli_fi#1' && $www == '') {
                $www = $contactinfo->www;
            }

            if($contactinfo->email && $contactinfo->kieli == $lang) {
                $email = $contactinfo->email;
            } elseif($contactinfo->email && $contactinfo->kieli == 'kieli_fi#1' && $email == '') {
                $email = $contactinfo->email;
            }

            if($contactinfo->osoiteTyyppi == 'kaynti' && $contactinfo->kieli == $lang) {
                $visitAddress = $contactinfo->osoite . ', ' . preg_replace('/(posti_)/', '', $contactinfo->postinumeroUri) . ' ' . $contactinfo->postitoimipaikka;
            } elseif($contactinfo->osoiteTyyppi == 'kaynti' && $contactinfo->kieli == 'kieli_fi#1' && $visitAddress == '') {
                $visitAddress = $contactinfo->osoite . ', ' . preg_replace('/(posti_)/', '', $contactinfo->postinumeroUri) . ' ' . $contactinfo->postitoimipaikka;
            }

            if($contactinfo->osoiteTyyppi == 'posti' && $contactinfo->kieli == $lang) {
                $postAddress = $contactinfo->osoite . ', ' . preg_replace('/(posti_)/', '', $contactinfo->postinumeroUri) . ' ' . $contactinfo->postitoimipaikka;
            } elseif($contactinfo->osoiteTyyppi == 'posti'  && $contactinfo->kieli == 'kieli_fi#1' && $postAddress == '') {
                $postAddress = $contactinfo->osoite . ', ' . preg_replace('/(posti_)/', '', $contactinfo->postinumeroUri) . ' ' . $contactinfo->postitoimipaikka;
            }

            if($contactinfo->tyyppi == 'puhelin' && $contactinfo->kieli == $lang) {
                $phone = $contactinfo->numero;  
            } elseif($contactinfo->tyyppi == 'puhelin' && $contactinfo->kieli == 'kieli_fi#1' && $phone == '') {
                $phone = $contactinfo->numero;  
            }   
        }


        $output .= '<h3>' . $itemName . ' ' . $itemoid . '</h3>';

        $output .= '<ul>';
            
            if($visitAddress) {
                $output .= '<li>';
                $output .=  __('Visiting address', 'html5blank') . ': ' . $visitAddress;
                $output .= '</li>';
            } 

            if($postAddress) {
                $output .= '<li>';
                $output .= __('Post address', 'html5blank') . ': ' . $postAddress;
                $output .= '</li>';
            } 

            if($phone) {
                $output .= '<li>';
                $output .=  __('Phone', 'html5blank') . ': ' . $phone;
                $output .= '</li>';
            }

            if($email) {
                $output .= '<li>';
                $output .= $email;
                $output .= '</li>';
            }

            if($www) {
                $output .= '<li>';
                $output .= '<a href="' . $www . '">'. $www .'</a>';
                $output .= '</li>';
            }
            
        $output .= '</ul>';
        

    } 

    $output .= '</div>';

    return $output;
}