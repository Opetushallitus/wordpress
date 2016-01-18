<?php

/*
* Template Name: OPH Default
*/

get_header(); ?>

<div class="grid_16">
    <h1><a href="<?php bloginfo('wpurl'); ?>">Virkailijan työpöytä</a></h1>
</div>

<?php get_sidebar( 'left' ); ?>

	<div id="content" class="grid_8">
            <div id="entries">
            <?php if (have_posts()) : ?>

                    <?php while (have_posts()) : the_post(); ?>
                
                    <div class="header-announcements">
                        <h2><?php // echo $EM_Event->output('#_EVENTDATES'); ?> <?php the_title(); ?> </h2>
                    </div>
                    
                    <div id="entries-content">

                            <div <?php post_class() ?> id="post-<?php the_ID(); ?>">

                                    <div class="entry">
                                            <?php the_content('Read the rest of this entry &raquo;'); ?>
                                    </div><!-- end entry -->

                            </div><!-- end post -->

                    <?php endwhile; ?>

                    <div class="navigationBottom">
                            <div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
                            <div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
                    </div><!-- end navigation -->

                    <?php else : ?>

                    <h2 class="center">Not Found</h2>
                    <p class="center">Sorry, but you are looking for something that isn't here.</p>
                    <?php get_search_form(); ?>

                    <?php endif; ?>
                </div><!-- end entries-content -->
            </div><!-- end entries -->
	</div><!-- end content -->

<?php get_sidebar('right'); ?>

<?php get_footer(); ?>
