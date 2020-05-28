<?php
/*adding sections for feature image selection*/
$wp_customize->add_section( 'portfolio-web-single-feature-image', array(
    'capability'     => 'edit_theme_options',
    'title'          => esc_html__( 'Feature Image Option', 'portfolio-web' ),
    'panel'          => 'portfolio-web-single-post'
) );

/*single image size*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-single-img-size]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-single-img-size'],
	'sanitize_callback' => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_get_image_sizes_options(1);
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-single-img-size]', array(
	'choices'  	=> $choices,
	'label'		=> esc_html__( 'Image Size', 'portfolio-web' ),
	'section'   => 'portfolio-web-single-feature-image',
	'settings'  => 'portfolio_web_theme_options[portfolio-web-single-img-size]',
	'type'	  	=> 'select'
) );