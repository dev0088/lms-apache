<?php
/*adding sections for header social options */
$wp_customize->add_section( 'portfolio-web-social-options', array(
    'priority'              => 20,
    'capability'            => 'edit_theme_options',
    'title'                 => esc_html__( 'Social Options', 'portfolio-web' ),
    'panel'                 => 'portfolio-web-options'
) );

/*repeater social data*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-social-data]', array(
	'sanitize_callback'     => 'portfolio_web_sanitize_social_data',
	'default'               => $defaults['portfolio-web-social-data']
) );
$wp_customize->add_control(
	new Portfolio_Web_Repeater_Control(
		$wp_customize,
		'portfolio_web_theme_options[portfolio-web-social-data]',
		array(
			'label'                         => esc_html__('Social Options Selection','portfolio-web'),
			'description'                   => esc_html__('Select Social Icons and enter link','portfolio-web'),
			'section'                       => 'portfolio-web-social-options',
			'settings'                      => 'portfolio_web_theme_options[portfolio-web-social-data]',
			'repeater_main_label'           => esc_html__('Social Icon','portfolio-web'),
			'repeater_add_control_field'    => esc_html__('Add New Icon','portfolio-web')
		),
		array(
			'icon' => array(
				'type'        => 'icons',
				'label'       => esc_html__( 'Select Icon', 'portfolio-web' ),
			),
			'link' => array(
				'type'        => 'url',
				'label'       => esc_html__( 'Enter Link', 'portfolio-web' ),
			),
			'checkbox' => array(
				'type'        => 'checkbox',
				'label'       => esc_html__( 'Open in New Window', 'portfolio-web' ),
			)
		)
	)
);