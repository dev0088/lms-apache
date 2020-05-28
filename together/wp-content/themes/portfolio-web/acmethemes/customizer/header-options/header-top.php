<?php
/*check if enable header top*/
if ( !function_exists('portfolio_web_is_enable_header_top') ) :
	function portfolio_web_is_enable_header_top() {
		$portfolio_web_customizer_all_values = portfolio_web_get_theme_options();
		if( 1 == $portfolio_web_customizer_all_values['portfolio-web-enable-header-top'] ){
			return true;
		}
		return false;
	}
endif;

/*adding sections for header options*/
$wp_customize->add_section( 'portfolio-web-header-top-option', array(
    'priority'                  => 10,
    'capability'                => 'edit_theme_options',
    'title'                     => esc_html__( 'Header Top', 'portfolio-web' ),
    'panel'                     => 'portfolio-web-header-panel',
) );

/*header top enable*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-enable-header-top]', array(
    'capability'		        => 'edit_theme_options',
    'default'			        => $defaults['portfolio-web-enable-header-top'],
    'sanitize_callback'         => 'portfolio_web_sanitize_checkbox',
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-enable-header-top]', array(
    'label'		                => esc_html__( 'Enable Header Top Options', 'portfolio-web' ),
    'section'                   => 'portfolio-web-header-top-option',
    'settings'                  => 'portfolio_web_theme_options[portfolio-web-enable-header-top]',
    'type'	  	                => 'checkbox'
) );

/*header top message*/
$wp_customize->add_setting('portfolio_web_theme_options[portfolio-web-header-top-message]', array(
	'capability'		        => 'edit_theme_options',
	'sanitize_callback'         => 'wp_kses_post'
));

$wp_customize->add_control(
	new Portfolio_Web_Customize_Message_Control(
		$wp_customize,
		'portfolio_web_theme_options[portfolio-web-header-top-message]',
		array(
			'section'           => 'portfolio-web-header-top-option',
			'description'       => "<hr /><h2>".esc_html__('Display Different Element on Top Right or Left','portfolio-web')."</h2>",
			'settings'          => 'portfolio_web_theme_options[portfolio-web-header-top-message]',
			'type'	  	        => 'message',
			'active_callback'   => 'portfolio_web_is_enable_header_top'
		)
	)
);

/*Top Menu Display*/
$choices = portfolio_web_header_top_display_selection();
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-header-top-menu-display-selection]', array(
	'capability'		        => 'edit_theme_options',
	'default'			        => $defaults['portfolio-web-header-top-menu-display-selection'],
	'sanitize_callback'         => 'portfolio_web_sanitize_select'
) );
$description = sprintf( esc_html__( 'Add/Edit Menu Items from %1$s here%2$s and select Menu Location : Top Menu ( Support First Level Only ) ', 'portfolio-web' ), '<a class="at-customizer button button-primary" data-panel="nav_menus" style="cursor: pointer">','</a>' );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-header-top-menu-display-selection]', array(
	'choices'  	                => $choices,
	'label'		                => esc_html__( 'Top Menu Display', 'portfolio-web' ),
	'description'		        => $description,
	'section'                   => 'portfolio-web-header-top-option',
	'settings'                  => 'portfolio_web_theme_options[portfolio-web-header-top-menu-display-selection]',
	'type'	  	                => 'select',
	'active_callback'           => 'portfolio_web_is_enable_header_top'
) );

/*Top Info display*/
$description = sprintf( esc_html__( 'Add/Edit Info Items from %1$s here%2$s', 'portfolio-web' ), '<a class="at-customizer button button-primary" data-section="portfolio-web-feature-info" style="cursor: pointer">','</a>' );
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-header-top-info-display-selection]', array(
	'capability'		        => 'edit_theme_options',
	'default'			        => $defaults['portfolio-web-header-top-info-display-selection'],
	'sanitize_callback'         => 'portfolio_web_sanitize_select'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-header-top-info-display-selection]', array(
	'choices'  	                => $choices,
	'label'		                => esc_html__( 'Top Info Display', 'portfolio-web' ),
	'description'		        => $description,
	'section'                   => 'portfolio-web-header-top-option',
	'settings'                  => 'portfolio_web_theme_options[portfolio-web-header-top-info-display-selection]',
	'type'	  	                => 'select',
	'active_callback'           => 'portfolio_web_is_enable_header_top'
) );

/*Social Display*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-header-top-social-display-selection]', array(
	'capability'		        => 'edit_theme_options',
	'default'			        => $defaults['portfolio-web-header-top-social-display-selection'],
	'sanitize_callback'         => 'portfolio_web_sanitize_select'
) );
$description = sprintf( esc_html__( 'Add/Edit Social Items from %1$s here%2$s ', 'portfolio-web' ), '<a class="at-customizer button button-primary" data-section="portfolio-web-social-options" style="cursor: pointer">','</a>' );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-header-top-social-display-selection]', array(
	'choices'  	                => $choices,
	'label'		                => esc_html__( 'Social Display', 'portfolio-web' ),
	'description'               => $description,
	'section'                   => 'portfolio-web-header-top-option',
	'settings'                  => 'portfolio_web_theme_options[portfolio-web-header-top-social-display-selection]',
	'type'	  	                => 'select',
	'active_callback'           => 'portfolio_web_is_enable_header_top'
) );