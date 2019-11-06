<?php
/**
 * Template Name: Vrm Form Submit
 *
 * Template for displaying a page without sidebar even if a sidebar widget is published.
 *
 * @package understrap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header();
$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="full-width-page-wrapper">
	<div class="<?php echo esc_attr( $container ); ?>" id="content">
		<div class="row">
			<div class="col-md-12 content-area" id="primary">
				<main class="site-main" id="main" role="main">					
					<form action="ajax.php" method="POST" id="vrm-form">
						<h1>Search for VRM in this search box.</h1>
						<input type="text" name="vrm" class="vrm">
						<input type="submit" value="Click to Check <<" class="vrm_btn">
					</form>
				</main><!-- #main -->
			</div><!-- #primary -->
		</div><!-- .row end -->
	</div><!-- #content -->
</div>

<?php get_footer(); ?>
