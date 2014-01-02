<!DOCTYPE html>
<!--[if IE 8]> <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
	<meta charset="<?php bloginfo('charset'); ?>">
	<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

    <meta name="google" content="notranslate" />
    
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
	
	<meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/img/Studieinfo.png" />
	<meta property="og:type" content="article" />
	<meta property="og:locale" content="sv_FI" />

	<!-- icons -->
	<link href="<?php echo get_template_directory_uri(); ?>/img/favicon16.ico" rel="shortcut icon">
	<link href="<?php echo get_template_directory_uri(); ?>/img/favicon32.ico" rel="apple-touch-icon-precomposed">

	<!-- For iOS web apps. -->
	<meta name="apple-mobile-web-app-capable" content="no" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="apple-mobile-web-app-title" content="<?php bloginfo('name'); ?>" />

	<!-- "Typewriter" Font -->
	<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:700|PT+Serif:400italic' rel='stylesheet' type='text/css'>

	<?php 
	
	$debug = true;

	if($debug) : ?>
		<link rel="stylesheet/less" type="text/css" href="<?php echo get_template_directory_uri(); ?>/less/style.less">
		<script src="<?php echo get_template_directory_uri(); ?>/js/vendor/less-1.3.1.min.js"></script>
	<?php endif; ?>


	<!-- selectivizr needs *.css files instead of *.less -->
	<!--[if lt IE 9]>

	<script src="<?php echo get_template_directory_uri(); ?>/js/vendor/matchmedia.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/vendor/respond.min.js"></script>

	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/vendor/selectivizr.min.js"></script>
	<![endif]-->
	
	<!-- css + javascript -->
		<?php wp_head(); ?>
		<script>
		var templateDir = '<?php echo get_template_directory_uri(); ?>';
		!function(){
			// configure legacy, retina, touch requirements @ conditionizr.com
			conditionizr()
		}()
		</script>
</head>

<body <?php body_class(); ?>>
	<header>
		<div class="logo-bg">
			<div class="container">
				<a id="home-link" href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/img/Studieinfo.png" alt="Studieinfo.fi" /></a>

				<?php is_qa(); ?>

				<div class="actions">
		            <ul>
		                <li><a href="http://www.opintopolku.fi">Suomeksi</a></li>
		                <li><a href="http://www.studieinfo.fi">På svenska</a></li>
		            </ul>

		        </div>
		        
		        <div class="actions primarylinks">
					<ul>
						<li class="icon basket">
							<a href="http://opintopolku.fi/app/#/muistilista">
								<span>Minneslista (<span class="appbasket-count">0</span>)</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
				
		<div class="search">
			<form action="#">
				<fieldset class="search-container">
					<label for="search-field" class="h2">Sök utbildningar här</label>
					<input type="text" tabindex="1" class="search-field" id="search-field-frontpage" name="search-field" placeholder="Skriv här t.ex. examen, yrke eller läroanstaltens namn" value="">
					<button class="button primary magnifier" type="submit"><span><span class="h2">Sök</span></span></button>
				</fieldset>
			</form>
		</div>
	</header>
	
	<div class="content container">		