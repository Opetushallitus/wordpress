<div class="sub-page">
    <aside>
        <section>
<?php        

/* PART 1: Get the page ID and store in $postID variable */ 

$postID = $post->ID;

/* PART 2: For that page, get the 'sidebar-post-id' custom field value */

$sidebarID = get_post_meta($postID, 'sidebar-entry-id', true);

/* PART 3: If a value is returned set up a WP query to retrieve content of Custom sidebar post */

if ($sidebarID) {

    $sidebarEntryArgs = array (

   	 'post_type' => 'sidebar-content',
   	 'post__in' => array ( $sidebarID )

    );

    $sidebarQuery = new WP_Query($sidebarEntryArgs);

    if ( $sidebarQuery->have_posts() ) :

   	 while ( $sidebarQuery->have_posts() ) : $sidebarQuery->the_post();

   		 $sidebarContent = get_the_content();  /* get the content of the post - what ever is in WYSIWYG editor box and store in variable for output below */

                 $content = apply_filters('the_content', $sidebarContent);
                 $content = str_replace(']]>', ']]&gt;', $content);
                 
   	 endwhile;

    endif;

    wp_reset_query();

}

/* Part 4: Output the sidebar code in a container with classes for CSS */

if ($sidebarContent) {

?>

   <?php echo $content; ?>
   

<?php } ?>
</section>
</aside>
</div>