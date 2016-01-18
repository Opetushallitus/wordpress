<?php
/*
Template Name: Blogit
*/ ?>


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
    <div class="col-md-16">
	<div class="col-md-12">  
	
		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
			<h1><?php the_title(); ?></h1>
		
			<?php the_post_thumbnail('large') ?>
		
			<?php the_content(); ?>
						
			<?php edit_post_link(); ?>
			
			<hr>
			
			<?php
            
            $args = array(
                'post_type' => 'post',
                'order_by' => 'post_date'
            );
            
            ?>
			
			
			<?php $the_query = new WP_Query($args); ?>

            <?php if ($the_query->have_posts()) : ?>

                <!-- pagination here -->

                <!-- the loop -->
                <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <!-- article -->
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                       
                    <!-- post title -->
                    <h2>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                    </h2>
                    <!-- /post title -

                    <!-- post details -->
                    <div class="post-meta">
                        <span class="author"><?php _e( 'Published by', 'html5blank' ); ?> <?php the_author_posts_link(); ?></span>
                        <span class="date"><?php the_time('m.j.Y'); ?></span>
                    </div>
                    <!-- /post details -->
                        
                    <?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>

                    <?php edit_post_link(); ?>
                </article>    
                <?php endwhile; ?>
                <!-- end of the loop -->

                <!-- pagination here -->

                <?php wp_reset_postdata(); ?>

            <?php else : ?>
                <p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
            <?php endif; ?>
			
		</article>
		<!-- /article -->		
	</div>
	
	<div class="col-md-4">
                <h3><?php _e('About', 'html5blank'); ?></h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum quod, in dolore quo tempore necessitatibus velit adipisci ut reprehenderit incidunt rerum omnis placeat praesentium ipsum. Veniam autem suscipit tempore numquam?</p>
                <hr />
                <h3><?php _e('Authors', 'html5blank'); ?></h3>
                <div class="post-archive">
                    <?php 
                    
                    $args = array(
                        'orderby' => 'display_name'
                    );

                    $authors = get_users($args);

                    //var_dump($authors);
                    foreach($authors as $author) { 
                        $author_ID = $author->ID;
                        $authors_posts = count_user_posts($author_ID);
                        
                        if($authors_posts >= 1) {
                    ?>
                        <div class="author-info">
                            <div class="author-avatar">
                                <?php echo get_avatar( $author_ID, 48 ); ?>
                            </div>
                            <div class="author-name">
                                <?php echo $author->display_name;?> 
                            </div>    
                        </div>
                        <div style="clear: both;"></div>
                    <?php } 
                    } ?>
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
</section>
</div>	

<?php get_footer(); ?>