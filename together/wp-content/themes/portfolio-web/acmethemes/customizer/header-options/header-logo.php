<?php
/*Site Logo*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-display-site-logo]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-display-site-logo'],
	'sanitize_callback' => 'portfolio_web_sanitize_checkbox'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-display-site-logo]', array(
	'label'		=> esc_html__( 'Display Logo', 'portfolio-web' ),
	'section'   => 'title_tagline',
	'settings'  => 'portfolio_web_theme_options[portfolio-web-display-site-logo]',
	'type'	  	=> 'checkbox'
) );

/*Site Title*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-display-site-title]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-display-site-title'],
	'sanitize_callback' => 'portfolio_web_sanitize_checkbox'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-display-site-title]', array(
	'label'		=> esc_html__( 'Display Site Title', 'portfolio-web' ),
	'section'   => 'title_tagline',
	'settings'  => 'portfolio_web_theme_options[portfolio-web-display-site-title]',
	'type'	  	=> 'checkbox'
) );

/*Site Tagline*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-display-site-tagline]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-display-site-tagline'],
	'sanitize_callback' => 'portfolio_web_sanitize_checkbox'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-display-site-tagline]', array(
	'label'		=> esc_html__( 'Display Site Tagline', 'portfolio-web' ),
	'section'   => 'title_tagline',
	'settings'  => 'portfolio_web_theme_options[portfolio-web-display-site-tagline]',
	'type'	  	=> 'checkbox'
) );