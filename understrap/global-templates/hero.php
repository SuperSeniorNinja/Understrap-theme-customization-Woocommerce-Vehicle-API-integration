<?php
/**
 * Hero setup.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>






<section class="jumbotron section-item mb-5 pb-5 section-bg-image"
        style="background-image: url(https://www.freecarcheck.co.uk/wp-content/themes/understrap/img/home-bg.png); background-repeat: no-repeat; background-position: center bottom;">
    <div class="content container">
		<div class="row">
			<div class="hc_column_cnt col-md-12">
				<div class="row">
					<div class="col-md-12 hc_title_tag_cnt">
						<h1 class="text-center animated fadeInDown">Get a Free Vehicle Check</h1>
					</div>

					<div class="col-md-12 hc_text_block_cnt">
						<div class="text-center small"><i class="fal fa-check-circle"></i> Reports available in seconds - no waiting around</div>
					</div>
				</div>
			</div>
	<div class="col-md-12 pt-5 mb-5 pb-5 mt-5">
		
			<div class="d-flex row text-center mb-3">
			<form action="#" class="form-box form-ajax form-ajax-wp form-inline mx-auto" method="post">
	
				<div class="col-md-6">
				<input id="vrn" name="Enter your vehicle reg" placeholder="YOUR REG" type="" class="form-control form-value">
				</div>
				<div class="col-md-6">
				<button class="btn btn-primary pull-right" type="submit">Get my report</button>
				</div>
		
	</form>
	</div>
			<div class="col-md-12">
				<div class="small text-center text-black-50">Looking for multiple vehicle reports? <a href="#" class="text-black-50">Contact us</a></div>
		</div>
		
		
		
		<div class="row bg-secondary w-75 mx-auto mt-5 p-3 text-center align-items-center rounded">	
			
				<div class="col-md-4">
					<img src="/wp-content/themes/understrap/img/trustpilot-logo-home.png" alt="Trustpilot Logo" style="margin-top:-3px;" class="img-fluid w-50 mx-auto">
				</div>
					
					<div class="col-md-4">
						<span class="text-black-50">Rated 4.8/5 on Trustpilot</span>
					</div>
				<div class="col-md-4">
					<img src="https://dot2vpz12e90n.cloudfront.net/images/mazuma/18/home/rebrand/rb-trustpilot-home-banner-stars.png" alt="Trustpilot 5 Stars" class="stars w-50 mx-auto">
				</div>
			
		</div>
		
		
	<div class="col-md-12"><hr class="space w-50 mb-5 pb-5" style="height: 250px" />
	</div>
		</div>


	</div>
</div>
</section>























<?php if ( is_active_sidebar( 'hero' ) || is_active_sidebar( 'statichero' ) || is_active_sidebar( 'herocanvas' ) ) : ?>

	<div class="wrapper" id="wrapper-hero">

		<?php get_template_part( 'sidebar-templates/sidebar', 'hero' ); ?>

		<?php get_template_part( 'sidebar-templates/sidebar', 'herocanvas' ); ?>

		<?php get_template_part( 'sidebar-templates/sidebar', 'statichero' ); ?>

	</div>

<?php endif; ?>
