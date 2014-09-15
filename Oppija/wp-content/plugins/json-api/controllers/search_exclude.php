  
<?php
/*
Controller name: Exclude pages
Controller description: Exclude frontpage pages from search
*/

class JSON_API_Search_Exclude_Controller {

public function search_pages() {
    
   $search_posts = new WP_Query('s=kisu&post_type=page');
    print_r($search_posts);

  }
}