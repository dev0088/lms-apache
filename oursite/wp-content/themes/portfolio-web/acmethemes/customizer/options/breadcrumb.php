<?php
/*adding sections for breadcrumb */
$wp_customize->add_section( 'portfolio-web-breadcrumb-options', array(
    'priority'          => 20,
    'capability'        => 'edit_theme_options',
    'title'             => esc_html__( 'Breadcrumb Options', 'portfolio-web' ),
    'panel'             => 'portfolio-web-options'
) );

/*show breadcrumb*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-breadcrumb-options]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-breadcrumb-options'],
    'sanitize_callback' => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_breadcrumb_options();

$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-breadcrumb-options]', array(
	'choices'  	        => $choices,
	'label'		        => esc_html__( 'Breadcrumb Options', 'portfolio-web' ),
	'description'		 => sprintf( 'Use any one of the plugin for Breadcrumb. %sBreadcrumb NavXT%s or %sYoast SEO%s', '<a href="https://wordpress.org/plugins/breadcrumb-navxt/" target="_blank">','</a>','<a href="https://wordpress.org/plugins/wordpress-seo/" target="_blank">','</a>' ),
    'section'           => 'portfolio-web-breadcrumb-options',
    'settings'          => 'portfolio_web_theme_options[portfolio-web-breadcrumb-options]',
    'type'	  	        => 'select'
) );