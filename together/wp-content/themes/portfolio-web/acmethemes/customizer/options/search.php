<?php
/*adding sections for Search Placeholder*/
$wp_customize->add_section( 'portfolio-web-search', array(
    'priority'          => 20,
    'capability'        => 'edit_theme_options',
    'title'             => esc_html__( 'Search', 'portfolio-web' ),
    'panel'             => 'portfolio-web-options'
) );

/*Search Placeholder*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-search-placeholder]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-search-placeholder'],
    'sanitize_callback' => 'sanitize_text_field'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-search-placeholder]', array(
    'label'		        => esc_html__( 'Search Placeholder', 'portfolio-web' ),
    'section'           => 'portfolio-web-search',
    'settings'          => 'portfolio_web_theme_options[portfolio-web-search-placeholder]',
    'type'	  	        => 'text'
) );