<?php get_header(); ?>
    
    <!-- breadcrumb -->
	<nav class="breadcrumb">
    <?php if(function_exists('bcn_display'))
    {
        bcn_display();
    }?>
    </nav>
	<!-- /breaddcrumb -->

	<!-- section -->
	<section role="main">
	<div class="col-md-12">
	
		<?php if (have_posts()): the_post(); ?>
	
		<h1><?php _e( 'Author Archives for ', 'html5blank' ); echo get_the_author(); ?></h1>

	    <?php if ( get_the_author_meta('description')) : ?>
	
	    <?php echo get_avatar(get_the_author_meta('user_email')); ?>
	
		<h2><?php e_( 'About', 'html5blank' ); echo get_the_author() ; ?></h2>
	
	<?php the_author_meta('description'); ?>
	
	<?php endif; ?>
	
	<?php rewind_posts(); while (have_posts()) : the_post(); ?>
	
		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
			<!-- post thumbnail -->
			<?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php the_post_thumbnail(array(120,120)); // Declare pixel size you need inside the array ?>
				</a>
			<?php endif; ?>
			<!-- /post thumbnail -->
			
			<!-- post title -->
			<h2>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
			</h2>
			<!-- /Post title -->
			
			<!-- post details -->
			<div class="post-meta">
                <span class="author"><?php _e( 'Published by', 'html5blank' ); ?> <?php the_author_posts_link(); ?></span>
                <span class="date"><?php the_time('m.j.Y'); ?></span>
            </div>
			<!-- /post details -->
			
			<?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>
			
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
		
		<?php get_template_part('pagination'); ?>
	</div>
	<div class="col-md-4">
            <h3><?php _e('Author', 'html5blank'); ?></h3>
            <div class="author-meta">
                <div class="author-avatar">
                    <?php echo get_avatar( get_the_author_meta( 'ID' ), 48 ); ?>
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
	
	</section>
	<!-- /section -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>