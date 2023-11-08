<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package CT_Custom
 */

?>

<div class="container">
	<?php
		if ( function_exists('yoast_breadcrumb') ) {
			yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
		}else{?>
			<p id="breadcrumbs">
				<a href="<?php bloginfo('url');?>">Home</a>

				<?php if(!is_front_page()){?>
				<a href="<?php echo get_the_permalink();?>"> / <?php echo get_the_title();?></a>
				<?php } ?>
			</p>
	<?php } ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header><!-- .entry-header -->

		<?php ct_custom_post_thumbnail(); ?>

		<div class="entry-content">
			<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ct-custom' ),
				'after'  => '</div>',
			) );
			?>
		</div><!-- .entry-content -->
	</article><!-- #post-<?php the_ID(); ?> -->
</div>
