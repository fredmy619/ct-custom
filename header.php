<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CT_Custom
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Open+Sans:wght@400;700&family=Ubuntu:wght@300;700&display=swap" rel="stylesheet">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'ct-custom' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="slogan">
			<div class="container">
				<div class="alignleft">
					<p>CALL US NOW! <span>385.154.11.28.35</span></p>
				</div>
				<div class="alignright">
					<nav class="top-nav">
						<a href="#" class="text-color-dark-orange">Login</a>
						<a href="#" class="text-color-white">Signup</a>
					</nav>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<div class="container">
			<div class="header-inner">
				<div class="site-branding">
					<?php 
					$ct_options = get_option('ct_options');

					$ct_logo = isset($ct_options['ct_logo']) ? $ct_options['ct_logo'] : '';

					if($ct_logo){?>
						<a href="<?php echo esc_url( home_url( '/' ));?>">
							<img src="<?php echo esc_url($ct_logo);?>" alt="<?php echo get_bloginfo('name');?>"/>
						</a>
					<?php } 
					else{?>
						<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php } ?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'ct-custom' ); ?></button>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'menu-1',
						'menu_id'        => 'primary-menu',
					) );
					?>
				</nav><!-- #site-navigation -->
			</div>
		</div>
		
	</header><!-- #masthead -->

	<div id="content" class="site-content">
