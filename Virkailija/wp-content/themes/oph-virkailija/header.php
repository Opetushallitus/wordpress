<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<! Dynamic Title Tag Optimized for Search Engine Visibility -->
<title><?php if (is_front_page()) {
		bloginfo('description');
		} elseif (is_search()) {
		bloginfo('name');?> &raquo; Search Results for: <?php echo wp_specialchars($s, 1);
		} else {
		wp_title('',true); ?> &#8212; <?php bloginfo('name');
		} ?></title>


<!-- These two lines call the css files essential for the 960 grid system - DO NOT REMOVE!! -->
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/css/reset.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/css/960.css" />

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />



<!-- Conditional comments for IE. Use ie7.css and ie6.css for custom css for Internet Explorer version 7 and 6 if necessary. -->
	<!--[if IE 7]>
	<style type="text/css" media="screen">
		@import url( <?php bloginfo('stylesheet_directory'); ?>/ie7.css );
	</style>
	<![endif]-->	
	<!--[if IE 6]>
	<style type="text/css" media="screen">
		@import url( <?php bloginfo('stylesheet_directory'); ?>/ie6.css );
	</style>
	<![endif]-->	
	
<!-- uncomment the following style to view the grid as a background image -->
	<!--
	<style type="text/css" media="all">
	.container_12
	{
		background: #fff url(<?php bloginfo('stylesheet_directory'); ?>/images/12_col.gif) repeat-y;
	}
	.container_16
	{
		background: #fff url(<?php bloginfo('stylesheet_directory'); ?>/images/16_col.gif) repeat-y;
	}
	</style>
	-->
	
<script>
  $(function() {
    $( "#tabs" ).tabs();
  });
 </script>        
        
<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' );?>	
<?php wp_head(); ?>
</head>
<body>
<div id="page">

	<div id="header">
		
		<div class="container_16">
		
			<div id="headerimg" class="grid_16">
                            <img src="<?php bloginfo('stylesheet_directory'); ?>/images/Opintopolku_FI_logo.png" title="Opintopolku" alt="Opintopolku" />
			</div><!-- end headerimg -->
			
			<!-- skip to content and menu links - these are hidden with screen reader friendly css for accessibility -->
			<div id="skip">
			<p></p><a href="#wrapper" title="skip to main content">skip to main content</a></p>
			<p></p><a href="#main-menu" title="skip to main menu">skip to main menu</a></p>
			</div><!-- end skip -->
			
			<!-- This clears all floats -->
			<div class="clear">&nbsp;</div>
		
		</div><!-- end headerContainer -->
		
	</div><!-- end header -->
	
        <div id="menu">
            <?php wp_nav_menu(array('theme_location' => 'external_menu')); ?>
        </div>
        
        <div class="meta-info">
            <?php
                $current_user = wp_get_current_user();
                echo $current_user->display_name.': ';
                echo date('l, j.n.Y, G:i');
            ?>
        </div>
        
        
        
	<!-- The class "container_12" restricts the grid to 12 columns and a total of 960px wide. Placing the wrapper div in the header.php file allows for easy editing of individual page templates without upsetting the grid. This div is closed in the footer.php file -->
	<div id="wrapper" class="container_16">