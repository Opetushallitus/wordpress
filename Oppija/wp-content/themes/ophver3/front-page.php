<?php get_header( get_bloginfo('language') ); ?>

        <div class="padding-bottom-10">

        <?php
        
        $args = array(
	    'post_type' => array('page', 'oph-feature'),
        'orderby' => 'type date',
        'order' => 'DESC',
	    'tax_query' => array(
                array(
                    'taxonomy' => 'oph-additional-tags',
                    'field' => 'slug',
                    'terms' => array('intro1', 'intro1-sv'),
                ),
            ),
        );
        
        $args2 = array(
	    'post_type' => array('page', 'oph-feature'),
        'orderby' => 'type date',
        'order' => 'DESC',
	    'tax_query' => array(
                array(
                    'taxonomy' => 'oph-additional-tags',
                    'field' => 'slug',
                    'terms' => array('intro2', 'intro2-sv'),
                ),
            ),
        );

        $args3 = array(
        'post_type' => array('page', 'oph-feature'),
        'orderby' => 'type date',
        'order' => 'DESC',
        'tax_query' => array(
                array(
                    'taxonomy' => 'oph-additional-tags',
                    'field' => 'slug',
                    'terms' => array('intro3', 'intro3-sv'),
                ),
            ),
        );

        $args4 = array(
        'post_type' => array('page', 'oph-feature'),
        'orderby' => 'type date',
        'order' => 'DESC',
        'tax_query' => array(
                array(
                    'taxonomy' => 'oph-additional-tags',
                    'field' => 'slug',
                    'terms' => array('intro4', 'intro4-sv'),
                ),
            ),
        );

        
        
        $intro_pages = new WP_Query($args);
        $intro_pages2 = new WP_Query($args2);
        $intro_pages3 = new WP_Query($args3);
        $intro_pages4 = new WP_Query($args4);?>

            <div class="col-sm-16 col-md-4 col-lg-4 frontpage-link">
            <?php if ( $intro_pages->have_posts() ) {
                while ( $intro_pages->have_posts() ) {
                    $intro_pages->the_post(); ?>
                    
                    <div class="col-xs-16">                       
                        <h2 class=""><?php echo get_the_title(); ?></h2>
                        <?php if(has_post_thumbnail()) :
                            the_post_thumbnail('oph-intro');
                        endif; ?>
                        <p><?php the_content();?></p>   
                        <?php //var_dump($content); ?> 
                    </div>
                
                <?php }
            } else {
                // no posts found
            }
                
            wp_reset_postdata();?>
            </div>
            
            <div class="col-sm-16 col-md-4 col-lg-4 frontpage-link">
            <?php if ( $intro_pages2->have_posts() ) {
                while ( $intro_pages2->have_posts() ) {
                    $intro_pages2->the_post(); ?>
                    
                    <div class="col-xs-16">                       
                        <h2 class=""><?php echo get_the_title(); ?></h2>
                        <?php if(has_post_thumbnail()) :
                            the_post_thumbnail('oph-intro');
                        endif; ?>
                        <p><?php the_content();?></p>      
                        <?php //var_dump($content); ?> 
                    </div>
                
                <?php }
            } else {
                // no posts found
            }
                
            wp_reset_postdata();?>
            </div>
            
            <div class="col-sm-16 col-md-4 col-lg-4 frontpage-link">
            <?php if ( $intro_pages3->have_posts() ) {
                while ( $intro_pages3->have_posts() ) {
                    $intro_pages3->the_post(); ?>
                    
                    <div class="col-xs-16">                       
                        <h2 class=""><?php echo get_the_title(); ?></h2>
                        <?php if(has_post_thumbnail()) :
                            the_post_thumbnail('oph-intro');
                        endif; ?>
                        <p><?php the_content();?></p>      
                        <?php //var_dump($content); ?> 
                    </div>
                
                <?php }
            } else {
                // no posts found
            } 
                
            wp_reset_postdata();?>
            </div>
            
            <div class="col-sm-16 col-md-4 col-lg-4 frontpage-link">
            
                <div class="col-xs-16">        
                <h2 class=""><?php _e( 'Upcoming', 'html5blank' ); ?></h2>

                    <div id="as-calendar" style="width: 100%;"></div>
                    <script src="https://testi.opintopolku.fi/calendar/calendar.js"></script><script>// <![CDATA[
                    (function() {
                        $("html").on("oppija-raamit-loaded", function() {
                            var prefix = CookiePrefixResolver.getPrefix(window.location.host),
                                key = prefix + 'i18next';
                            ApplicationSystemCalendar.calendar({
                                selector: '#as-calendar',
                                lang: jQuery.cookie(key),
                                deps: {
                                    stylesheet: '/calendar/css/calendar.css',
                                    underscore: '/calendar/lib/underscore-min.js'
                                },
                                calendarResource: '/as/fetchForCalendar'
                            });
                        });
                    }());
                    // ]]>
                    </script>

               <h2 class=""><?php _e( 'News', 'html5blank' ); ?></h2>

                <?php if ( $intro_pages4->have_posts() ) {
                    while ( $intro_pages4->have_posts() ) {
                        $intro_pages4->the_post(); ?>

                        <div class="col-xs-16 news">                                     
                            <h2 class=""><?php echo get_the_title(); ?></h2>
                            <?php if(has_post_thumbnail()) :
                                the_post_thumbnail('oph-intro');
                            endif; ?>
                            <p><?php the_content();?></p>  
                            <?php //var_dump($content); ?> 
                        </div>

                    <?php }
                } else {
                    // no posts found
                } 

                wp_reset_postdata();?>
                </div>
            </div>
            
        </div>

<?php //get_sidebar('widget-area-2'); ?>

<?php get_footer ( get_bloginfo('language') ); ?>