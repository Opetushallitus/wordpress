<?php
/*
Controller name: Footer menu links
Controller description: Get custom menu links
*/

class JSON_API_Menu_Controller {

  public function footer_links() {
    global $json_api;

    $menu_location = 'extra-menu';
    $menu_array = array();

    // Get the nav menu based on the theme_location
    if ( $menu_location && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_location ] ) ) {
        $menu = wp_get_nav_menu_object( $locations[ $menu_location ] );
    }

    // If the menu exists, get its items.
    if ( $menu && ! is_wp_error($menu) && !isset($menu_items) ) {
        $menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );
    }

    // Extract url and title values from objects
    $menu_urls = wp_list_pluck( $menu_items, 'url' );
    $menu_titles = wp_list_pluck( $menu_items, 'title' );

    // Create a combined array
    if (count($menu_urls) == count($menu_titles)) {
        foreach($menu_urls as $index => $url) {
            $menu_array[] = array( 'title' => $menu_titles[$index], 'url' => $url );
        }
    } else {
        $json_api->error("No menu items found!");
    }

    return array('nav' => $menu_array);
  }
}