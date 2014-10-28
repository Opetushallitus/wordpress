<?php get_header( get_bloginfo('language') ); ?>

        <div class="row padding-bottom-10">

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
        'orderby' => 'type',
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
        'orderby' => 'type',
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
        'orderby' => 'type',
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
        $intro_pages2 = get_posts($args2);
        $intro_pages3 = get_posts($args3);
        $intro_pages4 = get_posts($args4);?>

            <div class="col-sm-16 col-md-4 col-lg-4 frontpage-link">
            <?php if ( $intro_pages->have_posts() ) {
                while ( $intro_pages->have_posts() ) {
                    $intro_pages->the_post(); ?>
                    
                    <div class="row col-xs-16">                       
                        <h2 class=""><?php echo get_the_title(); ?></h2>   
                        <?php //var_dump($content); ?> 
                    </div>
                
                <?php }
            } else {
                // no posts found
            } ?>
            </div>
            
            <div class="col-sm-16 col-md-4 col-lg-4 frontpage-link">
            <?php foreach($intro_pages2 as $content2) {
                $post_id2 = $content2->ID;
                $post_type2 = $content2->post_type;
                //var_dump($content);
                ?>

                    <div class="row col-xs-16">                       
                        <h2 class=""><?php echo get_the_title($post_id2); echo ' "' . $post_type2 . '"'; ?></h2>   
                        <?php //var_dump($content); ?> 
                    </div>

            <?php } ?>
            </div>
            
            <div class="col-sm-16 col-md-4 col-lg-4 frontpage-link">
            <?php foreach($intro_pages3 as $content3) {
                $post_id3 = $content3->ID;
                $post_type3 = $content3->post_type;
                //var_dump($content);
                ?>

                    <div class="row col-xs-16">                       
                        <h2 class=""><?php echo get_the_title($post_id3); echo ' "' . $post_type3 . '"'; ?></h2>   
                        <?php //var_dump($content); ?> 
                    </div>

            <?php } ?>
            </div>
            
            <div class="col-sm-16 col-md-4 col-lg-4 frontpage-link">
            <?php foreach($intro_pages4 as $content4) {
                $post_id4 = $content4->ID;
                $post_type4 = $content4->post_type;
                //var_dump($content);
                ?>

                    <div class="row col-xs-16">                       
                        <h2 class=""><?php echo get_the_title($post_id4); echo ' "' . $post_type4 . '"'; ?></h2>   
                        <?php //var_dump($content); ?> 
                    </div>

            <?php } ?>
            </div>
            
        </div>

<?php //get_sidebar('widget-area-2'); ?>

<?php get_footer ( get_bloginfo('language') ); ?>