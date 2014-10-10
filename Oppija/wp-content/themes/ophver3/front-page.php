<?php get_header( get_bloginfo('language') ); ?>


        <?php
        
        $args = array(
	    'post_type' => 'page',
        'order_by' => 'name',
        'order' => 'ASC',
	    'tax_query' => array(
		array(
			'taxonomy' => 'oph-additional-tags',
			'field' => 'slug',
			'terms' => array('intro1', 'intro1-sv', 'intro2', 'intro2-sv', 'intro3', 'intro3-sv'),
		),
            ),
        );
        
        $intro_pages = new WP_Query($args);
        
        ?>

        <div class="row padding-bottom-10">

            <?php if ($intro_pages->have_posts()): while ($intro_pages->have_posts()) : $intro_pages->the_post(); ?>

                <div class="col-sm-16 col-md-4 col-lg-4 frontpage-link">
                    <div class="row col-xs-16">
                        <h2 class=""><?php the_title(); ?></h2>    
                         <?php if(has_post_thumbnail()) :
                            the_post_thumbnail('oph-intro');
                        endif; ?>
                        <p>
                            <?php the_content(); ?>
                        </p>
                    </div>
                </div>

            <?php endwhile; ?>

            <?php else: ?>

                <!-- article -->
                <article>

                    <h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>

                </article>
                <!-- /article -->
                
            <?php endif; ?>
            
            <?php wp_reset_postdata(); ?>
            
            <?php
            
            $args2 = array(
                'post_type' => 'oph-feature',
                'order' => 'DESC'
                );
            
            $features = new WP_Query($args2);
            ?>
            
            <div class="col-sm-16 col-md-4 col-lg-4 frontpage-link">
                <div class="row col-xs-16">
                    <h2 class=""><?php _e( 'Upcoming', 'html5blank' ); ?></h2>    
                    <div id="as-calendar" style="width: 100%;"></div>
                    <script src="https://itest-oppija.oph.ware.fi/calendar/calendar.js"></script><script>// <![CDATA[
                    (function() {
                        $("html").on("oppija-raamit-loaded", function() { 
                            var prefix = CookiePrefixResolver.getPrefix(window.location.host),
                                key = prefix + 'i18next';
                            ApplicationSystemCalendar.calendar({
                                selector: '#as-calendar',
                                lang: jQuery.cookie(key),
                                deps: {
                                    stylesheet: 'https://itest-oppija.oph.ware.fi/calendar/css/calendar.css',
                                    underscore: 'https://itest-oppija.oph.ware.fi/calendar/lib/underscore-min.js'
                                },
                                calendarResource: 'https://itest-oppija.oph.ware.fi/as/fetchForCalendar'
                            });
                        });
                    }());
                    // ]]></script>
                </div>
                <div class="row col-xs-16">
                    <h2 class=""><?php _e( 'News', 'html5blank' ); ?></h2>
                <?php if ($features->have_posts()): while ($features->have_posts()) : $features->the_post(); ?>
                    <h5><?php the_title(); ?></h5>   
                    <?php the_content(); ?>
                <?php endwhile; ?>
                
                <?php else: ?>
                    <h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
                <?php endif; ?>
                </div>
            </div>

        </div>

<?php //get_sidebar('widget-area-2'); ?>

<?php get_footer ( get_bloginfo('language') ); ?>