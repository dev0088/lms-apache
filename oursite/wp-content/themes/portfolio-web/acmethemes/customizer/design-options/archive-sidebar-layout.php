<?php
/*adding sections for default layout options panel*/
$wp_customize->add_section( 'portfolio-web-archive-sidebar-layout', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'title'          => esc_html__( 'Category/Archive Sidebar Layout', 'portfolio-web' ),
    'panel'          => 'portfolio-web-design-panel'
) );

/*Sidebar Layout*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-archive-sidebar-layout]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-archive-sidebar-layout'],
    'sanitize_callback' => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_sidebar_layout();
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-archive-sidebar-layout]', array(
    'choices'  	        => $choices,
    'label'		        => esc_html__( 'Category/Archive Sidebar Layout', 'portfolio-web' ),
    'description'       => esc_html__( 'Sidebar Layout for listing pages like category, author etc', 'portfolio-web' ),
    'section'           => 'portfolio-web-archive-sidebar-layout',
    'settings'          => 'portfolio_web_theme_options[portfolio-web-archive-sidebar-layout]',
    'type'	  	        => 'select'
) );