<?php
/**
 * Header Image Display Options
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_menu_display_options
 *
 */
if ( !function_exists('portfolio_web_menu_display_options') ) :
	function portfolio_web_menu_display_options() {
		$portfolio_web_menu_display_options =  array(
			'menu-default'      => esc_html__( 'Default', 'portfolio-web' ),
			'menu-classic'      => esc_html__( 'Classic', 'portfolio-web' ),
			'header-transparent'      => esc_html__( 'Transparent', 'portfolio-web' )
		);
		return apply_filters( 'portfolio_web_menu_display_options', $portfolio_web_menu_display_options );
	}
endif;

/**
 * Menu and Logo Display Options
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_header_image_display
 *
 */
if ( !function_exists('portfolio_web_header_image_display') ) :
	function portfolio_web_header_image_display() {
		$portfolio_web_header_image_display =  array(
			'hide'              => esc_html__( 'Hide', 'portfolio-web' ),
			'bg-image'          => esc_html__( 'Background Image', 'portfolio-web' ),
			'normal-image'      => esc_html__( 'Normal Image', 'portfolio-web' )
		);
		return apply_filters( 'portfolio_web_header_image_display', $portfolio_web_header_image_display );
	}
endif;

/**
 * Menu Right Button Link Options
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_menu_right_button_link_options
 *
 */
if ( !function_exists('portfolio_web_menu_right_button_link_options') ) :
	function portfolio_web_menu_right_button_link_options() {
		$portfolio_web_menu_right_button_link_options =  array(
			'disable'       => esc_html__( 'Disable', 'portfolio-web' ),
			'booking'       => esc_html__( 'Popup Widgets ( Booking Form )', 'portfolio-web' ),
			'link'          => esc_html__( 'One Link', 'portfolio-web' )
		);
		return apply_filters( 'portfolio_web_menu_right_button_link_options', $portfolio_web_menu_right_button_link_options );
	}
endif;

/**
 * Header top display options of elements
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_header_top_display_selection
 *
 */
if ( !function_exists('portfolio_web_header_top_display_selection') ) :
	function portfolio_web_header_top_display_selection() {
		$portfolio_web_header_top_display_selection =  array(
			'hide'          => esc_html__( 'Hide', 'portfolio-web' ),
			'left'          => esc_html__( 'on Top Left', 'portfolio-web' ),
			'right'         => esc_html__( 'on Top Right', 'portfolio-web' )
		);
		return apply_filters( 'portfolio_web_header_top_display_selection', $portfolio_web_header_top_display_selection );
	}
endif;

/**
 * Feature slider text align
 *
 * @since Mercantile 1.0.0
 *
 * @param null
 * @return array $portfolio_web_slider_text_align
 *
 */
if ( !function_exists('portfolio_web_slider_text_align') ) :
	function portfolio_web_slider_text_align() {
		$portfolio_web_slider_text_align =  array(
			'alternate'     => esc_html__( 'Alternate', 'portfolio-web' ),
			'text-left'     => esc_html__( 'Left', 'portfolio-web' ),
			'text-right'    => esc_html__( 'Right', 'portfolio-web' ),
			'text-center'   => esc_html__( 'Center', 'portfolio-web' )
		);
		return apply_filters( 'portfolio_web_slider_text_align', $portfolio_web_slider_text_align );
	}
endif;

/**
 * Featured Slider Image Options
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_fs_image_display_options
 *
 */
if ( !function_exists('portfolio_web_fs_image_display_options') ) :
	function portfolio_web_fs_image_display_options() {
		$portfolio_web_fs_image_display_options =  array(
			'full-screen-bg' => esc_html__( 'Full Screen Background', 'portfolio-web' ),
			'responsive-img' => esc_html__( 'Responsive Image', 'portfolio-web' )
		);
		return apply_filters( 'portfolio_web_fs_image_display_options', $portfolio_web_fs_image_display_options );
	}
endif;

/**
 * Feature Info number
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_feature_info_number
 *
 */
