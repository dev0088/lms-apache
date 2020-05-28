<?php
/*ading theme options panel*/
$wp_customize->add_panel( 'portfolio-web-single-post', array(
	'priority'       => 85,
	'capability'     => 'edit_theme_options',
	'title'          => esc_html__( 'Single Post Option', 'portfolio-web' )
) );

/*
* file for entry meta
*/
require_once portfolio_web_file_directory('acmethemes/customizer/single-posts/header-title.php');

/*
* file for feature-image
*/
require_once portfolio_web_file_directory('acmethemes/customizer/single-posts/feature-image.php');