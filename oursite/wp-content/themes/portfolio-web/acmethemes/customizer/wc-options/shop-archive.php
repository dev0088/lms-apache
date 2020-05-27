<?php
/*adding sections for sidebar options */
$wp_customize->add_section( 'portfolio-web-wc-shop-archive-option', array(
	'priority'       => 20,
	'capability'     => 'edit_theme_options',
	'title'          => esc_html__( 'Shop Archive Sidebar Layout', 'portfolio-web' ),
	'panel'          => 'portfolio-web-wc-panel'
) );

/*Sidebar Layout*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-wc-shop-archive-sidebar-layout]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-wc-shop-archive-sidebar-layout'],
	'sanitize_callback' => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_sidebar_layout();
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-wc-shop-archive-sidebar-layout]', array(
	'choices'  	=> $choices,
	'label'		=> esc_html__( 'Shop Archive Sidebar Layout', 'portfolio-web' ),
	'section'   => 'portfolio-web-wc-shop-archive-option',
	'settings'  => 'portfolio_web_theme_options[portfolio-web-wc-shop-archive-sidebar-layout]',
	'type'	  	=> 'select'
) );

/*wc-product-column-number*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-wc-product-column-number]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-wc-product-column-number'],
	'sanitize_callback' => 'absint'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-wc-product-column-number]', array(
	'label'		=> esc_html__( 'Products Per Row', 'portfolio-web' ),
	'section'   => 'portfolio-web-wc-shop-archive-option',
	'settings'  => 'portfolio_web_theme_options[portfolio-web-wc-product-column-number]',
	'type'	  	=> 'number'
) );

/*wc-shop-archive-total-product*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-wc-shop-archive-total-product]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-wc-shop-archive-total-product'],
	'sanitize_callback' => 'absint'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-wc-shop-archive-total-product]', array(
	'label'		=> esc_html__( 'Total Products Per Page', 'portfolio-web' ),
	'section'   => 'portfolio-web-wc-shop-archive-option',
	'settings'  => 'portfolio_web_theme_options[portfolio-web-wc-shop-archive-total-product]',
	'type'	  	=> 'number'
) );