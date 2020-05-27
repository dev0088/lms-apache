<?php
/**
 * Select sidebar according to the options saved
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return string
 *
 */
if ( !function_exists('portfolio_web_sidebar_selection') ) :
	function portfolio_web_sidebar_selection( ) {
		wp_reset_postdata();
		$portfolio_web_customizer_all_values = portfolio_web_get_theme_options();
		global $post;
		if(
			isset( $portfolio_web_customizer_all_values['portfolio-web-single-sidebar-layout'] ) &&
			(
				'left-sidebar' == $portfolio_web_customizer_all_values['portfolio-web-single-sidebar-layout'] ||
				'both-sidebar' == $portfolio_web_customizer_all_values['portfolio-web-single-sidebar-layout'] ||
				'middle-col' == $portfolio_web_customizer_all_values['portfolio-web-single-sidebar-layout'] ||
				'no-sidebar' == $portfolio_web_customizer_all_values['portfolio-web-single-sidebar-layout']
			)
		){
			$portfolio_web_body_global_class = $portfolio_web_customizer_all_values['portfolio-web-single-sidebar-layout'];
		}
		else{
			$portfolio_web_body_global_class= 'right-sidebar';
		}

		if ( portfolio_web_is_woocommerce_active() && ( is_product() || is_shop() || is_product_taxonomy() )) {
			if( is_product() ){
				$post_class = get_post_meta( $post->ID, 'portfolio_web_sidebar_layout', true );
				$portfolio_web_wc_single_product_sidebar_layout = $portfolio_web_customizer_all_values['portfolio-web-wc-single-product-sidebar-layout'];

				if ( 'default-sidebar' != $post_class ){
					if ( $post_class ) {
						$portfolio_web_body_classes = $post_class;
					} else {
						$portfolio_web_body_classes = $portfolio_web_wc_single_product_sidebar_layout;
					}
				}
				else{
					$portfolio_web_body_classes = $portfolio_web_wc_single_product_sidebar_layout;

				}
			}
			else{
				if( isset( $portfolio_web_customizer_all_values['portfolio-web-wc-shop-archive-sidebar-layout'] ) ){
					$portfolio_web_archive_sidebar_layout = $portfolio_web_customizer_all_values['portfolio-web-wc-shop-archive-sidebar-layout'];
					if(
						'right-sidebar' == $portfolio_web_archive_sidebar_layout ||
						'left-sidebar' == $portfolio_web_archive_sidebar_layout ||
						'both-sidebar' == $portfolio_web_archive_sidebar_layout ||
						'middle-col' == $portfolio_web_archive_sidebar_layout ||
						'no-sidebar' == $portfolio_web_archive_sidebar_layout
					){
						$portfolio_web_body_classes = $portfolio_web_archive_sidebar_layout;
					}
					else{
						$portfolio_web_body_classes = $portfolio_web_body_global_class;
					}
				}
				else{
					$portfolio_web_body_classes= $portfolio_web_body_global_class;
				}
			}
		}
		elseif( is_front_page() ){
			if( isset( $portfolio_web_customizer_all_values['portfolio-web-front-page-sidebar-layout'] ) ){
				if(
					'right-sidebar' == $portfolio_web_customizer_all_values['portfolio-web-front-page-sidebar-layout'] ||
					'left-sidebar' == $portfolio_web_customizer_all_values['portfolio-web-front-page-sidebar-layout'] ||
					'both-sidebar' == $portfolio_web_customizer_all_values['portfolio-web-front-page-sidebar-layout'] ||
					'middle-col' == $portfolio_web_customizer_all_values['portfolio-web-front-page-sidebar-layout'] ||
					'no-sidebar' == $portfolio_web_customizer_all_values['portfolio-web-front-page-sidebar-layout']
				){
					$portfolio_web_body_classes = $portfolio_web_customizer_all_values['portfolio-web-front-page-sidebar-layout'];
				}
				else{
					$portfolio_web_body_classes = $portfolio_web_body_global_class;
				}
			}
			else{
				$portfolio_web_body_classes= $portfolio_web_body_global_class;
			}
		}

		elseif ( is_singular() && isset( $post->ID ) ) {
			$post_class = get_post_meta( $post->ID, 'portfolio_web_sidebar_layout', true );
			if ( 'default-sidebar' != $post_class ){
				if ( $post_class ) {
					$portfolio_web_body_classes = $post_class;
				} else {
					$portfolio_web_body_classes = $portfolio_web_body_global_class;
				}
			}
			else{
				$portfolio_web_body_classes = $portfolio_web_body_global_class;
			}

		}
		elseif ( is_archive() ) {
			if( isset( $portfolio_web_customizer_all_values['portfolio-web-archive-sidebar-layout'] ) ){
				$portfolio_web_archive_sidebar_layout = $portfolio_web_customizer_all_values['portfolio-web-archive-sidebar-layout'];
				if(
					'right-sidebar' == $portfolio_web_archive_sidebar_layout ||
					'left-sidebar' == $portfolio_web_archive_sidebar_layout ||
					'both-sidebar' == $portfolio_web_archive_sidebar_layout ||
					'middle-col' == $portfolio_web_archive_sidebar_layout ||
					'no-sidebar' == $portfolio_web_archive_sidebar_layout
				){
					$portfolio_web_body_classes = $portfolio_web_archive_sidebar_layout;
				}
				else{
					$portfolio_web_body_classes = $portfolio_web_body_global_class;
				}
			}
			else{
				$portfolio_web_body_classes= $portfolio_web_body_global_class;
			}
		}
		else {
			$portfolio_web_body_classes = $portfolio_web_body_global_class;
		}
		return $portfolio_web_body_classes;
	}
endif;