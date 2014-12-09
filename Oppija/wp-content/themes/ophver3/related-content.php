
    <aside>
    <?php
        $my_query = oph_related_taxonomy_query( array('oph-huomautukset'), 'oph-notification');

        if( $my_query->have_posts() ) :
    ?>
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
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
    </aside>
