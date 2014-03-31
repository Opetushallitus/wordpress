<!DOCTYPE html>
<!--[if IE 8]> <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<meta charset="<?php bloginfo('charset'); ?>">
        <title>
            <?php
            
            $alt_title = get_field('alternative_title');
            
            if(empty($alt_title) ) {
                echo wp_title('');
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
	<meta name="description" content="<?php bloginfo('description'); ?>">
		
	<!-- og:tags -->
	<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
	<meta property="og:title" content="<?php bloginfo('name'); ?>" /> 
	<meta property="og:description" content='<?php bloginfo('description'); ?>' />
	
	<meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/img/Opintopolku_FI_logo.png" />
	<meta property="og:type" content="article" />
	<meta property="og:locale" content="fi_FI" />

	<!-- icons -->
	<link href="<?php echo get_template_directory_uri(); ?>/img/favicon16.ico" rel="shortcut icon">
	<link href="<?php echo get_template_directory_uri(); ?>/img/favicon32.ico" rel="apple-touch-icon-precomposed">

	<!-- For iOS web apps. -->
	<meta name="apple-mobile-web-app-capable" content="no" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?>" />

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
                
                
        <script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.easing.1.3.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.galleryview-3.0-dev.js"></script>
        <script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.timers-1.2.js"></script>
        
        <link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/jquery.galleryview-3.0-dev.css" />
                
</head>
<body <?php body_class(); ?>>
    <a href="#maincontent" class="offscreen"><?php _e('Skip to content', 'html5blank'); ?></a>
    <noscript>
       <div class="notification">
           <div class="notif-nojs">
               <img src="<?php echo get_template_directory_uri(); ?>/img/notif_icon.png " class="notif-icon">
               <div><?php _e('Toistaiseksi Opintopolku.fi:n käyttö edellyttää JavaScript -tukea. Voit halutessasi ottaa JavaScriptin käyttöön selaimesi asetuksista.', 'html5blank'); ?></div> 
           </div>
       </div>
    </noscript>
    <header id="siteheader">
		<div class="logo-bg">
			<div class="container">
				<?php if (ICL_LANGUAGE_CODE == 'sv') : ?>
					<a id="home-link" href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/Studieinfo.png" alt="Studieinfo.fi" /></a>
				<?php else : ?>
					<a id="home-link" href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/Opintopolku_FI_logo.png" alt="Opintopolku.fi" /></a>	
				<?php endif ?>
				
				
				<?php is_qa(); ?>
				
				
				<?php $languages = icl_get_languages('skip_missing=0'); ?>
				<div class="actions">
		            <ul>
		            	<?php foreach ($languages as $lang) : ?>
		            		<li><a href="<?php echo $lang['url'] ?>"><?php echo $lang['native_name'] ?></a></li>
		            	<?php endforeach ?> 
		            </ul>
		        </div>
		        
		        <div class="actions primarylinks">
					<ul>
						<li class="icon basket">
							<a href="/app/#!/muistilista">
								<span><?php _e('Muistilista') ?> (<span class="appbasket-count">0</span>)</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<!-- nav -->
		<nav class="nav" role="navigation">
			<div class="container">
		        
			     <?php add_filter('the_title', 'show_short_title', 10, 2);
			             do_action('icl_navigation_menu');
			         remove_filter('the_title', 'show_short_title'); ?>
				<?php //html5blank_nav(); ?>
                            
                             <div class="textversion"">
                                <?php if (ICL_LANGUAGE_CODE == 'sv') : ?>
					<a href="/m/index_sv.html">Specialläroanstalternas utbildningar som textversion</a>
				<?php else : ?>
					<a href="/m/index.html">Erityisoppilaitosten koulutukset tekstiversiona</a>	
				<?php endif ?>
                            </div>           
                            
			</div>
		</nav>
		<!-- /nav -->

				
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
	</header>
	
	<div id="maincontent" class="content container">		
