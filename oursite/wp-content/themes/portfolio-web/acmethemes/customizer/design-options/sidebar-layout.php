<?php
/*adding sections for sidebar options */
$wp_customize->add_section( 'portfolio-web-design-sidebar-layout-option', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'title'          => esc_html__( 'Default Page/Post Sidebar Layout', 'portfolio-web' ),
    'panel'          => 'portfolio-web-design-panel'
) );

/*Sidebar Layout*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-single-sidebar-layout]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-single-sidebar-layout'],
    'sanitize_callback' => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_sidebar_layout();
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-single-sidebar-layout]', array(
    'choices'  	        => $choices,
    'label'		        => esc_html__( 'Default Page/Post Sidebar Layout', 'portfolio-web' ),
    'description'       => esc_html__( 'Single Page/Post Sidebar', 'portfolio-web' ),
    'section'           => 'portfolio-web-design-sidebar-layout-option',
    'settings'          => 'portfolio_web_theme_options[portfolio-web-single-sidebar-layout]',
    'type'	  	        => 'select'
) );