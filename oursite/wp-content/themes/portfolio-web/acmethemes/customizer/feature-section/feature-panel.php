<?php
/*adding feature options panel*/
$wp_customize->add_panel( 'portfolio-web-feature-panel', array(
    'priority'       => 40,
    'capability'     => 'edit_theme_options',
    'title'          => esc_html__( 'Featured Section Options', 'portfolio-web' ),
    'description'    => esc_html__( 'Customize your awesome site feature section ', 'portfolio-web' )
) );

/*
* file for feature section enable
*/
require portfolio_web_file_directory('acmethemes/customizer/feature-section/feature-enable.php');

/*
* file for feature slider category
*/
require portfolio_web_file_directory('acmethemes/customizer/feature-section/feature-slider.php');