<?php get_header(); ?>

<div class="grid_16">
    <h1><a href="<?php bloginfo('wpurl'); ?>">Virkailijan työpöytä</a></h1>
</div>

<?php get_sidebar('left'); ?>

	<div id="content" class="grid_8">
            <div id="entries">
                
                <div class="header-announcements">
                    <h2>Tapahtumat</h2>
                </div>
                
                <div id="entries-content">
                
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
                    <div class="entry-title">
                        <h2><?php the_title(); ?></h2>
                    </div>
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div><!-- end entry -->
			
		
		
		<?php endwhile; endif; ?>
		<?php edit_post_link('Muokkaa.', '<p>', '</p>'); ?>
                </div><!-- end post -->        
	
            </div><!-- end entries-content -->
            </div><!-- end entries -->
	</div><!-- end content -->

<?php get_sidebar('right'); ?>

<?php get_footer(); ?>