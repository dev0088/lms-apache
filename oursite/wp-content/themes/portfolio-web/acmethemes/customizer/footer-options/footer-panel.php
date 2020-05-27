<?php
/*adding footer options panel*/
$wp_customize->add_panel( 'portfolio-web-footer-panel', array(
    'priority'       => 80,
    'capability'     => 'edit_theme_options',
    'title'          => esc_html__( 'Footer Options', 'portfolio-web' ),
    'description'    => esc_html__( 'Customize your awesome site footer ', 'portfolio-web' )
) );

/*
* file for background image
*/
require portfolio_web_file_directory('acmethemes/customizer/footer-options/footer-bg-img.php');

/*
* file for footer logo options
*/
require portfolio_web_file_directory('acmethemes/customizer/footer-options/footer-copyright.php');