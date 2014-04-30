<div class="sub-page">
    <aside>
<?php 
        
            $my_query = oph_related_taxonomy_query( array('oph-koulutus', 'oph-koulutusaste', 'oph-ammattiluokitus'));

            if( $my_query->have_posts() ) : ?>
                    
                    <section class="lookup">
                            <h5><?php _e('Katso myös') ?></h5>
                            <?php while ( $my_query->have_posts() ) : ?>
                                <?php $my_query->the_post(); ?>
                                    <a href="<?php the_permalink() ?>"><?php the_title() ?>.</a>
                                    <?php the_excerpt() ?>
                            <?php endwhile; ?>                            
                    </section>
            <?php endif;
        
        wp_reset_query();       
?>

<?php 
        
        $my_query = oph_related_taxonomy_query( array('oph-koulutus', 'oph-koulutusaste', 'oph-ammattiluokitus'), 'oph-related');

            if( $my_query->have_posts() ) : ?>
                    <section>
                        <h3><?php _e('Aiheeseen liittyvää') ?></h3>
                        <p>
                            <?php while ( $my_query->have_posts() ) : ?>
                                <?php $my_query->the_post(); ?>
                                <?php the_content() ?>
                            <?php endwhile; ?>                            
                        </p>
                    </section>
            <?php endif;
        
        wp_reset_query();       
?>

<?php 
        
            $my_query = oph_related_taxonomy_query(array('oph-huomautukset'), 'oph-notification');

            if( $my_query->have_posts() ) : ?>
                    <section>                       
                                <?php while ( $my_query->have_posts() ) : ?>
                                <div class="notification">
                                    <?php $my_query->the_post(); ?>
                                    <div class="notif-title">
                                        <img src="<?php bloginfo('template_url'); ?>/img/notif_icon.png " class="notif-icon" />
                                        <h4><?php the_title() ?></h4>
                                    </div>
                                    <div class="notif-content">
                                        
                                            <?php the_excerpt() ?>
                                        
                                        <p class="read-more">
                                            <a href="<?php the_permalink() ?>"><?php _e('Lue lisää...'); ?></a>
                                        </p>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                        
                    </section>
            <?php endif;
        wp_reset_query();       
?>              
        
    </aside>
</div>