<?php
/*adding sections for category section in front page*/
$wp_customize->add_section( 'portfolio-web-feature-page', array(
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'title'          => esc_html__( 'Feature Slider Selection', 'portfolio-web' ),
    'panel'          => 'portfolio-web-feature-panel'
) );

/* feature parent all-slides selection */
$slider_pages = array();
$slider_pages_obj = get_pages();
$slider_pages[''] = esc_html__('Select Slider Page','portfolio-web');
foreach ($slider_pages_obj as $page) {
	$slider_pages[$page->ID] = $page->post_title;
}
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-slides-data]', array(
	'sanitize_callback' => 'portfolio_web_sanitize_slider_data',
	'default'           => $defaults['portfolio-web-slides-data']
) );
$wp_customize->add_control(
	new Portfolio_Web_Repeater_Control(
		$wp_customize,
		'portfolio_web_theme_options[portfolio-web-slides-data]',
		array(
			'label'                         => esc_html__('Slider Selection','portfolio-web'),
			'description'                   => esc_html__('Select Page For Slider','portfolio-web'),
			'section'                       => 'portfolio-web-feature-page',
			'settings'                      => 'portfolio_web_theme_options[portfolio-web-slides-data]',
			'repeater_main_label'           => esc_html__('Select Slide of Slider','portfolio-web'),
			'repeater_add_control_field'    => esc_html__('Add New Slide','portfolio-web'),
		),
		array(
			'selectpage' => array(
				'type'        => 'select',
				'label'       => esc_html__( 'Select Page For Slide', 'portfolio-web' ),
				'options'     => $slider_pages
			),
			'button_1_text' => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Button One Text', 'portfolio-web' ),
			),
			'button_1_link' => array(
				'type'        => 'url',
				'label'       => esc_html__( 'Button One Link', 'portfolio-web' ),
			),
			'button_2_text' => array(
				'type'        => 'text',
				'label'       => esc_html__( 'Button Two Text', 'portfolio-web' ),
			),
			'button_2_link' => array(
				'type'        => 'url',
				'label'       => esc_html__( 'Button Two Link', 'portfolio-web' ),
			)
		)
	)
);

/*enable animation*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-feature-slider-enable-animation]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-feature-slider-enable-animation'],
    'sanitize_callback' => 'portfolio_web_sanitize_checkbox'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-feature-slider-enable-animation]', array(
    'label'		        => esc_html__( 'Enable Animation', 'portfolio-web' ),
    'section'           => 'portfolio-web-feature-page',
    'settings'          => 'portfolio_web_theme_options[portfolio-web-feature-slider-enable-animation]',
    'type'	  	        => 'checkbox',
) );

/*display-title*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-feature-slider-display-title]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-feature-slider-display-title'],
    'sanitize_callback' => 'portfolio_web_sanitize_checkbox'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-feature-slider-display-title]', array(
    'label'		            => esc_html__( 'Display Title', 'portfolio-web' ),
    'section'               => 'portfolio-web-feature-page',
    'settings'              => 'portfolio_web_theme_options[portfolio-web-feature-slider-display-title]',
    'type'	  	            => 'checkbox'
) );

/*display-excerpt*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-feature-slider-display-excerpt]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-feature-slider-display-excerpt'],
	'sanitize_callback'     => 'portfolio_web_sanitize_checkbox'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-feature-slider-display-excerpt]', array(
	'label'		            => esc_html__( 'Display Excerpt', 'portfolio-web' ),
	'section'               => 'portfolio-web-feature-page',
	'settings'              => 'portfolio_web_theme_options[portfolio-web-feature-slider-display-excerpt]',
	'type'	  	            => 'checkbox'
) );

/*Image Display Behavior*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-fs-image-display-options]', array(
    'capability'		    => 'edit_theme_options',
    'default'			    => $defaults['portfolio-web-fs-image-display-options'],
    'sanitize_callback'     => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_fs_image_display_options();
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-fs-image-display-options]', array(
    'choices'  	            => $choices,
    'label'		            => esc_html__( 'Feature Slider Image Display Options', 'portfolio-web' ),
    'section'               => 'portfolio-web-feature-page',
    'settings'              => 'portfolio_web_theme_options[portfolio-web-fs-image-display-options]',
    'type'	  	            => 'radio'
) );

/*Slider Selection Text Align*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-feature-slider-text-align]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-feature-slider-text-align'],
	'sanitize_callback'     => 'portfolio_web_sanitize_select',
) );
$choices = portfolio_web_slider_text_align();
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-feature-slider-text-align]', array(
	'choices'  	            => $choices,
	'label'		            => esc_html__( 'Slider Text Align', 'portfolio-web' ),
	'section'               => 'portfolio-web-feature-page',
	'settings'              => 'portfolio_web_theme_options[portfolio-web-feature-slider-text-align]',
	'type'	  	            => 'select'
) );

/*Slider Scroll Text*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-slider-scroll-text]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-slider-scroll-text'],
	'sanitize_callback' => 'sanitize_text_field',
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-slider-scroll-text]', array(
	'label'		    => esc_html__( 'Slider Scroll Text', 'portfolio-web' ),
	'section'       => 'portfolio-web-feature-page',
	'settings'      => 'portfolio_web_theme_options[portfolio-web-slider-scroll-text]',
	'type'	  	    => 'text'
) );

/*Slider Scroll Link*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-slider-scroll-link]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-slider-scroll-link'],
	'sanitize_callback' => 'esc_url_raw',
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-slider-scroll-link]', array(
	'label'		    => esc_html__( 'Slider Scroll Link', 'portfolio-web' ),
	'section'       => 'portfolio-web-feature-page',
	'settings'      => 'portfolio_web_theme_options[portfolio-web-slider-scroll-link]',
	'type'	  	    => 'url'
) );