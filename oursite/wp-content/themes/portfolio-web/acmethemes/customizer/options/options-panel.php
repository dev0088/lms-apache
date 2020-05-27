<?php
/*adding theme options panel*/
$wp_customize->add_panel( 'portfolio-web-options', array(
    'priority'       => 90,
    'capability'     => 'edit_theme_options',
    'title'          => esc_html__( 'Theme Options', 'portfolio-web' ),
    'description'    => esc_html__( 'Customize your awesome site with theme options ', 'portfolio-web' )
) );

/*
* file for header breadcrumb options
*/
require portfolio_web_file_directory('acmethemes/customizer/options/breadcrumb.php');

/*
* file for header search options
*/
require portfolio_web_file_directory('acmethemes/customizer/options/search.php');

/*
* file for social options
*/
require portfolio_web_file_directory('acmethemes/customizer/options/social-options.php');