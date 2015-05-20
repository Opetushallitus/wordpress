<div class="sub-page">
    <aside>
        <section>             
<?php 
 
$posts = get_field('sidebar-content');
 
    if( $posts ): ?>
        <?php foreach( $posts as $post): ?>
            <div class="sidebar-node">
            <?php setup_postdata($post); ?>
                <h3><?php the_title(); ?></h3>
                <div><?php the_content(); ?></div>
             </div>    
        <?php endforeach; ?>
           
        <?php wp_reset_postdata(); // Reset ?>
    <?php endif; ?>
            
</section>
</aside>
</div>