<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$container = get_theme_mod( 'understrap_container_type' );
?>

<?php get_template_part( 'sidebar-templates/sidebar', 'footerfull' ); ?>



<footer class=" dark-bg" >
    <div class="content">
        <div class="container">
                        <div class="row">
                <div class="col-md-6 footer-left footer-left">
                      <img src="https://www.freecarcheck.co.uk/wp-content/themes/understrap/img/logo.png" alt=""></br></br>
<p class="footer-text-light">Tofu post-ironic disrupt, vexillologist 90&#39;s prism williamsburg vegan small batch selvage taxidermy cray ethical adaptogen. Forage pour-over beard trust fund whatever drinking vinegar man braid. Hell of +1 post-ironic disrupt, art party freegan 8-bit air plant vice polaroid put a bird on it DIY tousled af live-edge.</p>                </div>
                                <div class="col-md-3">
                    <h5>Company</h5></br>
<p><a href="#">About Us</a></p>
<p><a href="#">Services</a></p>
<p><a href="#">Team</a></p>
<p><a href="#">Developers</a></p>
<p><a href="#">Brand Kit</a></p>                                    </div>
                                <div class="col-md-3 ">
                    <h5>Contact</h5></br>
<p><a href="#">1.800.646.3517</a></p>
<p><a href="#">Contact Us</a></p>
<p><a href="#">Support Center</a></p>
<p><a href="#">Become a Partner</a></p><span class="space"></span><span class="space"></span>                </div>
            </div>
                                </div>
            </div>
</footer>




<div class="wrapper" id="wrapper-footer">

	<div class="<?php echo esc_attr( $container ); ?>">

		<div class="row">

			<div class="col-md-12">

				<footer class="site-footer" id="colophon">

					<div class="site-info small">

						<p class="text-black-50"><a href="/" class="text-black-50">Free Car Check</a> is a trading name of Uffiliate Ltd. &copy; <?php echo date("Y"); ?> All Rights Reserved.  Registered in England. Company no. 08448494.<br>
 Registered office address: Platt Barn, Bullen Farm, Tonbridge, Kent, TN12 5LX.</p>

					</div><!-- .site-info -->

				</footer><!-- #colophon -->

			</div><!--col end -->

		</div><!-- row end -->

	</div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

