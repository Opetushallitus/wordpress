<?php
/*
Controller name: Navigation
Controller description: Top navigation to JSON array
*/

class JSON_API_Nav_Controller {

      public function json_nav()
      {
        $container = array();

        // Fetch pages that are excluded from the top navigation
        $page_excludes = get_pages( array(
                            'meta_key' => '_top_nav_excluded',
                            'hierarchical' => 0,
                            'post_type' => 'page',
                            'post_status' => 'publish'
                         ));

        $page_ids = wp_list_pluck( $page_excludes, 'ID' );
        $page_ids = implode(', ', $page_ids);

        // Fetch all custom navigation titles
        $page_custom_titles = get_pages( array(
                            'meta_key' => '_oph_nav_title',
                            'hierarchical' => 0,
                            'post_type' => 'page',
                            'post_status' => 'publish'
                         ));

        $page_custom_titles = wp_list_pluck( $page_custom_titles, 'ID' );

        // Fetch all top level pages and exclude pages with _top_nav_excluded = 1
        $pages = get_pages( array(
                    'parent' => 0,
                    'post_status' => 'publish',
                    'sort_column'  => 'menu_order, post_title',
                    'exclude' => $page_ids
                 ) );

        foreach ($pages as $page)
        {
            $subnav = get_pages( array(
                'parent' => $page->ID,
                'child_of' => $page->ID,
                'post_status' => 'publish',
                'sort_column'  => 'menu_order, post_title',
                'exclude' => $page_ids
             ) );

            $subarray = array();

            foreach ($subnav as $subpage)
            {
                // Check if sub page has custom title
                if (in_array($subpage->ID, $page_custom_titles))
                {
                    $custom_title = get_post_meta($subpage->ID, '_oph_nav_title', true);

                    if (!empty($custom_title))
                    {
                        $subpage->post_title = $custom_title;
                    }
                }

                $subarray[] = array('id' => $subpage->ID, 'title' => $subpage->post_title, 'link' => get_page_link($subpage->ID));
            }

            // Check parent page if has custom title
            if (in_array($page->ID, $page_custom_titles))
            {
                $custom_title = get_post_meta($page->ID, '_oph_nav_title', true);

                if (!empty($custom_title))
                {
                    $page->post_title = $custom_title;
                }
            }

            if (empty($subarray))
            {
                $container[] = array('id' => $page->ID, 'title' => $page->post_title, 'link' => get_page_link($page->ID));
            }
            else
            {
                $container[] = array('id' => $page->ID, 'title' => $page->post_title, 'link' => get_page_link($page->ID), 'subnav' => $subarray);
            }
        }

        return array('nav' => $container);

      }

}

?>