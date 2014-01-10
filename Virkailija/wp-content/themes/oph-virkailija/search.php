<?php get_header(); ?>

<div class="grid_16">
    <h1><a href="<?php bloginfo('wpurl'); ?>">Virkailijan työpöytä</a></h1>
</div>

<?php get_sidebar('left'); ?>

	<div id="content" class="grid_8">
            <div id="entries">        
                
                <?php if (have_posts()) : ?>
                
		<?php while (have_posts()) : the_post(); ?>
			<div <?php post_class() ?>>
                            
                                <div class="entry-title">
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
                                </div>
                                <?php if($post->post_excerpt) { the_excerpt(); } else { ?>
                                <p><?php echo get_excerpt(160); ?></p> <?php } ?>
				
			</div><!-- end post -->

		<?php endwhile; ?>

	<?php else : ?>

		<h2>No posts found. Try a different search?</h2>
		<?php get_search_form(); ?>

	<?php endif; ?>

            </div> <!-- end entries -->
        </div> <!-- end content -->

    <?php get_sidebar('right'); ?>
        
<?php get_footer(); ?>