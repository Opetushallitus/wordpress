<div class="sub-page">
    <aside>
        <section>             
<?php 
 
$ids = get_field('sidebar-content', false, false);

$args = array(
    'post__in' => $ids,
    'post_type' => 'sidebar-content',
    'post_status' => 'publish'
);

$query = new WP_Query($args); ?>
           
    <?php if ($query->have_posts()): while ($query->have_posts()) : $query->the_post(); ?>
           
            <div class="sidebar-node">
            <?php setup_postdata($post); ?>
                <h3><?php the_title(); ?></h3>
                <div><?php the_content(); ?></div>
             </div>    
           
        <?php wp_reset_postdata(); // Reset ?>
    <?php endwhile; ?>
	
	<?php else: ?>
           	
		<!-- article -->
		<article>
			
		</article>
		<!-- /article -->
	
	<?php endif; ?>
            
</section>
</aside>
</div>