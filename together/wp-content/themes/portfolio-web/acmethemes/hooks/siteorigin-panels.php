<?php
/**
 * Adds Portfolio Web Theme Widgets in SiteOrigin Pagebuilder Tabs
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return null
 *
 */
function portfolio_web_siteorigin_widgets($widgets) {
    $theme_widgets = array(
        'Portfolio_Web_About',
	    'Portfolio_Web_Posts_Col',
	    'Portfolio_Web_Contact',
	    'Portfolio_Web_Gallery',
	    'Portfolio_Web_Advanced_Image_Logo',
	    'Portfolio_Web_Feature',
	    'Portfolio_Web_Service',
	    'Portfolio_Web_Skills',
	    'Portfolio_Web_Testimonial',
	    'Portfolio_Web_Timeline'
    );
    foreach($theme_widgets as $theme_widget) {
        if( isset( $widgets[$theme_widget] ) ) {
            $widgets[$theme_widget]['groups'] = array('portfolio-web');
            $widgets[$theme_widget]['icon']   = 'dashicons dashicons-screenoptions';
        }
    }
    return $widgets;
}
add_filter('siteorigin_panels_widgets', 'portfolio_web_siteorigin_widgets');

/**
 * Add a tab for the theme widgets in the page builder
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return null
 *
 */
function portfolio_web_siteorigin_widgets_tab($tabs){
    $tabs[] = array(
        'title'  => esc_html__('AT Portfolio Web Widgets', 'portfolio-web'),
        'filter' => array(
            'groups' => array('portfolio-web')
        )
    );
    return $tabs;
}
add_filter('siteorigin_panels_widget_dialog_tabs', 'portfolio_web_siteorigin_widgets_tab', 20 );