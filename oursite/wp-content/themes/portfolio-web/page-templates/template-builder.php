<?php
/**
 * Template Name: AT Builder Page
 *
 * @link https://developer.wordpress.org/themes/template-files-section/page-template-files/
 *
 * @package Acme Themes
 * @subpackage Portfolio Web
 */
get_header();
global $portfolio_web_customizer_all_values;
$portfolio_web_template_builder_header_options_meta = get_post_meta( $post->ID, 'portfolio_web_template_builder_header_options', true );

if(
	'hide-header' != $portfolio_web_template_builder_header_options_meta
){
	?>
	<div class="wrapper inner-main-title">
		<?php
		echo portfolio_web_get_header_normal_image();
		?>
		<div class="container">
			<header class="entry-header init-animate">
				<?php
				the_title( '<h1 class="entry-title">', '</h1>' );

				portfolio_web_breadcrumbs();
				?>
			</header><!-- .entry-header -->
		</div>
	</div>
	<?php
}

while ( have_posts() ) : the_post();

    the_content();

endwhile; // End of the loop.

get_footer();