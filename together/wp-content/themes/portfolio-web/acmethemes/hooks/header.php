<?php
/**
 * Setting global variables for all theme options saved values
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( ! function_exists( 'portfolio_web_set_global' ) ) :
    function portfolio_web_set_global() {
        /*Getting saved values start*/
        $portfolio_web_saved_theme_options = portfolio_web_get_theme_options();
        $GLOBALS['portfolio_web_customizer_all_values'] = $portfolio_web_saved_theme_options;
        /*Getting saved values end*/
    }
endif;
add_action( 'portfolio_web_action_before_head', 'portfolio_web_set_global', 0 );

/**
 * Doctype Declaration
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( ! function_exists( 'portfolio_web_doctype' ) ) :
    function portfolio_web_doctype() {
        ?>
        <!DOCTYPE html><html <?php language_attributes(); ?>>
        <?php
    }
endif;
add_action( 'portfolio_web_action_before_head', 'portfolio_web_doctype', 10 );

/**
 * Code inside head tage but before wp_head funtion
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( ! function_exists( 'portfolio_web_before_wp_head' ) ) :

    function portfolio_web_before_wp_head() {
        ?>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
         <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="profile" href="//gmpg.org/xfn/11">
        <?php
    }
endif;
add_action( 'portfolio_web_action_before_wp_head', 'portfolio_web_before_wp_head', 10 );

/**
 * Add body class
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array
 *
 */
if ( ! function_exists( 'portfolio_web_body_class' ) ) :

    function portfolio_web_body_class( $portfolio_web_body_classes ) {

        global $portfolio_web_customizer_all_values;
        $portfolio_web_enable_animation = $portfolio_web_customizer_all_values['portfolio-web-enable-animation'];

        $portfolio_web_menu_display_position = $portfolio_web_customizer_all_values['portfolio-web-menu-display-options'];
        $portfolio_web_body_classes[] = esc_attr( $portfolio_web_menu_display_position );

        $portfolio_web_enable_header_top = $portfolio_web_customizer_all_values['portfolio-web-enable-header-top'];
        /*wow animation*/
        if( 1 != $portfolio_web_enable_animation ){
            $portfolio_web_body_classes[] = 'acme-animate';
        }
        $portfolio_web_body_classes[] = portfolio_web_sidebar_selection();

        $portfolio_web_enable_feature = $portfolio_web_customizer_all_values['portfolio-web-enable-feature'];

        if( 1 == $portfolio_web_enable_header_top  ){
             $portfolio_web_body_classes[] = 'header-enable-top';
        }
    
        if ( 1 != $portfolio_web_enable_feature && is_front_page()){
            $portfolio_web_body_classes[] = 'at-front-no-feature';
        }
        return $portfolio_web_body_classes;
    }
endif;
add_action( 'body_class', 'portfolio_web_body_class', 10, 1 );

/**
 * Start site div
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'portfolio_web_site_start' ) ) :

    function portfolio_web_site_start() {
        ?>
        <div class="site" id="page">
        <?php
    }
endif;
add_action( 'portfolio_web_action_before', 'portfolio_web_site_start', 20 );

/**
 * Skip to content
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( ! function_exists( 'portfolio_web_skip_to_content' ) ) :

    function portfolio_web_skip_to_content() {
        ?>
        <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'portfolio-web' ); ?></a>
        <?php
    }
endif;
add_action( 'portfolio_web_action_before_header', 'portfolio_web_skip_to_content', 10 );

/**
 * Main header
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( ! function_exists( 'portfolio_web_header' ) ) :
    function portfolio_web_header() {
        global $portfolio_web_customizer_all_values;
        $portfolio_web_enable_header_top = $portfolio_web_customizer_all_values['portfolio-web-enable-header-top'];
	    $portfolio_web_nav_class = '';
	    $portfolio_web_enable_sticky = $portfolio_web_customizer_all_values['portfolio-web-enable-sticky'];
	    if( 1 == $portfolio_web_enable_sticky ){
		    $portfolio_web_nav_class .= ' portfolio-web-sticky';
	    }
    
        if( 1 == $portfolio_web_enable_header_top ){
            ?>
            <div class="top-header">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <?php
                                $portfolio_web_header_top_menu_display_selection = $portfolio_web_customizer_all_values['portfolio-web-header-top-menu-display-selection'];
                                $portfolio_web_header_top_info_display_selection = $portfolio_web_customizer_all_values['portfolio-web-header-top-info-display-selection'];
                                $portfolio_web_header_top_social_display_selection = $portfolio_web_customizer_all_values['portfolio-web-header-top-social-display-selection'];

                                if( 'left' == $portfolio_web_header_top_menu_display_selection ){
                                    do_action('portfolio_web_action_top_menu');
                                }
                                if( 'left' == $portfolio_web_header_top_social_display_selection ){
                                    do_action('portfolio_web_action_social_links');
                                }
                                if( 'left' == $portfolio_web_header_top_info_display_selection ){
                                    do_action('portfolio_web_action_feature_info');
                                }
                            ?>
                        </div>
                        <div class="col-sm-6 text-right">
                            <?php
                                if( 'right' == $portfolio_web_header_top_menu_display_selection ){
                                    do_action('portfolio_web_action_top_menu');
                                }
                                if( 'right' == $portfolio_web_header_top_social_display_selection ){
                                    do_action('portfolio_web_action_social_links');
                                }
                                if( 'right' == $portfolio_web_header_top_info_display_selection ){
                                    do_action('portfolio_web_action_feature_info');
                                }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>
        <div class="navbar at-navbar <?php echo  $portfolio_web_nav_class;?>" id="navbar" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="menu-icon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                        <!-- <i class="fa fa-bars"></i> -->
                    </button>
                    <span class="logo">                      
                  
                        <?php
                        $portfolio_web_display_site_logo = $portfolio_web_customizer_all_values['portfolio-web-display-site-logo'];
    	                $portfolio_web_display_site_title = $portfolio_web_customizer_all_values['portfolio-web-display-site-title'];
    	                $portfolio_web_display_site_tagline = $portfolio_web_customizer_all_values['portfolio-web-display-site-tagline'];
    	                
                        if( 1== $portfolio_web_display_site_logo || 1 == $portfolio_web_display_site_title || 1 == $portfolio_web_display_site_tagline ):
                            if ( 1 == $portfolio_web_display_site_logo && function_exists( 'the_custom_logo' ) ):
                                the_custom_logo();
                            endif;
                            if ( 1== $portfolio_web_display_site_title  ):
                                if ( is_front_page() && is_home() ) : ?>
                                    <h1 class="site-title">
                                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                                    </h1>
                                <?php else : ?>
                                    <p class="site-title">
                                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
                                    </p>
                                <?php endif;
                            endif;
                            if ( 1== $portfolio_web_display_site_tagline  ):
                                $description = get_bloginfo( 'description', 'display' );
                                if ( $description || is_customize_preview() ) : ?>
                                    <p class="site-description"><?php echo esc_html( $description ); ?></p>
                                <?php endif;
                            endif;
                        endif;
                        ?>
                    </span>
                </div>
                <div class="at-beside-navbar-header">
                    <a href="#" class="close-menu">
                        <span class="menu-icon">
                            <span></span>
                            <span></span>
                        </span>
                    </a>
	                <?php
	                 portfolio_web_primary_menu();
	                ?>
                </div>
                <!--.at-beside-navbar-header-->
            </div>
        </div>
        <?php
    }
endif;
add_action( 'portfolio_web_action_header', 'portfolio_web_header', 10 );