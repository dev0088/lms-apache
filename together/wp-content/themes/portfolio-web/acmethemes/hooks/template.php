<?php
if ( ! function_exists( 'portfolio_web_posts_navigation' ) ) :
	/**
	 * Post navigation
	 *
	 * @since Portfolio Web 1.0.0
	 *
	 * @return void
	 */
	function portfolio_web_posts_navigation() {
		global $portfolio_web_customizer_all_values;
		$portfolio_web_pagination_option = $portfolio_web_customizer_all_values['portfolio-web-pagination-option'];
		if( 'default' == $portfolio_web_pagination_option ){
			// Previous/next page navigation.
			the_posts_navigation();
		}
		else {
			// Previous/next page navigation.
			the_posts_pagination();
		}
	}
endif;
add_action( 'portfolio_web_action_posts_navigation', 'portfolio_web_posts_navigation' );

/**
 * Feature Options
 *
 * @since Medical Circle 1.0.0
 *
 * @param null
 * @return string
 *
 */
if ( !function_exists('portfolio_web_featured_image_display') ) :
	function portfolio_web_featured_image_display( ) {
		global $portfolio_web_customizer_all_values;
		$portfolio_web_single_image_layout = $portfolio_web_customizer_all_values['portfolio-web-single-img-size'];

		return $portfolio_web_single_image_layout;
	}
endif;