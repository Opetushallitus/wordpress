<!-- breadcrumb -->
	<nav class="breadcrumb">
    <?php if(function_exists('bcn_display'))
    {
        bcn_display();
    }?>
    </nav>
	<!-- /breaddcrumb -->

	<!-- secondary navigation -->
<div class="row padding-bottom-10">
    <div class="col-xs-16 col-sm-16 col-sm-16 col-sm-16">	
        <div class="col-xs-16 col-sm-4 col-md-4 col-lg-4">   
            <nav class="sidenav">
                <ul>
                    <?php oph_subnavi(); ?>
                </ul>
            </nav>
        </div>	
	
    <!-- /secondary navigation -->   
	
	<div class="col-xs-16 col-sm-8 col-md-8 col-lg-8">  
	
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	
		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
			<h1><?php the_title(); ?></h1>
		
			<?php the_post_thumbnail('large') ?>
		
			<?php the_content(); ?>
						
			<?php edit_post_link(); ?>
			
			<hr>
			
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
	</div>
	
	<div class="col-xs-16 col-sm-4 col-md-4 col-lg-4">  
    <?php //get_sidebar(); 
        get_template_part('related-content');
    ?>
    
    <?php require_once('sidebar-content.php') ?>
    </div>