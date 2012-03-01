<!DOCTYPE html>

<!--[if lt IE 7 ]> <html class="ie ie6 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<!-- the "no-js" class is for Modernizr. -->

<head id="ona12-journalists-org" data-template-set="html5-reset-wordpress-theme" profile="http://gmpg.org/xfn/11">

	<meta charset="<?php bloginfo('charset'); ?>">
	
	<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<?php if (is_search()) { ?>
	<meta name="robots" content="noindex, nofollow" /> 
	<?php } ?>

	<title>
		   <?php
		      if (function_exists('is_tag') && is_tag()) {
		         single_tag_title("Tag Archive for &quot;"); echo '&quot; - '; bloginfo('name'); }
		      elseif (is_archive()) {
		         wp_title(''); echo ' Archive - '; bloginfo('name'); }
		      elseif (is_search()) {
		         echo 'Search for &quot;'.wp_specialchars($s).'&quot; - '; bloginfo('name'); }
		      elseif (is_front_page()) {
		         bloginfo('name'); echo ' - '; bloginfo('description'); }
		      elseif (!(is_404()) && (is_single()) || (is_page())) {
		         wp_title(''); echo ' - '; bloginfo('name'); }
		      elseif (is_404()) {
		         echo 'Not Found - '; bloginfo('name'); }
		      if ($paged>1) {
		         echo ' - page '. $paged; }
		   ?>
	</title>
	
	<meta name="title" content="<?php
		      if (function_exists('is_tag') && is_tag()) {
		         single_tag_title("Tag Archive for &quot;"); echo '&quot; - '; bloginfo('name'); }
		      elseif (is_archive()) {
		         wp_title(''); echo ' Archive - '; bloginfo('name'); }
		      elseif (is_search()) {
		         echo 'Search for &quot;'.wp_specialchars($s).'&quot; - '; bloginfo('name'); }
		      elseif (is_front_page()) {
		         bloginfo('name'); echo ' - '; bloginfo('description'); }
		      elseif (!(is_404()) && (is_single()) || (is_page())) {
		         wp_title(''); echo ' - '; bloginfo('name'); }
		      elseif (is_404()) {
		         echo 'Not Found - '; bloginfo('name'); }
		      if ($paged>1) {
		         echo ' - page '. $paged; }
		   ?>">
	<meta name="description" content="<?php bloginfo('description'); ?>">
	
	<meta name="google-site-verification" content="">
	<!-- Speaking of Google, don't forget to set your site up: http://google.com/webmasters -->
	
	<meta name="author" content="Online News Association">
	<meta name="Copyright" content="Copyright Online News Association <?php echo date("Y"); ?>. All rights reserved.">

	<!-- Dublin Core Metadata : http://dublincore.org/ -->
	<meta name="DC.title" content="ONA12">
	<meta name="DC.subject" content="The 2012 Online News Association Conference & Awards Banquet">
	<meta name="DC.creator" content="Online News Association">
	
	<!--  Mobile Viewport meta tag
	j.mp/mobileviewport & davidbcalhoun.com/2010/viewport-metatag 
	device-width : Occupy full width of the screen in its current orientation
	initial-scale = 1.0 retains dimensions instead of zooming out if page height > device height
	maximum-scale = 1.0 retains dimensions instead of zooming in if page width < device width -->
	<!-- Uncomment to use; use thoughtfully!
	<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
	-->
	
	<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/_/img/favicon.ico">
	<!-- This is the traditional favicon.
		 - size: 16x16 or 32x32
		 - transparency is OK
		 - see wikipedia for info on browser support: http://mky.be/favicon/ -->
		 
	<link rel="apple-touch-icon" href="<?php bloginfo('stylesheet_directory'); ?>/_/img/apple-touch-icon-precomposed.png">
	<!-- The is the icon for iOS's Web Clip.
		 - size: 57x57 for older iPhones, 72x72 for iPads, 114x114 for iPhone4's retina display (IMHO, just go ahead and use the biggest one)
		 - To prevent iOS from applying its styles to the icon name it thusly: apple-touch-icon-precomposed.png
		 - Transparency is not recommended (iOS will put a black BG behind the icon) -->
	
	<!-- CSS: screen, mobile & print are all in the same file -->
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css">
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/960.css" type="text/css">
	
	<!-- Adding web fonts -->
	
	<link href="http://fonts.googleapis.com/css?family=Bitter" rel="stylesheet" type="text/css">
	
	<!-- all our JS is at the bottom of the page, except for Modernizr. -->
	<script src="<?php bloginfo('template_directory'); ?>/_/js/modernizr-1.7.min.js"></script>
	
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

	<?php wp_head(); ?>
	
</head>

<body <?php body_class(); ?>>
	
	<div id="page-wrap"><!-- not needed? up to you: http://camendesign.com/code/developpeurs_sans_frontieres -->
		
		<div id="nav-container">
		<nav id="nav" class="container_12">
			<div id="nav-content" class="grid_12">
			<?php wp_nav_menu( array( 'theme_location' => 'navigation-menu' ) ); ?>
			</div><!-- #nav-content -->
		</nav>
		</div><!-- #nav-container -->		
		
		<div id="header-container">
		<header id="header" class="container_12">
			
			<div class="header-content grid_3">
			<a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('stylesheet_directory');  ?>/img/ona12-300px-t.png" class="header-logo" /></a>
			</div>
			
			<div id="header-text" class="header-content grid_8 push_1">
			<h2><a href="<?php bloginfo('url'); ?>">The 2012 Online News Association<br />Conference & Awards Banquet</a></h2>
			<h3>Sept. 20-22, Hyatt Regency San Francisco</h3>
			</div><!-- #header-text -->
			
		</header>
		</div><!-- #header-container -->
		
		<div id="content-container">