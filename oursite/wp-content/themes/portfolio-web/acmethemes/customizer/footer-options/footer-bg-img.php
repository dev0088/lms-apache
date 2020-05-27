<?php
/*adding sections for footer background image*/
$wp_customize->add_section( 'portfolio-web-footer-bg-img', array(
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => esc_html__( 'Footer Background Image', 'portfolio-web' ),
    'panel'          => 'portfolio-web-footer-panel',
) );

/*footer background image*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-footer-bg-img]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-footer-bg-img'],
    'sanitize_callback' => 'esc_url_raw'
) );
$wp_customize->add_control(
    new WP_Customize_Image_Control(
        $wp_customize,
        'portfolio_web_theme_options[portfolio-web-footer-bg-img]',
        array(
            'label'		=> esc_html__( 'Footer Background Image', 'portfolio-web' ),
            'section'   => 'portfolio-web-footer-bg-img',
            'settings'  => 'portfolio_web_theme_options[portfolio-web-footer-bg-img]',
            'type'	  	=> 'image'
        )
    )
);