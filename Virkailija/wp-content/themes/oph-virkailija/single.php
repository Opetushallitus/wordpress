<?php get_header(); ?>

<div class="grid_16">
    <h1><a href="<?php bloginfo('wpurl'); ?>">Virkailijan työpöytä</a></h1>
</div>

<?php get_sidebar('left'); ?>

	<div id="content" class="grid_8">
            <div id="entries">        
                
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <h2><?php // echo $EM_Event->output('#_EVENTDATES'); ?> <?php the_title(); ?> </h2>
                
                <div id="entry-meta">
                    <small id="single"><?php the_time('j.n.Y') ?> 
                        
                        <?php 
                        $terms = get_the_terms( $post->ID , array('event-categories', 'category'));
                        if($terms) {
                            echo 'Sisältökategoria: ';
                            echo the_category('&bull;');
                        }?>
                        
                        
                        </small>
                </div>
                
                <div id="entries-content-single">
                
                    <div <?php post_class() ?> id="post-<?php the_ID(); ?>">

                            <div class="entry">
                                    <?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>

                                    <?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
                                    <?php the_tags( '<p>Tags: ', ', ', '</p>'); ?>

                            </div><!-- end entry -->
                        </div><!-- end post -->
                    </div><!-- end entries-content -->
                    
                <div class="navigationBottom">
                    <div class="alignleft"><?php previous_post_link('&larr; %link', 'Edellinen tiedote', $in_same_cat = true) ?></div>
                    <div class="alignright"><?php next_post_link('%link &rarr;', 'Seuraava tiedote', $in_same_cat = true) ?></div>
		</div><!-- end navigation -->
                    
		</div><!-- end entries -->
                
	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

	<?php endif; ?>

	</div><!-- end content -->

<?php get_sidebar('right'); ?>

<?php get_footer(); ?>
