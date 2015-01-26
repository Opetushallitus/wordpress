<?php 
/*
Template Name: Tutustu tarinoihin
*/
?>

<?php get_header(); ?>

    <!-- breadcrumb -->
    <nav class="breadcrumb">
        <?php if(function_exists('theme_bcn'))
        {
        	theme_bcn();
        }?>
    </nav>
    
    <?php
    
    function get_theme_name() {
    $terms = get_the_terms( $post->ID , 'story-theme' );
        if ( $terms != null ){
            foreach( $terms as $term ) {
                print $term->name ;
                unset($term);
            } 
        }
    }?>
    
    <div class="row padding-bottom-10">
    <!-- /breaddcrumb -->
    <div class="col-sm-16 col-sm-16 col-sm-16">
	<div class="col-sm-16 col-md-4 col-lg-4">   
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
    </div>
	
    <h1><?php _e( 'Tutustu tarinoihin', 'html5blank' ); ?></h1>
    
    <div class="col-sm-16 col-md-12 col-lg-12">  
        <div class="stories">
        <!-- section -->

            <?php get_template_part('loop-oph-story'); ?>

            <?php //get_template_part('pagination'); ?>

        <!-- /section -->
        </div>
    </div>
    </div>        
	

<?php get_footer(); ?>