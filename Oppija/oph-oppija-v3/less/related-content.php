<div class="sub-page">
    <aside>
<?php 
        
            $my_query = oph_related_taxonomy_query( array('oph-koulutus', 'oph-koulutusaste', 'oph-ammattiluokitus'));

            if( $my_query->have_posts() ) : ?>
                    <section>
                        <h3><?php _e('Katso myös') ?></h3>
                        <p>
                            <?php while ( $my_query->have_posts() ) : ?>
                                <?php $my_query->the_post(); ?>
                                <p>
                                        <?php the_excerpt() ?>
                                </p>
                                <p>
                                    <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
                                </p>
                            <?php endwhile; ?>                            
                        </p>
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
                        <h3><?php _e('Huomautukset') ?></h3>
                        <p>
                            <?php while ( $my_query->have_posts() ) : ?>
                                <?php $my_query->the_post(); ?>
                                <h4><?php the_title() ?></h4>
                                <p>
                                        <?php the_excerpt() ?>
                                </p>
                                <p>
                                    <a href="<?php the_permalink() ?>">Lue lisää..</a>
                                </p>
                            <?php endwhile; ?>                            
                        </p>
                    </section>
            <?php endif;
        
        wp_reset_query();       
?>  
        
    </aside>
</div>