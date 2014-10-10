<!DOCTYPE html>
<!--[if IE 8]> <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<meta charset="<?php bloginfo('charset'); ?>">

        <title>
            <?php

            $alt_title = get_field('alternative_title') ;
            $site_title = get_bloginfo();

            if(empty($alt_title) ) {
                _e($site_title, 'html5blank');
                echo wp_title(' :');
            }
            else {
                echo $alt_title;
            }
            ?></title>

    <meta name="google" content="notranslate" />

    <?php if(is_front_page()) : ?>
	<meta name="google-site-verification" content="Hfc9R_6N1QPibD-tkZsJEwysP2EKFlpQ1VI3pBvgE3U" />
    <?php endif; ?>

	<!-- dns prefetch -->
	<link href="//www.google-analytics.com" rel="dns-prefetch">

	<!-- meta -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<meta name="description" content="<?php echo((!is_front_page() && !is_archive()) ? (get_the_title() . ' - ' . __($site_title, 'html5blank')) : get_bloginfo('description')); ?>">

	<!-- og:tags -->
	<meta property="og:site_name" content="<?php _e($site_title, 'html5blank'); ?>" />
	<meta property="og:title" content="<?php _e($site_title, 'html5blank'); ?>" />
	<meta property="og:description" content="<?php echo((!is_front_page() && !is_archive()) ? (get_the_title() . ' - ' . __($site_title, 'html5blank')) : get_bloginfo('description')); ?>" />

	<meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/img/Opintopolku_FI_logo.png" />
	<meta property="og:type" content="article" />
	<meta property="og:locale" content="fi_FI" />

	<!-- icons -->
	<link href="<?php echo get_template_directory_uri(); ?>/img/favicon16.ico" rel="shortcut icon">
	<link href="<?php echo get_template_directory_uri(); ?>/img/favicon32.ico" rel="apple-touch-icon-precomposed">

	<!-- For iOS web apps. -->
	<meta name="apple-mobile-web-app-capable" content="no" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="apple-mobile-web-app-title" content="<?php _e($site_title, 'html5blank'); ?>" />

	<!--[if lt IE 9]>

	<script src="<?php echo get_template_directory_uri(); ?>/js/vendor/matchmedia.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/vendor/respond.min.js"></script>

	<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/vendor/selectivizr.min.js"></script>
	<![endif]-->

	<!-- css + javascript -->
		<?php wp_head(); ?>
		<script>
		var templateDir = '<?php echo get_template_directory_uri(); ?>';
		!function(){
			// configure legacy, retina, touch requirements @ conditionizr.com
			//conditionizr()
		}()
		</script>

             
</head>
<body <?php body_class(); ?> style="display: none" aria-busy="true">
    <div class="container-fluid">
    <a href="#maincontent" class="offscreen"><?php _e('Skip to content', 'html5blank'); ?></a>
    <noscript>
       <div class="notification">
           <div class="notif-nojs">
               <img src="<?php echo get_template_directory_uri(); ?>/img/notif_icon.png " class="notif-icon">
               <div><?php _e('Toistaiseksi Opintopolku.fi:n käyttö edellyttää JavaScript -tukea. Voit halutessasi ottaa JavaScriptin käyttöön selaimesi asetuksista.', 'html5blank'); ?></div>
           </div>
       </div>
    </noscript>
    <?php
        
        $args = array(
	'post_type' => 'page',
        'order_by' => 'name',
        'order' => 'ASC',
	'tax_query' => array(
		array(
			'taxonomy' => 'oph-additional-tags',
			'field' => 'slug',
			'terms' => array('frontpage-notice-fi', 'frontpage-notice-sv'),
		),
            ),
        );
        
        $frontpage_notice = new WP_Query($args);
        
        ?>
    
    <?php if ($frontpage_notice->have_posts()): while ($frontpage_notice->have_posts()) : $frontpage_notice->the_post(); ?>

    <div class="row">
        <div class="col-xs-16">
            <div class="alert alert-frontpage alert-dismissable">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                <?php echo the_content();  ?>
            </div>
        </div>
    </div>
    
    <?php endwhile; ?>

	<?php else: ?>
    
    <?php endif; ?>
    
    <?php wp_reset_postdata(); ?>
    
    <div class="<?php if (is_front_page() || $post->post_type == 'post') echo 'row'; ?> search-group padding-top-20 padding-bottom-20">
            <form action="/app/#!/haku/" id="ki-search" class="form-horizontal col-lg-13 col-lg-offset-2">
                <div class="form-group">
                        <label for="search-field-frontpage" class="col-lg-4 control-label find">Etsi koulutuksia tästä</label>
                    <div>
                        <div class="col-lg-6">
                        
                        <input type="text" tabindex="1" class="search-field" id="search-field-frontpage" data-provide="typeahead" name="search-field" placeholder="<?php _e('Kirjoita tähän esim. tutkinto, ammatti tai oppilaitoksen nimi') ?>" value="">
                    </div>
                        <div class="col-lg-4">
                        <span class="input-group-btn"><button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span><?php _e('Hae') ?></button></span>
                    </div>
                    </div>
                </div>
            </form>
    </div>
       
        <script>
        $('#ki-search').submit(function() {
            
            var $input = $(this).find('input[name=search-field]');
            
            if (!$input.val()) {
                $input.val('*');
            }
            
        });
        </script>
        
    <div id="maincontent" class="content <?php if ( !is_front_page() && !($post->post_type == 'post')) echo 'container'; ?>">
