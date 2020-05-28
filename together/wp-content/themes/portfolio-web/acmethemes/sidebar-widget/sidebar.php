<?php
/**
 * Sanitize choices
 * @since Portfolio Web 1.0.0
 * @param null
 * @return string $portfolio_web_about_column_number
 *
 */
if ( ! function_exists( 'portfolio_web_sanitize_choice_options' ) ) :
	function portfolio_web_sanitize_choice_options( $value, $choices, $default ) {
		$input = wp_kses_post( $value );
		$output = array_key_exists( $input, $choices ) ? $input : $default;
		return $output;
	}
endif;

/**
 * Common functions for widgets
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 *
 * @return array $portfolio_web_about_column_number
 *
 */
if ( ! function_exists( 'portfolio_web_background_options' ) ) :
	function portfolio_web_background_options() {
		$portfolio_web_about_column_number = array(
			'default'   => esc_html__( 'Default', 'portfolio-web' ),
			'gray'      => esc_html__( 'Gray', 'portfolio-web' )
		);

		return apply_filters( 'portfolio_web_background_options', $portfolio_web_about_column_number );
	}
endif;

/**
 * Column Number
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 *
 * @return array $portfolio_web_about_column_number
 *
 */
if ( ! function_exists( 'portfolio_web_widget_column_number' ) ) :
	function portfolio_web_widget_column_number() {
		$portfolio_web_about_column_number = array(
			1 => esc_html__( '1', 'portfolio-web' ),
			2 => esc_html__( '2', 'portfolio-web' ),
			3 => esc_html__( '3', 'portfolio-web' ),
			4 => esc_html__( '4', 'portfolio-web' )
		);
		return apply_filters( 'portfolio_web_widget_column_number', $portfolio_web_about_column_number );
	}
endif;

/**
 * Widget Image Popup Type
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array $portfolio_web_gallery_image_popup
 *
 */
if ( !function_exists('portfolio_web_gallery_image_popup') ) :
	function portfolio_web_gallery_image_popup() {
		$portfolio_web_gallery_image_popup =  array(
			'gallery'   => esc_html__( 'Gallery', 'portfolio-web' ),
			'single'    => esc_html__( 'Single', 'portfolio-web' ),
			'disable'   => esc_html__( 'Disable', 'portfolio-web' ),
		);
		return apply_filters( 'portfolio_web_gallery_image_popup', $portfolio_web_gallery_image_popup );
	}
endif;

/**
 * Content From
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 *
 * @return array $portfolio_web_content_from
 *
 */
if ( ! function_exists( 'portfolio_web_content_from' ) ) :
	function portfolio_web_content_from() {
		$portfolio_web_about_column_number = array(
			'excerpt' => esc_html__( 'Excerpt', 'portfolio-web' ),
			'content' => esc_html__( 'Content', 'portfolio-web' )
		);
		return apply_filters( 'portfolio_web_content_from', $portfolio_web_about_column_number );
	}
endif;

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function portfolio_web_widgets_init() {
	register_sidebar( array(
        'name'          => esc_html__( 'Right Sidebar', 'portfolio-web' ),
        'id'            => 'portfolio-web-sidebar',
        'description'   => '',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
    if ( is_customize_preview() ) {
        $portfolio_web_home_description = sprintf( esc_html__( 'Displays widgets on home page main content area.%1$s Note : Please go to %2$s "Static Front Page"%3$s setting, Select "A static page" then "Front page" and "Posts page" to show added widgets', 'portfolio-web' ), '<br />','<b><a class="at-customizer" data-section="static_front_page" style="cursor: pointer">','</a></b>' );
    }
    else{
        $portfolio_web_home_description = esc_html__( 'Displays widgets on Front/Home page. Note : Please go to Setting => Reading, Select "A static page" then "Front page" and "Posts page" to show added widgets', 'portfolio-web' );
    }
    register_sidebar(array(
        'name'          => esc_html__('Home Main Content Area', 'portfolio-web'),
        'id'            => 'portfolio-web-home',
        'description'	=> $portfolio_web_home_description,
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h2 class="widget-title init-animate zoomIn"><span>',
        'after_title'   => '</span></h2>',
    ));

	register_sidebar( array(
		'name'          => esc_html__( 'Left Sidebar', 'portfolio-web' ),
		'id'            => 'portfolio-web-sidebar-left',
		'description'   => '',
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

    register_sidebar(array(
        'name'          => esc_html__('Footer Column One', 'portfolio-web'),
        'id'            => 'footer-col-one',
        'description'   => esc_html__('Displays items on top footer section.', 'portfolio-web'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title"><span>',
        'after_title'   => '</span></h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column Two', 'portfolio-web'),
        'id'            => 'footer-col-two',
        'description'   => esc_html__('Displays items on top footer section.', 'portfolio-web'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title"><span>',
        'after_title'   => '</span></h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column Three', 'portfolio-web'),
        'id'            => 'footer-col-three',
        'description'   => esc_html__('Displays items on top footer section.', 'portfolio-web'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title"><span>',
        'after_title'   => '</span></h3>',
    ));

    register_sidebar(array(
        'name'          => esc_html__('Footer Column Four', 'portfolio-web'),
        'id'            => 'footer-col-four',
        'description'   => esc_html__('Displays items on top footer section.', 'portfolio-web'),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title"><span>',
        'after_title'   => '</span></h3>',
    ));

	register_sidebar(array(
		'name'          => esc_html__('Popup Widget Area', 'portfolio-web'),
		'id'            => 'popup-widget-area',
		'description'   => esc_html__('Displays items on Pop up', 'portfolio-web'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	));

    /*Widgets*/
	register_widget( 'Portfolio_Web_About' );
	register_widget( 'Portfolio_Web_Posts_Col' );
	register_widget( 'Portfolio_Web_Contact' );
	register_widget( 'Portfolio_Web_Gallery' );
	register_widget( 'Portfolio_Web_Advanced_Image_Logo' );
	register_widget( 'Portfolio_Web_Feature' );
	register_widget( 'Portfolio_Web_Service' );
	register_widget( 'Portfolio_Web_Skills' );
	register_widget( 'Portfolio_Web_Testimonial' );
	register_widget( 'Portfolio_Web_Timeline' );
}
add_action( 'widgets_init', 'portfolio_web_widgets_init' );

/* ajax callback for get_edit_post_link*/
add_action( 'wp_ajax_at_get_edit_post_link', 'portfolio_web_get_edit_post_link' );
function portfolio_web_get_edit_post_link(){
    if( isset( $_GET['id'] ) ){
	    $id = absint( $_GET['id'] );
	    if( get_edit_post_link( $id ) ){
		    ?>
            <a class="button button-link at-postid alignright" target="_blank" href="<?php echo esc_url( get_edit_post_link( $id ) ); ?>">
			    <?php esc_html_e('Full Edit','portfolio-web');?>
            </a>
		    <?php
	    }
	    else{
		    echo 0;
	    }
	    exit;
    }
}