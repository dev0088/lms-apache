<?php
/*adding sections for feature info options */
$wp_customize->add_section( 'portfolio-web-feature-info', array(
	'priority'       => 20,
	'capability'     => 'edit_theme_options',
	'title'          => esc_html__( 'Feature Info', 'portfolio-web' ),
	'panel'          => 'portfolio-web-header-panel'
) );

/* basic info number*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-feature-info-number]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-feature-info-number'],
	'sanitize_callback' => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_feature_info_number();
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-feature-info-number]', array(
	'choices'  	        => $choices,
	'label'		        => esc_html__( 'Basic Info Number Display', 'portfolio-web' ),
	'section'           => 'portfolio-web-feature-info',
	'settings'          => 'portfolio_web_theme_options[portfolio-web-feature-info-number]',
	'type'	  	        => 'select',
) );

/*first info*/
$wp_customize->add_setting('portfolio_web_theme_options[portfolio-web-first-info-message]', array(
	'capability'		=> 'edit_theme_options',
	'sanitize_callback' => 'wp_kses_post'
));

$wp_customize->add_control(
	new Portfolio_Web_Customize_Message_Control(
		$wp_customize,
		'portfolio_web_theme_options[portfolio-web-first-info-message]',
		array(
			'section'      => 'portfolio-web-feature-info',
			'description'  => "<hr /><h2>".esc_html__('First Info','portfolio-web')."</h2>",
			'settings'     => 'portfolio_web_theme_options[portfolio-web-first-info-message]',
			'type'	  	   => 'message',
		)
	)
);
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-first-info-icon]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-first-info-icon'],
	'sanitize_callback'     => 'portfolio_web_sanitize_allowed_html'
) );

$wp_customize->add_control(
	new Portfolio_Web_Customize_Icons_Control(
		$wp_customize,
		'portfolio_web_theme_options[portfolio-web-first-info-icon]',
		array(
			'label'		    => esc_html__( 'Icon', 'portfolio-web' ),
			'section'       => 'portfolio-web-feature-info',
			'settings'      => 'portfolio_web_theme_options[portfolio-web-first-info-icon]',
			'type'	  	    => 'text'
		)
	)
);

$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-first-info-title]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-first-info-title'],
	'sanitize_callback'     => 'portfolio_web_sanitize_allowed_html'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-first-info-title]', array(
	'label'		            => esc_html__( 'Title', 'portfolio-web' ),
	'section'               => 'portfolio-web-feature-info',
	'settings'              => 'portfolio_web_theme_options[portfolio-web-first-info-title]',
	'type'	  	            => 'text'
) );

$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-first-info-desc]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-first-info-desc'],
	'sanitize_callback'     => 'portfolio_web_sanitize_allowed_html'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-first-info-desc]', array(
	'label'		            => esc_html__( 'Very Short Description', 'portfolio-web' ),
	'section'               => 'portfolio-web-feature-info',
	'settings'              => 'portfolio_web_theme_options[portfolio-web-first-info-desc]',
	'type'	  	            => 'text'
) );

/*Second Info*/
$wp_customize->add_setting('portfolio_web_theme_options[portfolio-web-second-info-message]', array(
	'capability'		    => 'edit_theme_options',
	'sanitize_callback'     => 'wp_kses_post'
));

$wp_customize->add_control(
	new Portfolio_Web_Customize_Message_Control(
		$wp_customize,
		'portfolio_web_theme_options[portfolio-web-second-info-message]',
		array(
			'section'       => 'portfolio-web-feature-info',
			'description'   => "<hr /><h2>".esc_html__('Second Info','portfolio-web')."</h2>",
			'settings'      => 'portfolio_web_theme_options[portfolio-web-second-info-message]',
			'type'	  	    => 'message',
		)
	)
);
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-second-info-icon]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-second-info-icon'],
	'sanitize_callback'     => 'portfolio_web_sanitize_allowed_html'
) );
$wp_customize->add_control(
	new Portfolio_Web_Customize_Icons_Control(
		$wp_customize,
		'portfolio_web_theme_options[portfolio-web-second-info-icon]',
		array(
			'label'		    => esc_html__( 'Icon', 'portfolio-web' ),
			'section'       => 'portfolio-web-feature-info',
			'settings'      => 'portfolio_web_theme_options[portfolio-web-second-info-icon]',
			'type'	  	    => 'text'
		)
	)
);

$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-second-info-title]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-second-info-title'],
	'sanitize_callback'     => 'portfolio_web_sanitize_allowed_html'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-second-info-title]', array(
	'label'		            => esc_html__( 'Title', 'portfolio-web' ),
	'section'               => 'portfolio-web-feature-info',
	'settings'              => 'portfolio_web_theme_options[portfolio-web-second-info-title]',
	'type'	  	            => 'text'
) );

