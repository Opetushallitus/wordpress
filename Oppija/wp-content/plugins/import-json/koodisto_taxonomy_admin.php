<div class="wrap">
    <div id="icon-options-general" class="icon32"><br></div> 
      <h2>Koodisto update</h2>
         
      <form name="koodisto_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
        <input type="hidden" name="koodisto_hidden" value="Y">      
        <h3>Choose Koodisto to update</h3>
        <select name="koodisto_taxonomy" class="koodisto_taxonomy">
            <?php 
            
            // Get all the custom taxonomies created 
            
              $args = array(
              'public'   => true,
              '_builtin' => false
              ); 
                $output = 'names';
                $operator = 'and';
                $taxonomies = get_taxonomies( $args, $output, $operator ); 
                if ( $taxonomies ) {
                  foreach ( $taxonomies  as $taxonomy ) {
                    echo '<option value='.$taxonomy.'>' . $taxonomy . '</option>';
                  }

                }
            ?>
        </select> 
        <div class="submit">  
           <?php submit_button('Update!'); ?>
        </div>

    </form>
      
</div>

<?php
    // Update taxonomy when button is pressed OR cron job
if($_POST['koodisto_hidden'] == 'Y') { 
    
    update_oph_koodisto();
}


    
/*
 * Get created terms
 */

function wpse_added_term($term_id, $tt_id, $tax) {
    
    $addedTerm = get_term_by('id', $term_id, $tax, ARRAY_N);
    echo $addedTerm[1].'<br />';
 
}
