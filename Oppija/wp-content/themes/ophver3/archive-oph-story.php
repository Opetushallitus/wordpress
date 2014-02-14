<?php get_header(); ?>

    <!-- breadcrumb -->
    <nav class="breadcrumb">
        <?php if(function_exists('bcn_display'))
        {
            bcn_display();
        }?>
    </nav>
    <!-- /breaddcrumb -->


    <nav class="sidenav">
        <ul>
            <li>
                <ul class="stories-sidenav">
                    
                    <?php
            
                function get_custom_terms($taxonomies){
                $args = array('orderby'=>'asc','hide_empty'=>false);
                $custom_terms = get_terms(array($taxonomies), $args);
                foreach($custom_terms as $term){
                    echo '<li class="page_item"><a href="'. get_term_link($term) .'" class="expanded"><span>'. $term->name.'</span></a></li>';
                }
                }
                
                get_custom_terms('story-theme'); 
                
                 ?>
                    
                    
                </ul>
            </li>
        </ul>
    </nav>
	
    <h1><?php _e( 'Tutustu tarinoihin', 'html5blank' ); ?></h1>
    
    <div class="stories">
	<!-- section -->
	
		<?php get_template_part('loop-oph-story'); ?>
		
		<?php //get_template_part('pagination'); ?>

	<!-- /section -->
    </div>
	

<?php get_footer(); ?>