<?php get_header( get_bloginfo('language') ); ?>


	<?php if (have_posts()): while (have_posts()) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" class="main" >

			<h1><?php the_title(); ?></h1>

			<?php the_content(); ?>

			<br class="clear">

			<?php edit_post_link(); ?>

		</article>
		<!-- /article -->

	<?php endwhile; ?>

	<?php else: ?>

		<!-- article -->
		<article>

			<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>

		</article>
		<!-- /article -->

	<?php endif; ?>

	<!-- nostot -->
	<?php
			$featured = oph_nostot();

			if ( count($featured ) > 0 ) : ?>
				<aside>
					<?php foreach ($featured as $post) : setup_postdata($post); ?>
    					<?php
        					$title = get_the_title();
                            $langInfo = wpml_get_language_information($post->ID);
                            $postLang = $langInfo['locale'];

                            if(ICL_LANGUAGE_CODE == 'fi'){
                                $currentLang = 'fi';
                            }

                            if (ICL_LANGUAGE_CODE == 'sv') {
                                $currentLang = 'sv_SE';
                            }

                            if (!empty($title) && $postLang == $currentLang) :
                        ?>
                        <section>
                            <h2><?php the_title(); ?></h2>
                            <?php the_content(); ?>
                            <hr>
                        </section>
                        <?php endif ?>
                    <?php endforeach; ?>
				</aside>
	       <?php endif; ?>
	<!-- /nostot -->


<?php //get_sidebar('widget-area-2'); ?>

<?php get_footer ( get_bloginfo('language') ); ?>