if ( !function_exists('portfolio_web_feature_info_number') ) :
	function portfolio_web_feature_info_number() {
		$portfolio_web_feature_info_number =  array(
			1               => esc_html__( '1', 'portfolio-web' ),
			2               => esc_html__( '2', 'portfolio-web' ),
			3               => esc_html__( '3', 'portfolio-web' ),
			4               => esc_html__( '4', 'portfolio-web' ),
		);
		return apply_filters( 'portfolio_web_feature_info_number', $portfolio_web_feature_info_number );
	}
endif;

/**
 * Footer copyright beside options
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_footer_copyright_beside_option
 *
 */
if ( !function_exists('portfolio_web_footer_copyright_beside_option') ) :
	function portfolio_web_footer_copyright_beside_option() {
		$portfolio_web_footer_copyright_beside_option =  array(
			'hide'          => esc_html__( 'Hide', 'portfolio-web' ),
			'social'        => esc_html__( 'Social Links', 'portfolio-web' ),
			'footer-menu'   => esc_html__( 'Footer Menu', 'portfolio-web' )
		);
		return apply_filters( 'portfolio_web_footer_copyright_beside_option', $portfolio_web_footer_copyright_beside_option );
	}
endif;

/**
 * Button design options
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_button_design
 *
 */
if ( !function_exists('portfolio_web_button_design') ) :
	function portfolio_web_button_design() {
		$portfolio_web_button_design =  array(
			'rectangle'         => esc_html__( 'Rectangle', 'portfolio-web' ),
			'rounded-rectangle' => esc_html__( 'Rounded Rectangle', 'portfolio-web' )
		);
		return apply_filters( 'portfolio_web_button_design', $portfolio_web_button_design );
	}
endif;

/**
 * Sidebar layout options
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_sidebar_layout
 *
 */
if ( !function_exists('portfolio_web_sidebar_layout') ) :
    function portfolio_web_sidebar_layout() {
        $portfolio_web_sidebar_layout =  array(
	        'right-sidebar' => esc_html__( 'Right Sidebar', 'portfolio-web' ),
	        'left-sidebar'  => esc_html__( 'Left Sidebar' , 'portfolio-web' ),
	        'both-sidebar'  => esc_html__( 'Both Sidebar' , 'portfolio-web' ),
	        'middle-col'    => esc_html__( 'Middle Column' , 'portfolio-web' ),
	        'no-sidebar'    => esc_html__( 'No Sidebar', 'portfolio-web' )
        );
        return apply_filters( 'portfolio_web_sidebar_layout', $portfolio_web_sidebar_layout );
    }
endif;

/**
 * Blog layout options
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_blog_archive_feature_layout
 *
 */
if ( !function_exists('portfolio_web_blog_archive_feature_layout') ) :
    function portfolio_web_blog_archive_feature_layout() {
        $portfolio_web_blog_archive_feature_layout =  array(
            'left-image'    => esc_html__( 'Show Image', 'portfolio-web' ),
            'no-image'      => esc_html__( 'No Image', 'portfolio-web' )
        );
        return apply_filters( 'portfolio_web_blog_archive_feature_layout', $portfolio_web_blog_archive_feature_layout );
    }
endif;

/**
 * Blog content from
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_blog_archive_content_from
 *
 */
if ( !function_exists('portfolio_web_blog_archive_content_from') ) :
	function portfolio_web_blog_archive_content_from() {
		$portfolio_web_blog_archive_content_from =  array(
			'excerpt'    => esc_html__( 'Excerpt', 'portfolio-web' ),
			'content'    => esc_html__( 'Content', 'portfolio-web' )
		);
		return apply_filters( 'portfolio_web_blog_archive_content_from', $portfolio_web_blog_archive_content_from );
	}
endif;

/**
 * Image Size
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_get_image_sizes_options
 *
 */
if ( !function_exists('portfolio_web_get_image_sizes_options') ) :
	function portfolio_web_get_image_sizes_options( $add_disable = false ) {
		global $_wp_additional_image_sizes;
		$choices = array();
		if ( true == $add_disable ) {
			$choices['disable'] = esc_html__( 'No Image', 'portfolio-web' );
		}
		foreach ( array( 'thumbnail', 'medium', 'large' ) as $key => $_size ) {
			$choices[ $_size ] = $_size . ' ('. get_option( $_size . '_size_w' ) . 'x' . get_option( $_size . '_size_h' ) . ')';
		}
		$choices['full'] = esc_html__( 'full (original)', 'portfolio-web' );
		if ( ! empty( $_wp_additional_image_sizes ) && is_array( $_wp_additional_image_sizes ) ) {

			foreach ($_wp_additional_image_sizes as $key => $size ) {
				$choices[ $key ] = $key . ' ('. $size['width'] . 'x' . $size['height'] . ')';
			}
		}
		return apply_filters( 'portfolio_web_get_image_sizes_options', $choices );
	}
