<?php
/**
 * Front page hook for all WordPress Conditions
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( ! function_exists( 'portfolio_web_featured_slider' ) ) :

    function portfolio_web_featured_slider() {
        global $portfolio_web_customizer_all_values;

        $portfolio_web_enable_feature = $portfolio_web_customizer_all_values['portfolio-web-enable-feature'];
        if( is_front_page() && 1 == $portfolio_web_enable_feature && !is_home() ) :
	        do_action( 'portfolio_web_action_feature_slider' );
	
        endif;
    }
endif;
add_action( 'portfolio_web_action_front_page', 'portfolio_web_featured_slider', 10 );

if ( ! function_exists( 'portfolio_web_front_page' ) ) :

    function portfolio_web_front_page() {
        global $portfolio_web_customizer_all_values;

        $portfolio_web_hide_front_page_content = $portfolio_web_customizer_all_values['portfolio-web-hide-front-page-content'];

        /*show widget in front page, now user are not force to use front page*/
        if( is_active_sidebar( 'portfolio-web-home' ) && !is_home() ){
            dynamic_sidebar( 'portfolio-web-home' );
        }
        if ( 'posts' == get_option( 'show_on_front' ) ) {
            include( get_home_template() );
        }
        else {
            if( 1 != $portfolio_web_hide_front_page_content ){
                include( get_page_template() );
            }
        }
    }
endif;
add_action( 'portfolio_web_action_front_page', 'portfolio_web_front_page', 20 );