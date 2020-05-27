<?php
/*check for portfolio-web-menu-right-button-options*/
if ( !function_exists('portfolio_web_menu_right_button_if_not_disable') ) :
	function portfolio_web_menu_right_button_if_not_disable() {
		$portfolio_web_customizer_all_values = portfolio_web_get_theme_options();
		if( 'disable' != $portfolio_web_customizer_all_values['portfolio-web-menu-right-button-options'] ){
			return true;
		}
		return false;
	}
endif;

if ( !function_exists('portfolio_web_menu_right_button_if_booking') ) :
	function portfolio_web_menu_right_button_if_booking() {
		$portfolio_web_customizer_all_values = portfolio_web_get_theme_options();
		if( 'booking' == $portfolio_web_customizer_all_values['portfolio-web-menu-right-button-options'] ){
			return true;
		}
		return false;
	}
endif;

if ( !function_exists('portfolio_web_menu_right_button_if_link') ) :
	function portfolio_web_menu_right_button_if_link() {
		$portfolio_web_customizer_all_values = portfolio_web_get_theme_options();
		if( 'link' == $portfolio_web_customizer_all_values['portfolio-web-menu-right-button-options'] ){
			return true;
		}
		return false;
	}
endif;

/*Button Link Options*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-menu-display-options]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-menu-display-options'],
	'sanitize_callback' => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_menu_display_options();
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-menu-display-options]', array(
	'choices'  	    => $choices,
	'label'		    => esc_html__( 'Menu Display Options', 'portfolio-web' ),
	'section'       => 'portfolio-web-menu-options',
	'settings'      => 'portfolio_web_theme_options[portfolio-web-menu-display-options]',
	'type'	  	    => 'select'
) );

/*Menu Section*/
$wp_customize->add_section( 'portfolio-web-menu-options', array(
    'priority'       => 20,
    'capability'     => 'edit_theme_options',
    'title'          => esc_html__( 'Menu Options', 'portfolio-web' ),
    'panel'          => 'portfolio-web-header-panel'
) );

/*enable sticky*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-enable-sticky]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-enable-sticky'],
    'sanitize_callback' => 'portfolio_web_sanitize_checkbox'
) );

$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-enable-sticky]', array(
    'label'		=> esc_html__( 'Enable Sticky Menu', 'portfolio-web' ),
    'section'   => 'portfolio-web-menu-options',
    'settings'  => 'portfolio_web_theme_options[portfolio-web-enable-sticky]',
    'type'	  	=> 'checkbox'
) );

if( portfolio_web_is_woocommerce_active() ){
	/*enable cart*/
	$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-enable-cart-icon]', array(
		'capability'		=> 'edit_theme_options',
		'default'			=> $defaults['portfolio-web-enable-cart-icon'],
		'sanitize_callback' => 'portfolio_web_sanitize_checkbox'
	) );

	$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-enable-cart-icon]', array(
		'label'		=> esc_html__( 'Enable Cart', 'portfolio-web' ),
		'section'   => 'portfolio-web-menu-options',
		'settings'  => 'portfolio_web_theme_options[portfolio-web-enable-cart-icon]',
		'type'	  	=> 'checkbox'
	) );
}

/*Button Right Message*/
$wp_customize->add_setting('portfolio_web_theme_options[portfolio-web-menu-right-button-message]', array(
	'capability'		=> 'edit_theme_options',
	'sanitize_callback' => 'wp_kses_post'
));

$wp_customize->add_control(
	new Portfolio_Web_Customize_Message_Control(
		$wp_customize,
		'portfolio_web_theme_options[portfolio-web-menu-right-button-message]',
		array(
			'section'       => 'portfolio-web-menu-options',
			'description'   => "<hr /><h2>".esc_html__('Special Button On Menu Right','portfolio-web')."</h2>",
			'settings'      => 'portfolio_web_theme_options[portfolio-web-menu-right-button-message]',
			'type'	  	    => 'message'
		)
	)
);

/*Button Link Options*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-menu-right-button-options]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-menu-right-button-options'],
	'sanitize_callback' => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_menu_right_button_link_options();
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-menu-right-button-options]', array(
	'choices'  	    => $choices,
	'label'		    => esc_html__( 'Button Options', 'portfolio-web' ),
	'section'       => 'portfolio-web-menu-options',
	'settings'      => 'portfolio_web_theme_options[portfolio-web-menu-right-button-options]',
	'type'	  	    => 'select'
) );

/*Button title*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-menu-right-button-title]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-menu-right-button-title'],
	'sanitize_callback' => 'sanitize_text_field'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-menu-right-button-title]', array(
	'label'		        => esc_html__( 'Button Title', 'portfolio-web' ),
	'section'           => 'portfolio-web-menu-options',
	'settings'          => 'portfolio_web_theme_options[portfolio-web-menu-right-button-title]',
	'type'	  	        => 'text',
	'active_callback'   => 'portfolio_web_menu_right_button_if_not_disable'
) );

/*Button Right booking Message*/
$wp_customize->add_setting('portfolio_web_theme_options[portfolio-web-menu-right-button-booking-message]', array(
	'capability'		=> 'edit_theme_options',
	'sanitize_callback' => 'wp_kses_post'
));
$description = sprintf( esc_html__( 'Add Popup Widget from %1$s here%2$s ', 'portfolio-web' ), '<a class="at-customizer" data-section="sidebar-widgets-popup-widget-area" style="cursor: pointer">','</a>' );
$wp_customize->add_control(
	new Portfolio_Web_Customize_Message_Control(
		$wp_customize,
		'portfolio_web_theme_options[portfolio-web-menu-right-button-booking-message]',
		array(
			'section'           => 'portfolio-web-menu-options',
			'description'       => $description,
			'settings'          => 'portfolio_web_theme_options[portfolio-web-menu-right-button-booking-message]',
			'type'	  	        => 'message',
			'active_callback'   => 'portfolio_web_menu_right_button_if_booking'
		)
	)
);

/*Button link*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-menu-right-button-link]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-menu-right-button-link'],
	'sanitize_callback' => 'esc_url_raw'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-menu-right-button-link]', array(
	'label'		        => esc_html__( 'Button Link', 'portfolio-web' ),
	'section'           => 'portfolio-web-menu-options',
	'settings'          => 'portfolio_web_theme_options[portfolio-web-menu-right-button-link]',
	'type'	  	        => 'url',
	'active_callback'   => 'portfolio_web_menu_right_button_if_link'
) );