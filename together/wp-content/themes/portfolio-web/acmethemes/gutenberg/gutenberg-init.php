<?php
if ( ! function_exists( 'portfolio_web_gutenberg_setup' ) ) :
	/**
	 * Making theme gutenberg compatible
	 */
	function portfolio_web_gutenberg_setup() {
		add_theme_support( 'align-wide' );
		add_theme_support( 'wp-block-styles' );
	}
endif;
add_action( 'after_setup_theme', 'portfolio_web_gutenberg_setup' );

function portfolio_web_dynamic_editor_styles(){

	global $portfolio_web_customizer_all_values;
	$portfolio_web_link_color               = esc_attr( $portfolio_web_customizer_all_values['portfolio_web-link-color'] );
	$portfolio_web_link_hover_color         = esc_attr( $portfolio_web_customizer_all_values['portfolio_web-link-hover-color'] );

	$custom_css = '';

	$custom_css .= "
            .edit-post-visual-editor, 
			.edit-post-visual-editor p {
               color: #666;
            }";

	$custom_css .= "
	        .wp-block .wp-block-heading h1, 
	        .wp-block .wp-block-heading h1 a,
	        .wp-block .wp-block-heading h2,
	        .wp-block .wp-block-heading h2 a,
	        .wp-block .wp-block-heading h3, 
	        .wp-block .wp-block-heading h3 a,
	        .wp-block .wp-block-heading h4, 
	        .wp-block .wp-block-heading h4 a,
	        .wp-block .wp-block-heading h5, 
	        .wp-block .wp-block-heading h5 a,
	        .wp-block .wp-block-heading h6,
	        .wp-block .wp-block-heading h6 a{
	            color: #3a3a3a;
	        }";

	$custom_css .= "
	        .wp-block a{
	            color: {$portfolio_web_link_color};
	        }";
	$custom_css .= "
	        .wp-block a:hover,
	        .wp-block a:active,
	        .wp-block a:focus{
	            color: {$portfolio_web_link_hover_color};
	        }";

        return wp_strip_all_tags( $custom_css );	
}

/**
 * Enqueue block editor style
 */
function portfolio_web_block_editor_styles() {
	wp_enqueue_style( 'portfolio_web-googleapis', '//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i|Work+Sans:100,200,300,400,500,600,700,800,900', array(), null );
	wp_enqueue_style( 'portfolio_web-block-editor-styles', get_template_directory_uri() . '/acmethemes/gutenberg/gutenberg-edit.css', false, '1.0' );

	/**
	 * Styles from the customizer
	 */
	wp_add_inline_style( 'portfolio_web-block-editor-styles', portfolio_web_dynamic_editor_styles() );
}
add_action( 'enqueue_block_editor_assets', 'portfolio_web_block_editor_styles',99 );

function portfolio_web_gutenberg_scripts() {
	wp_enqueue_style( 'portfolio_web-block-front-styles', get_template_directory_uri() . '/acmethemes/gutenberg/gutenberg-front.css', false, '1.0' );
	wp_style_add_data( 'portfolio_web-block-front-styles', 'rtl', 'replace' );
}
add_action( 'wp_enqueue_scripts', 'portfolio_web_gutenberg_scripts' );