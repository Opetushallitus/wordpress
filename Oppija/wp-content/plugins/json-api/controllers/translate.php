<?php
/*
Controller name: Translate pages
Controller description: Get translated pages via WPML
*/

class JSON_API_Translate_Controller {

  public function translate_page() {
      
    global $json_api;
    
    $path = $json_api->query->path;
	
    if($path == '/' || $path == '') :

        $page = get_post(get_option('page_on_front'));
		$page_url = get_permalink($page->ID);
		$is_home = true;
		
    else :
        
        $page = get_page_by_path($path);
		$is_home = false;
        
    endif;

    if($page == null) :
        
        header("HTTP/1.0 404 Not Found");
        die();
    
    else :
    
        $page_id = $page->ID;
        $page_title = $page->post_title;
		
        $language_info = wpml_get_language_information($page_id);

		$page_language = $language_info['locale'];

        if($page_language == 'sv_SE') :
            $lang = 'fi';
        endif;

        if($page_language == 'fi') :
            $lang = 'sv';
        endif;
      
        $translated_id = icl_object_id($page_id, 'page', true, $lang);
        $translated_page_data = get_page($translated_id);
        $translated_title = $translated_page_data->post_title;
    
      
		if($is_home == true) : 
			
			$translated_permalink = $page_url;
      
        elseif ($page_title == $translated_title) :
            
            $home = icl_get_home_url();    
            $translated_permalink = $home;
		
		else :
		
			$translated_permalink = get_permalink($translated_id);
				
		endif;
		
		
		
        return array(
            "translation" => array(
                        "title" => $translated_title,
                        "url" => $translated_permalink,
                        "id" => $translated_id
                )
        );
    
    endif;
    
  }

}