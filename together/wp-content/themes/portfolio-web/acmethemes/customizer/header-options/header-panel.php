<?php
/*adding header options panel*/
$wp_customize->add_panel( 'portfolio-web-header-panel', array(
    'priority'       => 30,
    'capability'     => 'edit_theme_options',
    'title'          => esc_html__( 'Header Options', 'portfolio-web' ),
    'description'    => esc_html__( 'Customize your awesome site header ', 'portfolio-web' )
) );

/*
* file for header top options
*/
require portfolio_web_file_directory('acmethemes/customizer/header-options/header-top.php');

/*
* file for feature info
*/
require portfolio_web_file_directory('acmethemes/customizer/header-options/feature-info.php');

/*
* file for header logo options
*/
require portfolio_web_file_directory('acmethemes/customizer/header-options/header-logo.php');

/*
 * file for menu options
*/
require portfolio_web_file_directory('acmethemes/customizer/header-options/menu-options.php');

/*
* file for booking form
*/
require portfolio_web_file_directory('acmethemes/customizer/header-options/popup-widgets.php');

/*adding header image inside this panel*/
$wp_customize->get_section( 'header_image' )->panel = 'portfolio-web-header-panel';
$wp_customize->get_section( 'header_image' )->description = esc_html__( 'Applied to header image of inner pages.', 'portfolio-web' );

/* feature section height*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-header-height]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-header-height'],
    'sanitize_callback' => 'portfolio_web_sanitize_number'
) );

$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-header-height]', array(
    'type'              => 'range',
    'priority'          => 100,
    'section'           => 'header_image',
    'label'		        => esc_html__( 'Inner Page Header Section Height', 'portfolio-web' ),
    'description'       => esc_html__( 'Control the height of Header section. The minimum height is 100px and maximium height is 500px', 'portfolio-web' ),
    'input_attrs'       => array(
        'min'           => 100,
        'max'           => 500,
        'step'          => 1,
        'class'         => 'portfolio-web-header-height',
        'style'         => 'color: #0a0',
    ),
    'active_callback'   => 'portfolio_web_if_header_bg_image'
) );

/*Header Image Display*/
$choices = portfolio_web_header_image_display();
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-header-image-display]', array(
	'capability'		        => 'edit_theme_options',
	'default'			        => $defaults['portfolio-web-header-image-display'],
	'sanitize_callback'         => 'portfolio_web_sanitize_select'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-header-image-display]', array(
	'choices'  	                => $choices,
	'priority'                  => 1,
	'label'		                => esc_html__( 'Header Image Display', 'portfolio-web' ),
	'section'                   => 'header_image',
	'settings'                  => 'portfolio_web_theme_options[portfolio-web-header-image-display]',
	'type'	  	                => 'select'
) );

/*check if header bg*/
if ( !function_exists('portfolio_web_if_header_bg_image') ) :
	function portfolio_web_if_header_bg_image() {
		$portfolio_web_customizer_all_values = portfolio_web_get_theme_options();
		if( 'bg-image' == $portfolio_web_customizer_all_values['portfolio-web-header-image-display'] ){
			return true;
		}
		return false;
	}
endif;