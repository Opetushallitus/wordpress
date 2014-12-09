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
	
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	<div class="col-md-8 col-md-offset-2">
		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
			<h1><?php the_title(); ?></h1>
		    
		    <div class="post-meta">
		    <?php _e( 'Author: ', 'html5blank' ); the_author(); ?>
		    <?php  _e( 'Published: ', 'html5blank' ); the_time('j.n.Y'); ?>
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
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 96 ); ?>
            </div>
            <div class="author-name">
                <?php _e( 'Author: ', 'html5blank' ); the_author(); ?>
            </div>
        </div>
        <hr />
        <h3><?php _e('Archives', 'html5blank'); ?></h3>
        <div class="post-archive">
            <?php wp_get_archives('type=monthly'); ?>
        </div>
        <hr />
        <h3><?php _e('Tags', 'html5blank'); ?></h3>
        <?php

            $term_args = array(
                'exclude' => array(7280, 7279, 7281)
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
	<?php endwhile; ?>
	
	<?php else: ?>
	
		<!-- article -->
		<article>
			
			<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
			
		</article>
		<!-- /article -->
	
	<?php endif; ?>
	
	</div>
<?php //get_sidebar(); 
    get_template_part('related-content');
?>
    
    <?php require_once('sidebar-content.php') ?>

<?php get_footer( get_bloginfo('language') ); ?>