<?php
/*adding sections for sidebar options */
$wp_customize->add_section( 'portfolio-web-wc-single-product-options', array(
	'priority'       => 20,
	'capability'     => 'edit_theme_options',
	'title'          => esc_html__( 'Single Product', 'portfolio-web' ),
	'panel'          => 'portfolio-web-wc-panel'
) );

/*Sidebar Layout*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-wc-single-product-sidebar-layout]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-wc-single-product-sidebar-layout'],
	'sanitize_callback' => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_sidebar_layout();
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-wc-single-product-sidebar-layout]', array(
	'choices'  	=> $choices,
	'label'		=> esc_html__( 'Single Product Sidebar Layout', 'portfolio-web' ),
	'section'   => 'portfolio-web-wc-single-product-options',
	'settings'  => 'portfolio_web_theme_options[portfolio-web-wc-single-product-sidebar-layout]',
	'type'	  	=> 'select'
) );