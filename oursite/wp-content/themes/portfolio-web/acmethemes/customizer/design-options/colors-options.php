<?php
/*customizing default colors section and adding new controls-setting too*/
$wp_customize->get_section( 'colors' )->panel = 'portfolio-web-design-panel';
$wp_customize->get_section( 'colors' )->title = esc_html__( 'Basic Color', 'portfolio-web' );
$wp_customize->get_section( 'background_image' )->priority = 100;

/*Primary color*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-primary-color]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-primary-color'],
    'sanitize_callback' => 'sanitize_hex_color'
) );

$wp_customize->add_control(
    new WP_Customize_Color_Control(
        $wp_customize,
        'portfolio_web_theme_options[portfolio-web-primary-color]',
        array(
            'label'		=> esc_html__( 'Primary Color', 'portfolio-web' ),
            'section'   => 'colors',
            'settings'  => 'portfolio_web_theme_options[portfolio-web-primary-color]',
            'type'	  	=> 'color'
        ) )
);

/*Header TOP color*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-header-top-bg-color]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-header-top-bg-color'],
    'sanitize_callback' => 'sanitize_hex_color'
) );

$wp_customize->add_control(
    new WP_Customize_Color_Control(
        $wp_customize,
        'portfolio_web_theme_options[portfolio-web-header-top-bg-color]',
        array(
            'label'		=> esc_html__( 'Header Top Background Color', 'portfolio-web' ),
            'description'=> esc_html__( 'Also used as secondary color', 'portfolio-web' ),
            'section'   => 'colors',
            'settings'  => 'portfolio_web_theme_options[portfolio-web-header-top-bg-color]',
            'type'	  	=> 'color'
        )
    )
);

/* Footer Background Color*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-footer-bg-color]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-footer-bg-color'],
    'sanitize_callback' => 'sanitize_hex_color'
) );

$wp_customize->add_control(
    new WP_Customize_Color_Control(
        $wp_customize,
        'portfolio_web_theme_options[portfolio-web-footer-bg-color]',
        array(
            'label'		=> esc_html__( 'Footer Background Color', 'portfolio-web' ),
            'section'   => 'colors',
            'settings'  => 'portfolio_web_theme_options[portfolio-web-footer-bg-color]',
            'type'	  	=> 'color'
        )
    )
);

/*Footer Bottom Background Color*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-footer-bottom-bg-color]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-footer-bottom-bg-color'],
    'sanitize_callback' => 'sanitize_hex_color'
) );

$wp_customize->add_control(
    new WP_Customize_Color_Control(
        $wp_customize,
        'portfolio_web_theme_options[portfolio-web-footer-bottom-bg-color]',
        array(
            'label'		=> esc_html__( 'Footer Bottom Background Color', 'portfolio-web' ),
            'section'   => 'colors',
            'settings'  => 'portfolio_web_theme_options[portfolio-web-footer-bottom-bg-color]',
            'type'	  	=> 'color'
        )
    )
);

/*Link color*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-link-color]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-link-color'],
	'sanitize_callback' => 'sanitize_hex_color'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-link-color]', array(
	'label'		=> esc_html__( 'Link Color', 'portfolio-web' ),
	'section'   => 'colors',
	'settings'  => 'portfolio_web_theme_options[portfolio-web-link-color]',
	'type'	  	=> 'color'
) );

/*Link Hover color*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-link-hover-color]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-link-hover-color'],
	'sanitize_callback' => 'sanitize_hex_color'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-link-hover-color]', array(
	'label'		=> esc_html__( 'Link Hover Color', 'portfolio-web' ),
	'section'   => 'colors',
	'settings'  => 'portfolio_web_theme_options[portfolio-web-link-hover-color]',
	'type'	  	=> 'color'
) );