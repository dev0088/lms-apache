<?php
/**
 * Main include functions ( to support child theme ) that child theme can override file too
 *
 * @since Portfolio Web 1.0.0
 *
 * @param string $file_path, path from the theme
 * @return string full path of file inside theme
 *
 */
if( !function_exists('portfolio_web_file_directory') ){

    function portfolio_web_file_directory( $file_path ){
        if( file_exists( trailingslashit( get_stylesheet_directory() ) . $file_path) ) {
            return trailingslashit( get_stylesheet_directory() ) . $file_path;
        }
        else{
            return trailingslashit( get_template_directory() ) . $file_path;
        }
    }
}

/**
 * Check empty or null
 *
 * @since Portfolio Web 1.0.0
 *
 * @param string $str, string
 * @return boolean
 *
 */
if( !function_exists('portfolio_web_is_null_or_empty') ){
    function portfolio_web_is_null_or_empty( $str ){
        return ( !isset($str) || trim($str)==='' );
    }
}

/*file for library*/
require portfolio_web_file_directory('acmethemes/library/tgm/class-tgm-plugin-activation.php');

/*
* file for customizer theme options
*/
require portfolio_web_file_directory('acmethemes/customizer/customizer.php');

/*
* file for additional functions files
*/
require portfolio_web_file_directory('acmethemes/functions.php');

require portfolio_web_file_directory('acmethemes/functions/sidebar-selection.php');

require portfolio_web_file_directory('acmethemes/functions/primary-menu.php');

/*woocommerce*/
require portfolio_web_file_directory('acmethemes/woocommerce/functions-woocommerce.php');

require portfolio_web_file_directory('acmethemes/woocommerce/class-woocommerce.php');

/*
* files for hooks
*/

require portfolio_web_file_directory('acmethemes/hooks/tgm.php');

require portfolio_web_file_directory('acmethemes/hooks/front-page.php');

require portfolio_web_file_directory('acmethemes/hooks/slider-selection.php');

require portfolio_web_file_directory('acmethemes/hooks/feature-info.php');

require portfolio_web_file_directory('acmethemes/hooks/header.php');

require portfolio_web_file_directory('acmethemes/hooks/header-helper.php');

require portfolio_web_file_directory('acmethemes/hooks/dynamic-css.php');

require portfolio_web_file_directory('acmethemes/hooks/footer.php');

require portfolio_web_file_directory('acmethemes/hooks/comment-forms.php');

require portfolio_web_file_directory('acmethemes/hooks/excerpts.php');

require portfolio_web_file_directory('acmethemes/hooks/siteorigin-panels.php');

require portfolio_web_file_directory('acmethemes/hooks/acme-demo-setup.php');

require portfolio_web_file_directory('acmethemes/hooks/template.php');

/*
* file for sidebar and widgets
*/
require portfolio_web_file_directory('acmethemes/sidebar-widget/acme-about.php');

require portfolio_web_file_directory('acmethemes/sidebar-widget/acme-col-posts.php');

require portfolio_web_file_directory('acmethemes/sidebar-widget/acme-contact.php');

require portfolio_web_file_directory('acmethemes/sidebar-widget/acme-gallery.php');

require portfolio_web_file_directory('acmethemes/sidebar-widget/acme-logo.php');

require portfolio_web_file_directory('acmethemes/sidebar-widget/acme-parallax.php');

require portfolio_web_file_directory('acmethemes/sidebar-widget/acme-service.php');

require portfolio_web_file_directory('acmethemes/sidebar-widget/acme-skills.php');

require portfolio_web_file_directory('acmethemes/sidebar-widget/acme-testimonial.php');

require portfolio_web_file_directory('acmethemes/sidebar-widget/acme-timeline.php');

require portfolio_web_file_directory('acmethemes/sidebar-widget/sidebar.php');

/*file for metaboxes*/
require portfolio_web_file_directory('acmethemes/metabox/meta-icons.php');

require portfolio_web_file_directory('acmethemes/metabox/metabox-defaults.php');

require portfolio_web_file_directory('acmethemes/metabox/meta-header.php');

/*
* file for core functions imported from functions.php while downloading Underscores
*/
require portfolio_web_file_directory('acmethemes/core.php');
require portfolio_web_file_directory('acmethemes/gutenberg/gutenberg-init.php');

/*themes info*/
require portfolio_web_file_directory('acmethemes/at-theme-info/class-at-theme-info.php');