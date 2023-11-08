<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package CT_Custom
 */

?>

	</div><!-- #content -->

	<?php 
	$ct_options = get_option('ct_options');
	
	$address = isset($ct_options['ct_address_information']) ? $ct_options['ct_address_information'] : '';
	$phone = isset($ct_options['ct_phone_number']) ? $ct_options['ct_phone_number'] : '';
	$fax = isset($ct_options['ct_fax_number']) ? $ct_options['ct_fax_number'] : '';

	$facebook = isset($ct_options['ct_facebook_url']) ? $ct_options['ct_facebook_url'] : '';
	$twitter = isset($ct_options['ct_twitter_url']) ? $ct_options['ct_twitter_url'] : '';
	$linkedin = isset($ct_options['ct_linkedin_url']) ? $ct_options['ct_linkedin_url'] : '';
	$pinterest = isset($ct_options['ct_pinterest_url']) ? $ct_options['ct_pinterest_url'] : '';
	?>
	<footer id="colophon" class="site-footer">
		<div class="container">
			<div class="footer-inner">
				<div class="footer-form">
					<h3>Contact Us</h3>
					<?php echo do_shortcode('[contact-form-7 id="f51e0f8" title="Contact Form"]'); ?>
				</div>
				<div class="footer-address">
					<h3>React Us</h3>

					<?php if($address){?>
						<p><?php echo wp_kses_post($address);?></p>
					<?php } ?>
					
					<?php if($phone){?>
						<p style="margin-bottom:0;">Phone: <?php echo esc_html($phone);?></p>
					<?php } ?>
					<?php if($fax){?>
						<p style="margin-top: 0;">Fax: <?php echo esc_html($fax);?></p>
					<?php } ?>
					
					<div class="social-media-wrapper">
						<nav>
							<?php if($facebook){?>
								<a href="<?php echo esc_url($facebook);?>"><img src="<?php bloginfo('stylesheet_directory');?>/images/icon_facebook.png" alt="facebook"/></a>
							<?php } ?>
							
							<?php if($twitter){?>
							<a href="<?php echo esc_url($twitter);?>"><img src="<?php bloginfo('stylesheet_directory');?>/images/icon_twitter.png" alt="twitter"/></a>
							<?php } ?>

							<?php if($linkedin){?>
							<a href="<?php echo esc_url($linkedin);?>"><img src="<?php bloginfo('stylesheet_directory');?>/images/icon_linkedin.png" alt="linkedin"/></a>
							<?php } ?>

							<?php if($pinterest){?>
							<a href="<?php echo esc_url($pinterest);?>"><img src="<?php bloginfo('stylesheet_directory');?>/images/icon_pinterest.png" alt="pinterest"/></a>
							<?php } ?>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
