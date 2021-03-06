<?php
/*
Template Name: Ysi-info
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

	<!-- secondary navigation -->
<div class="row padding-bottom-10">
    <div class="col-xs-16 col-sm-16 col-sm-16 col-sm-16">	
        <div class="col-xs-16 col-sm-16 col-md-4 col-lg-4">   
            <nav class="sidenav">
                <ul>
                    <?php oph_subnavi(); ?>
                </ul>
            </nav>
        </div>	
  
    <!-- /secondary navigation -->   
 	<div class="bg-item col-xs-16 col-sm-16 col-md-12 col-lg-12">  
      
      <!-- main-column -->
      <div class="main-column">
          
          <?php if (have_posts()): while (have_posts()) : the_post(); ?>
	
        <!-- box-general -->
        <div class="box-general">
            <h1><?php the_field('main-title'); ?></h1>
            <?php the_field('box-general'); ?>
        </div><!-- /box-general -->
        
        <!-- box-options -->
        <div class="box-options grad-gray gradient">
            <h2><?php the_field('box-options-title'); ?></h2>
            <?php the_field('box-options'); ?>
        </div><!-- /box-options -->

        <!-- box-tab -->
        <div class="box-tab">
            
          <?php
          
          if( have_rows('box-tabs') ): ?>

            <div class="padder grad-gray gradient">
                <h3><?php _e( 'Learn more about studies', 'html5blank' ); ?></h3>

                    <ul id="tabs">

                      <?php
                      $i = 1;
                      
                      while ( have_rows('box-tabs') ) : the_row(); ?>
                           <!-- padder -->
                           
                           <li><a href="" id="tab<?php echo $i; $i++; ?>"><?php the_sub_field('box-tab-title'); ?></a></li>
                            
          <?php endwhile; ?>

                    </ul>
            </div><!-- /padder -->
                
            
            
            <?php else : ?>  
            
            <?php endif; ?>
            
            
            
            <?php if( have_rows('box-tabs') ): 
                
                $l = 1;
                      
                    while ( have_rows('box-tabs') ) : the_row(); ?>
                
                <!-- tab-container -->
                <div class="tab-container" id="tab<?php echo $l; $l++; ?>C">

                  <!-- padder -->
                  <div class="padder">

                    <h2><?php the_sub_field('box-tab-title'); ?></h2>
                    
                    <?php the_sub_field('box-tab-content'); ?>

                  </div><!-- /padder -->

                </div><!-- /tab-container -->
          
            <?php endwhile; ?>
          
            <?php else : ?>  
            
            <?php endif; ?>
          
        </div><!-- /box-tab -->
        
        </div><!-- /main-column -->
      
        
        <!-- side-column -->
        <div class="side-column">

          <!-- box-opo -->
            <div class="box-opo">

            <?php $image = get_field('box-opo-img');

            if( !empty($image) ): 

                $url = $image['url'];
                $alt = $image['alt'];

                $size = 'oph-ysi-info-opo';
                $thumb = $image['sizes'][ $size ];  ?>

                        <img src="<?php echo $thumb; ?>" alt="<?php echo $alt; ?>" class="opo-title" border="0"/>

            <?php endif; ?>
              
            <?php the_field('box-opo-main-content'); ?>            
                        
                        
          </div><!-- /box-opo -->

          <!-- box-video -->
          <div class="box-video grad-gray-lighter gradient">
          <?php if( have_rows('box-video') ):

                  // loop through the rows of data
                    while ( have_rows('box-video') ) : the_row(); ?>

                    <div class="ysi-info-video">
                    
                      <?php if (get_sub_field('box-video-title')) {?> 
                        <h3> <?php the_sub_field('box-video-title'); ?> </h3> 
                      <?php }?>

                      <?php    
                        $video_embed = wp_oembed_get(get_sub_field('box-video-url'), array('width'=>248)); echo $video_embed; 
                      
                        the_sub_field('box-video-desc'); ?>

                    </div>
                      <?php endwhile;

                else :

                // not found!

                endif ?>

          </div><!-- /box-video -->

        </div><!-- /side-column -->
        

        <?php endwhile; ?>
	
	<?php else: ?>
	
		<!-- article -->
		<article>
			
			<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
			
		</article>
		<!-- /article -->
	
	<?php endif; ?>

</div>
<?php get_footer( get_bloginfo('language') ); ?>