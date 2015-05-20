<?php get_header(); ?>

        <!-- breadcrumb -->
        <nav class="breadcrumb">
        <?php if(function_exists('bcn_display'))
        {
            bcn_display();
        }?>
        </nav>
        <!-- /breaddcrumb -->


	<div class="row padding-bottom-10">
    <section role="main">
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	<div class="col-md-16">
        <div class="col-md-12">
            <!-- article -->
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <h1><?php the_title(); ?></h1>

                <div class="post-meta">
                    <span class="author"><?php _e( 'Published by', 'html5blank' ); ?> <?php the_author_posts_link(); ?></span>
                    <span class="date"><?php the_time('m.j.Y'); ?></span>
                </div>

                <?php the_post_thumbnail('oph-mid-column'); ?>

                <?php the_content(); ?>

                <?php the_tags( __( 'Tags: ', 'html5blank' ), ', ', '<br>'); // Separated by commas with a line break at the end ?>        

                <?php edit_post_link(); ?>

                <hr>

            </article>
            <!-- /article -->
        </div>	

        <div class="col-md-4">
            <h3><?php _e('Author', 'html5blank'); ?></h3>
            <div class="author-meta">
                <div class="author-avatar">
                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 48); ?>
                </div>
                <div class="author-name">
                    <?php _e( 'Author: ', 'html5blank' ); the_author(); ?>
                </div>
            </div>
            <div style="clear: both;"></div>
            <hr />
            <h3><?php _e('Archives', 'html5blank'); ?></h3>
            <div class="post-archive">
                <?php wp_get_archives('type=monthly'); ?>
            </div>
            <hr />
            <h3><?php _e('Tags', 'html5blank'); ?></h3>
            <?php

                $term_args = array(
                    'exclude' => array(7280, 7279, 7281, 7278),
                    'hide_empty' => true
                );

                $terms = get_terms('post_tag', $term_args);

                $html = '<div class="post_tags">';
                foreach ( $terms as $tag ) {
                    $tag_link = get_tag_link( $tag->term_id );

                    $html .= "<a href='{$tag_link}' title='{$tag->name} Tag' class='{$tag->slug}'>";
                    $html .= "{$tag->name}</a> ";
                }
                $html .= '</div>';
                echo $html;
            ?>
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
        </section>
	</div>

<?php get_footer( get_bloginfo('language') ); ?>