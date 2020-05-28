<?php
/*active callback function for front-page-header*/
if ( !function_exists('portfolio_web_active_callback_front_page_header') ) :
    function portfolio_web_active_callback_front_page_header() {
        $portfolio_web_customizer_all_values = portfolio_web_get_theme_options();
        if( 1 != $portfolio_web_customizer_all_values['portfolio-web-hide-front-page-content'] ){
            return true;
        }
        return false;
    }
endif;

/*adding sections for front page content */
$wp_customize->add_section( 'portfolio-web-front-page-content', array(
    'priority'          => 10,
    'capability'        => 'edit_theme_options',
    'title'             => esc_html__( 'Front Page Content Options', 'portfolio-web' ),
    'panel'             => 'portfolio-web-design-panel'

) );

/*hide front page content*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-hide-front-page-content]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-hide-front-page-content'],
    'sanitize_callback' => 'portfolio_web_sanitize_checkbox',
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-hide-front-page-content]', array(
    'label'		        => esc_html__( 'Hide Front Page Content', 'portfolio-web' ),
    'description'       => esc_html__( 'You may want to hide front page content and want to show only Feature section and Home Widgets. Check this to hide front page content.', 'portfolio-web' ),
    'section'           => 'portfolio-web-front-page-content',
    'settings'          => 'portfolio_web_theme_options[portfolio-web-hide-front-page-content]',
    'type'	  	        => 'checkbox'
) );

/*hide front page header*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-hide-front-page-header]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-hide-front-page-header'],
    'sanitize_callback' => 'portfolio_web_sanitize_checkbox',
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-hide-front-page-header]', array(
    'label'		        => esc_html__( 'Hide Front Page Header', 'portfolio-web' ),
    'description'       => esc_html__( 'You may want to hide front page header and want to show only Feature section and Home Widgets. Check this to hide front page Header.', 'portfolio-web' ),
    'section'           => 'portfolio-web-front-page-content',
    'settings'          => 'portfolio_web_theme_options[portfolio-web-hide-front-page-header]',
    'type'	  	        => 'checkbox',
    'active_callback'   => 'portfolio_web_active_callback_front_page_header'
) );