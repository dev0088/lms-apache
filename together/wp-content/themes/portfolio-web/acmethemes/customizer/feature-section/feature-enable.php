<?php
/*adding sections for enabling feature section in front page*/
$wp_customize->add_section( 'portfolio-web-enable-feature', array(
    'priority'       => 10,
    'capability'     => 'edit_theme_options',
    'title'          => esc_html__( 'Enable Feature Section', 'portfolio-web' ),
    'panel'          => 'portfolio-web-feature-panel'
) );

/*enable feature section*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-enable-feature]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-enable-feature'],
    'sanitize_callback' => 'portfolio_web_sanitize_checkbox'
) );

$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-enable-feature]', array(
    'label'		        => esc_html__( 'Enable Feature Section', 'portfolio-web' ),
    'description'	    => sprintf( esc_html__( 'Feature section will display on front/home page. Feature Section includes Feature Slider and Feature Column.%1$s Note : Please go to %2$s "Static Front Page"%3$s setting, Select "A static page" then "Front page" and "Posts page" to enable it', 'portfolio-web' ), '<br />','<b><a class="at-customizer" data-section="static_front_page"> ','</a></b>' ),
    'section'           => 'portfolio-web-enable-feature',
    'settings'          => 'portfolio_web_theme_options[portfolio-web-enable-feature]',
    'type'	  	        => 'checkbox'
) );