$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-second-info-desc]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-second-info-desc'],
	'sanitize_callback'     => 'portfolio_web_sanitize_allowed_html'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-second-info-desc]', array(
	'label'		            => esc_html__( 'Very Short Description', 'portfolio-web' ),
	'section'               => 'portfolio-web-feature-info',
	'settings'              => 'portfolio_web_theme_options[portfolio-web-second-info-desc]',
	'type'	  	            => 'text'
) );

/*third info*/
$wp_customize->add_setting('portfolio_web_theme_options[portfolio-web-third-info-message]', array(
	'capability'		    => 'edit_theme_options',
	'sanitize_callback'     => 'wp_kses_post'
));

$wp_customize->add_control(
	new Portfolio_Web_Customize_Message_Control(
		$wp_customize,
		'portfolio_web_theme_options[portfolio-web-third-info-message]',
		array(
			'section'       => 'portfolio-web-feature-info',
			'description'   => "<hr /><h2>".esc_html__('Third Info','portfolio-web')."</h2>",
			'settings'      => 'portfolio_web_theme_options[portfolio-web-third-info-message]',
			'type'	  	    => 'message',
		)
	)
);
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-third-info-icon]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-third-info-icon'],
	'sanitize_callback'     => 'portfolio_web_sanitize_allowed_html'
) );
$wp_customize->add_control(
	new Portfolio_Web_Customize_Icons_Control(
		$wp_customize,
		'portfolio_web_theme_options[portfolio-web-third-info-icon]',
		array(
			'label'		    => esc_html__( 'Icon', 'portfolio-web' ),
			'section'       => 'portfolio-web-feature-info',
			'settings'      => 'portfolio_web_theme_options[portfolio-web-third-info-icon]',
			'type'	  	    => 'text'
		)
	)
);

$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-third-info-title]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-third-info-title'],
	'sanitize_callback'     => 'portfolio_web_sanitize_allowed_html'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-third-info-title]', array(
	'label'		            => esc_html__( 'Title', 'portfolio-web' ),
	'section'               => 'portfolio-web-feature-info',
	'settings'              => 'portfolio_web_theme_options[portfolio-web-third-info-title]',
	'type'	  	            => 'text'
) );

$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-third-info-desc]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-third-info-desc'],
	'sanitize_callback'     => 'portfolio_web_sanitize_allowed_html'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-third-info-desc]', array(
	'label'		            => esc_html__( 'Very Short Description', 'portfolio-web' ),
	'section'               => 'portfolio-web-feature-info',
	'settings'              => 'portfolio_web_theme_options[portfolio-web-third-info-desc]',
	'type'	  	            => 'text'
) );

/*forth info*/
$wp_customize->add_setting('portfolio_web_theme_options[portfolio-web-forth-info-message]', array(
	'capability'		    => 'edit_theme_options',
	'sanitize_callback'     => 'wp_kses_post'
));

$wp_customize->add_control(
	new Portfolio_Web_Customize_Message_Control(
		$wp_customize,
		'portfolio_web_theme_options[portfolio-web-forth-info-message]',
		array(
			'section'       => 'portfolio-web-feature-info',
			'description'   => "<hr /><h2>".esc_html__('Forth Info','portfolio-web')."</h2>",
			'settings'      => 'portfolio_web_theme_options[portfolio-web-forth-info-message]',
			'type'	  	    => 'message',
		)
	)
);
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-forth-info-icon]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-forth-info-icon'],
	'sanitize_callback'     => 'portfolio_web_sanitize_allowed_html'
) );
$wp_customize->add_control(
	new Portfolio_Web_Customize_Icons_Control(
		$wp_customize,
		'portfolio_web_theme_options[portfolio-web-forth-info-icon]',
		array(
			'label'		    => esc_html__( 'Icon', 'portfolio-web' ),
			'section'       => 'portfolio-web-feature-info',
			'settings'      => 'portfolio_web_theme_options[portfolio-web-forth-info-icon]',
			'type'	  	    => 'text'
		)
	)
);

$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-forth-info-title]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-forth-info-title'],
	'sanitize_callback'     => 'portfolio_web_sanitize_allowed_html'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-forth-info-title]', array(
	'label'		            => esc_html__( 'Title', 'portfolio-web' ),
	'section'               => 'portfolio-web-feature-info',
	'settings'              => 'portfolio_web_theme_options[portfolio-web-forth-info-title]',
	'type'	  	            => 'text'
) );

$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-forth-info-desc]', array(
	'capability'		    => 'edit_theme_options',
	'default'			    => $defaults['portfolio-web-forth-info-desc'],
	'sanitize_callback'     => 'portfolio_web_sanitize_allowed_html'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-forth-info-desc]', array(
	'label'		            => esc_html__( 'Very Short Description', 'portfolio-web' ),
	'section'               => 'portfolio-web-feature-info',
	'settings'              => 'portfolio_web_theme_options[portfolio-web-forth-info-desc]',
	'type'	  	            => 'text'
) );