<?php
/*adding sections for footer options*/
$wp_customize->add_section( 'portfolio-web-footer-copyright-option', array(
    'priority'              => 20,
    'capability'            => 'edit_theme_options',
    'title'                 => esc_html__( 'Bottom Copyright Section', 'portfolio-web' ),
    'panel'                 => 'portfolio-web-footer-panel',
) );

/*copyright*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-footer-copyright]', array(
    'capability'		    => 'edit_theme_options',
    'default'			    => $defaults['portfolio-web-footer-copyright'],
    'sanitize_callback'     => 'wp_kses_post'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-footer-copyright]', array(
    'label'		            => esc_html__( 'Copyright Text', 'portfolio-web' ),
    'section'               => 'portfolio-web-footer-copyright-option',
    'settings'              => 'portfolio_web_theme_options[portfolio-web-footer-copyright]',
    'type'	  	            => 'text'
) );

/*footer copyright beside*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-footer-copyright-beside-option]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-footer-copyright-beside-option'],
	'sanitize_callback'     => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_footer_copyright_beside_option();
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-footer-copyright-beside-option]', array(
	'choices'  	            => $choices,
	'label'		            => esc_html__( 'Footer Copyright Beside Option', 'portfolio-web' ),
	'section'               => 'portfolio-web-footer-copyright-option',
	'settings'              => 'portfolio_web_theme_options[portfolio-web-footer-copyright-beside-option]',
	'type'	  	            => 'select'
) );

/*footer got to top enable-disable */
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-enable-footer-power-text]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-enable-footer-power-text'],
	'sanitize_callback' => 'portfolio_web_sanitize_checkbox'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-enable-footer-power-text]', array(
	'label'		=> esc_html__( ' Enable Theme Name And Powered By Text ', 'portfolio-web' ),
	'section'   => 'portfolio-web-footer-copyright-option',
	'settings'  => 'portfolio_web_theme_options[portfolio-web-enable-footer-power-text]',
	'type'	  	=> 'checkbox'
) );