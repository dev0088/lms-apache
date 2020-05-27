<?php
/*Title*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-popup-widget-title]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-popup-widget-title'],
	'sanitize_callback' => 'sanitize_text_field'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-popup-widget-title]', array(
	'label'		        => esc_html__( 'Main Title', 'portfolio-web' ),
	'section'           => 'portfolio-web-menu-options',
	'settings'          => 'portfolio_web_theme_options[portfolio-web-popup-widget-title]',
	'type'	  	        => 'text',
	'priority'	  	    => 1,
) );
$portfolio_web_popup_widget_area = $wp_customize->get_section( 'sidebar-widgets-popup-widget-area' );
if ( ! empty( $portfolio_web_popup_widget_area ) ) {
	$portfolio_web_popup_widget_area->panel = 'portfolio-web-header-panel';
	$portfolio_web_popup_widget_area->title = esc_html__( 'Popup Widgets', 'portfolio-web' );
	$portfolio_web_popup_widget_area->priority = 999;

	$portfolio_web_popup_widget_title = $wp_customize->get_control( 'portfolio_web_theme_options[portfolio-web-popup-widget-title]' );
	if ( ! empty( $portfolio_web_popup_widget_title ) ) {
		$portfolio_web_popup_widget_title->section  = 'sidebar-widgets-popup-widget-area';
		$portfolio_web_popup_widget_title->priority = -1;
	}
}