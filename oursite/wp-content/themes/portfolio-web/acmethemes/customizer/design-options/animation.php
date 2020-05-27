<?php
/*adding sections for design options panel*/
$wp_customize->add_section( 'portfolio-web-animation', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'title'          => esc_html__( 'Animation', 'portfolio-web' ),
    'panel'          => 'portfolio-web-design-panel'
) );

/*enable disable animation*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-enable-animation]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-enable-animation'],
    'sanitize_callback' => 'portfolio_web_sanitize_checkbox'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-enable-animation]', array(
    'label'		        => esc_html__( 'Disable animation', 'portfolio-web' ),
    'description'       => esc_html__( 'Check this to disable overall site animation effect provided by theme', 'portfolio-web' ),
    'section'           => 'portfolio-web-animation',
    'settings'          => 'portfolio_web_theme_options[portfolio-web-enable-animation]',
    'type'	  	        => 'checkbox'
) );