endif;

/**
 * Pagination Options
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array portfolio_web_pagination_options
 *
 */
if ( !function_exists('portfolio_web_pagination_options') ) :
	function portfolio_web_pagination_options() {
		$portfolio_web_pagination_options =  array(
			'default'  => esc_html__( 'Default', 'portfolio-web' ),
			'numeric'  => esc_html__( 'Numeric', 'portfolio-web' )
		);
		return apply_filters( 'portfolio_web_pagination_options', $portfolio_web_pagination_options );
	}
endif;

/**
 * Breadcrumb Options
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array portfolio_web_breadcrumb_options
 *
 */
if ( !function_exists('portfolio_web_breadcrumb_options') ) :
	function portfolio_web_breadcrumb_options() {
		$portfolio_web_breadcrumb_options =  array(
			'hide'  => esc_html__( 'Hide', 'portfolio-web' ),
		);
		if ( function_exists('yoast_breadcrumb') ) {
			$portfolio_web_breadcrumb_options['yoast'] = esc_html__( 'Yoast', 'portfolio-web' );
		}
		if ( function_exists('bcn_display') ) {
			$portfolio_web_breadcrumb_options['bcn'] = esc_html__( 'Breadcrumb NavXT', 'portfolio-web' );
		}
		return apply_filters( 'portfolio_web_pagination_options', $portfolio_web_breadcrumb_options );
	}
endif;

/**
 * Default Theme layout options
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_theme_layout
 *
 */
