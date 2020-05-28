<?php
/*active callback function for excerpt*/
if ( !function_exists('portfolio_web_active_callback_content_from_excerpt') ) :
	function portfolio_web_active_callback_content_from_excerpt() {
		$portfolio_web_customizer_all_values = portfolio_web_get_theme_options();
		if( 'excerpt' == $portfolio_web_customizer_all_values['portfolio-web-blog-archive-content-from'] ){
			return true;
		}
		return false;
	}
endif;

/*adding sections for blog layout options*/
$wp_customize->add_section( 'portfolio-web-design-blog-layout-option', array(
    'priority'       => 30,
    'capability'     => 'edit_theme_options',
    'title'          => esc_html__( 'Default Blog/Archive Layout', 'portfolio-web' ),
    'panel'          => 'portfolio-web-design-panel'
) );

/*blog layout*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-blog-archive-img-size]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-blog-archive-img-size'],
    'sanitize_callback' => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_get_image_sizes_options(1);
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-blog-archive-img-size]', array(
    'choices'  	    => $choices,
    'label'		    => esc_html__( 'Blog/Archive Feature Image Size', 'portfolio-web' ),
    'section'       => 'portfolio-web-design-blog-layout-option',
    'settings'      => 'portfolio_web_theme_options[portfolio-web-blog-archive-img-size]',
    'type'	  	    => 'select'
) );

/*blog content from*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-blog-archive-content-from]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-blog-archive-content-from'],
	'sanitize_callback' => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_blog_archive_content_from();
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-blog-archive-content-from]', array(
	'choices'  	    => $choices,
	'label'		    => esc_html__( 'Blog/Archive Content From', 'portfolio-web' ),
	'section'       => 'portfolio-web-design-blog-layout-option',
	'settings'      => 'portfolio_web_theme_options[portfolio-web-blog-archive-content-from]',
	'type'	  	    => 'select'
) );

/*Excerpt Length*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-blog-archive-excerpt-length]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-blog-archive-excerpt-length'],
	'sanitize_callback' => 'absint'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-blog-archive-excerpt-length]', array(
	'label'		        => esc_html__( 'Except Length', 'portfolio-web' ),
	'section'           => 'portfolio-web-design-blog-layout-option',
	'settings'          => 'portfolio_web_theme_options[portfolio-web-blog-archive-excerpt-length]',
	'type'	  	        => 'number',
	'active_callback'   => 'portfolio_web_active_callback_content_from_excerpt'
) );

/*Read More Text*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-blog-archive-more-text]', array(
    'capability'		=> 'edit_theme_options',
    'default'			=> $defaults['portfolio-web-blog-archive-more-text'],
    'sanitize_callback' => 'sanitize_text_field'
) );
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-blog-archive-more-text]', array(
    'label'		=> esc_html__( 'Read More Text', 'portfolio-web' ),
    'section'   => 'portfolio-web-design-blog-layout-option',
    'settings'  => 'portfolio_web_theme_options[portfolio-web-blog-archive-more-text]',
    'type'	  	=> 'text'
) );

/*Pagination Options*/
$wp_customize->add_setting( 'portfolio_web_theme_options[portfolio-web-pagination-option]', array(
	'capability'		=> 'edit_theme_options',
	'default'			=> $defaults['portfolio-web-pagination-option'],
	'sanitize_callback' => 'portfolio_web_sanitize_select'
) );
$choices = portfolio_web_pagination_options();
$wp_customize->add_control( 'portfolio_web_theme_options[portfolio-web-pagination-option]', array(
	'choices'  	    => $choices,
	'label'		    => esc_html__( 'Pagination Options', 'portfolio-web' ),
	'description'   => esc_html__( 'Blog and Archive Pages Pagination', 'portfolio-web' ),
	'section'       => 'portfolio-web-design-blog-layout-option',
	'settings'      => 'portfolio_web_theme_options[portfolio-web-pagination-option]',
	'type'	  	    => 'select'
) );