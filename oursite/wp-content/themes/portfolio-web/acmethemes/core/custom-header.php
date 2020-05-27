<?php
/**
 * Set up the WordPress core custom header feature.
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Acme Themes
 * @subpackage Portfolio Web
 * @return void
 */
function portfolio_web_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'portfolio_web_custom_header_args', array(
		'default-image'      => get_template_directory_uri() . '/assets/img/portfolio-web-inner-banner-1920-600.jpg',
		'width'              => 1920,
		'height'             => 1280,
		'flex-height'        => true,
		'flex-width'         => true,
		'header-text'        => false
	) ) );
	register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/assets/img/portfolio-web-inner-banner-1920-600.jpg',
			'thumbnail_url' => '%s/assets/img/portfolio-web-inner-banner-1920-600.jpg',
			'description'   => esc_html__( 'Default Header Image', 'portfolio-web' ),
		),
	) );
}
add_action( 'after_setup_theme', 'portfolio_web_custom_header_setup' );