if ( !function_exists('portfolio_web_get_default_theme_options') ) :
    function portfolio_web_get_default_theme_options() {

        $default_theme_options = array(

	        /*logo and site title*/
	        'portfolio-web-display-site-logo'      => '',
	        'portfolio-web-display-site-title'     => 1,
	        'portfolio-web-display-site-tagline'   => 1,

	        /*header height*/
	        'portfolio-web-header-height'          => 300,
	        'portfolio-web-header-image-display'   => 'normal-image',

            /*header top*/
            'portfolio-web-enable-header-top'       => '',
	        'portfolio-web-header-top-menu-display-selection'      => 'right',
	        'portfolio-web-header-top-info-display-selection'      => 'left',
	        'portfolio-web-header-top-social-display-selection'    => 'right',

	        /*menu options*/
            'portfolio-web-menu-display-options'      => 'menu-default',
            'portfolio-web-enable-sticky'                  => '',
	        'portfolio-web-menu-right-button-options'      => 'disable',
	        'portfolio-web-menu-right-button-title'        => esc_html__('Book Table','portfolio-web'),
	        'portfolio-web-menu-right-button-link'         => '',
            'portfolio-web-enable-cart-icon'               => '',

	        /*feature section options*/
	        'portfolio-web-enable-feature'                         => '',
	        'portfolio-web-slides-data'                            => '',
            'portfolio-web-feature-slider-enable-animation'        => 1,
            'portfolio-web-feature-slider-display-title'           => 1,
            'portfolio-web-feature-slider-display-excerpt'         => 1,
            'portfolio-web-fs-image-display-options'               => 'full-screen-bg',
            'portfolio-web-feature-slider-text-align'              => 'text-left',
            'portfolio-web-slider-scroll-text'              => '',
            'portfolio-web-slider-scroll-link'              => '',

	        /*basic info*/
	        'portfolio-web-feature-info-number'    => 4,
	        'portfolio-web-first-info-icon'        => 'fa-calendar',
	        'portfolio-web-first-info-title'       => esc_html__('Send Us a Mail', 'portfolio-web'),
	        'portfolio-web-first-info-desc'        => esc_html__('domain@example.com ', 'portfolio-web'),
	        'portfolio-web-second-info-icon'       => 'fa-map-marker',
	        'portfolio-web-second-info-title'      => esc_html__('Our Location', 'portfolio-web'),
	        'portfolio-web-second-info-desc'       => esc_html__('Elmonte California', 'portfolio-web'),
	        'portfolio-web-third-info-icon'        => 'fa-phone',
	        'portfolio-web-third-info-title'       => esc_html__('Call Us', 'portfolio-web'),
	        'portfolio-web-third-info-desc'        => esc_html__('01-23456789-10', 'portfolio-web'),
	        'portfolio-web-forth-info-icon'        => 'fa-envelope-o',
	        'portfolio-web-forth-info-title'       => esc_html__('Office Hours', 'portfolio-web'),
	        'portfolio-web-forth-info-desc'        => esc_html__('8 hours per day', 'portfolio-web'),

            /*footer options*/
            'portfolio-web-footer-copyright'                       => esc_html__( '&copy; All right reserved', 'portfolio-web' ),
	        'portfolio-web-footer-copyright-beside-option'         => 'footer-menu',
	        'portfolio-web-enable-footer-power-text'               => 1,
	        'portfolio-web-footer-site-info'                       => '',
	        'portfolio-web-footer-bg-img'                          => '',

	        /*layout/design options*/
	        'portfolio-web-pagination-option'      => 'numeric',

	        'portfolio-web-enable-animation'       => '',

	        'portfolio-web-single-sidebar-layout'                  => 'right-sidebar',
            'portfolio-web-front-page-sidebar-layout'              => 'right-sidebar',
            'portfolio-web-archive-sidebar-layout'                 => 'right-sidebar',

            'portfolio-web-blog-archive-img-size'                  => 'full',
            'portfolio-web-blog-archive-content-from'              => 'excerpt',
            'portfolio-web-blog-archive-excerpt-length'            => 42,
	        'portfolio-web-blog-archive-more-text'                 => esc_html__( 'Read More', 'portfolio-web' ),


	        'portfolio-web-primary-color'          => '#4f6df5',
            'portfolio-web-header-top-bg-color'    => '#4f6df5',
            'portfolio-web-footer-bg-color'        => '#1b1b1b',
            'portfolio-web-footer-bottom-bg-color' => '#2d2d2d',
            'portfolio-web-link-color'             => '#748BF7',
            'portfolio-web-link-hover-color'       => '#3f5dcf',

	        'portfolio-web-hide-front-page-content' => '',
	        'portfolio-web-hide-front-page-header'  => '',

	        /*woocommerce*/
	        'portfolio-web-wc-shop-archive-sidebar-layout'     => 'no-sidebar',
	        'portfolio-web-wc-product-column-number'           => 4,
	        'portfolio-web-wc-shop-archive-total-product'      => 16,
	        'portfolio-web-wc-single-product-sidebar-layout'   => 'no-sidebar',

	        /*single post*/
	        'portfolio-web-single-header-title'            => esc_html__( 'Blog', 'portfolio-web' ),
	        'portfolio-web-single-img-size'                => 'full',

	        /*theme options*/
            'portfolio-web-popup-widget-title'     => esc_html__( 'Booking Table', 'portfolio-web' ),
	        'portfolio-web-breadcrumb-options'        => 'hide',
            'portfolio-web-search-placeholder'     => esc_html__( 'Search', 'portfolio-web' ),
            'portfolio-web-social-data'            => ''

        );
        return apply_filters( 'portfolio_web_default_theme_options', $default_theme_options );
    }
endif;

/**
 * Get theme options
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array portfolio_web_theme_options
 *
 */
if ( !function_exists('portfolio_web_get_theme_options') ) :
    function portfolio_web_get_theme_options() {

        $portfolio_web_default_theme_options = portfolio_web_get_default_theme_options();
        $portfolio_web_get_theme_options = get_theme_mod( 'portfolio_web_theme_options');
        if( is_array( $portfolio_web_get_theme_options )){
            return array_merge( $portfolio_web_default_theme_options ,$portfolio_web_get_theme_options );
        }
        else{
            return $portfolio_web_default_theme_options;
        }
    }
endif;