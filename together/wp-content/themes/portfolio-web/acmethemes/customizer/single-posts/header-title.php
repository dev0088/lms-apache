<?php
/*adding sections for header title*/
$wp_customize->add_section( 'portfolio-web-single-header-title', array(
	'priority'              => 20,
	'capability'            => 'edit_theme_options',
	'title'                 => esc_html__( 'Single Header Title', 'portfolio-web' ),
	'panel'                 => 'portfolio-web-single-post',
) );

/*header title*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-single-header-title]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-single-header-title'],
	'sanitize_callback'     => 'sanitize_text_field'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-single-header-title]', array(
	'label'		            => esc_html__( 'Header Title', 'portfolio-web' ),
	'section'               => 'portfolio-web-single-header-title',
	'settings'              => 'portfolio_web_theme_options[portfolio-web-single-header-title]',
	'type'	  	            => 'text'
) );