<?php
/**
 * Acme Demo Setup Data
 * Don't confuse they dont need translations
 *
 * @since Portfolio Web 1.0.0
 *
 */

if( !function_exists( 'portfolio_web_demo_nav_data') ){
    function portfolio_web_demo_nav_data(){
        $demo_navs = array(
            'primary'       => 'Primary Menu',
            'footer-menu'   => 'Footer Menu'
        );
        return $demo_navs;
    }
}
add_filter('acme_demo_setup_nav_data','portfolio_web_demo_nav_data');

if( !function_exists( 'portfolio_web_demo_wp_options_data') ){
    function portfolio_web_demo_wp_options_data(){
        $wp_options = array(
            'page_on_front'     => 'Home',
            'page_for_posts'    => 'Blog',
        );
        return $wp_options;
    }
}
add_filter('acme_demo_setup_wp_options_data','portfolio_web_demo_wp_options_data');