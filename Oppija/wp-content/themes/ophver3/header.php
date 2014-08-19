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

	<!-- "Typewriter" Font -->
	<link href='//fonts.googleapis.com/css?family=PT+Sans+Narrow:700|PT+Serif:400italic' rel='stylesheet' type='text/css'>

	<?php

	$debug = false;

	if($debug) : ?>
		<script type="text/javascript">var less=less||{};less.env='development';</script>
		<link rel="stylesheet/less" type="text/css" href="<?php echo get_template_directory_uri(); ?>/less/style.less">
		<script src="<?php echo get_template_directory_uri(); ?>/js/vendor/less-1.3.1.min.js"></script>
        <?php else : ?>
                <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/style.css">
	<?php endif; ?>

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
    <a href="#maincontent" class="offscreen"><?php _e('Skip to content', 'html5blank'); ?></a>
    <noscript>
       <div class="notification">
           <div class="notif-nojs">
               <img src="<?php echo get_template_directory_uri(); ?>/img/notif_icon.png " class="notif-icon">
               <div><?php _e('Toistaiseksi Opintopolku.fi:n käyttö edellyttää JavaScript -tukea. Voit halutessasi ottaa JavaScriptin käyttöön selaimesi asetuksista.', 'html5blank'); ?></div>
           </div>
       </div>
    </noscript>
    <div id="search">
		<div class="search">
			<form action="/app/#!/haku/">
				<fieldset class="search-container">
				    <legend></legend>
					<label for="search-field-frontpage" class="h2"><?php _e('Etsi koulutuksia tästä') ?></label>
					<input type="text" tabindex="1" class="search-field" id="search-field-frontpage" name="search-field" placeholder="<?php _e('Kirjoita tähän esim. tutkinto, ammatti tai oppilaitoksen nimi') ?>" value="">
					<button class="button primary magnifier" type="submit"><span><span class="h2"><?php _e('Hae') ?></span></span></button>
				</fieldset>
			</form>
		</div>

	</div>

	<div id="maincontent" class="content container">
