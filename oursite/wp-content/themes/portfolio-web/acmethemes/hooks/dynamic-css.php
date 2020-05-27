<?php
/**
 * Dynamic css
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( ! function_exists( 'portfolio_web_dynamic_css' ) ) :

    function portfolio_web_dynamic_css() {

        global $portfolio_web_customizer_all_values;

        /*header height*/
        $portfolio_web_header_height            = esc_attr( $portfolio_web_customizer_all_values['portfolio-web-header-height'] );

	    /*Color options */
        $portfolio_web_primary_color            = esc_attr( $portfolio_web_customizer_all_values['portfolio-web-primary-color'] );
	    $portfolio_web_link_color               = esc_attr( $portfolio_web_customizer_all_values['portfolio-web-link-color'] );
	    $portfolio_web_link_hover_color         = esc_attr( $portfolio_web_customizer_all_values['portfolio-web-link-hover-color'] );
	    
        $portfolio_web_header_top_bg_color      = esc_attr( $portfolio_web_customizer_all_values['portfolio-web-header-top-bg-color'] );
        $portfolio_web_footer_bg_color          = esc_attr( $portfolio_web_customizer_all_values['portfolio-web-footer-bg-color'] );
        $portfolio_web_footer_bottom_bg_color   = esc_attr( $portfolio_web_customizer_all_values['portfolio-web-footer-bottom-bg-color'] );

        /*Animation*/
        $portfolio_web_enable_animation = $portfolio_web_customizer_all_values['portfolio-web-enable-animation'];

        $custom_css = '';

        /*animation*/
        if( 1 == $portfolio_web_enable_animation ){
            $custom_css .= "
             .init-animate {
                visibility: visible !important;
             }
             ";
        }
        /*background*/
	    $portfolio_web_header_image_display = esc_attr( $portfolio_web_customizer_all_values['portfolio-web-header-image-display'] );
	    if( 'bg-image' == $portfolio_web_header_image_display || 'hide' == $portfolio_web_header_image_display ){
		    $bg_image_url ='';
		    if( get_header_image() && 'bg-image' == $portfolio_web_header_image_display ){
			    $bg_image_url = esc_url( get_header_image() );
		    }
		    $custom_css .= "
              .inner-main-title {
                background-image:url('{$bg_image_url}');
                background-repeat:no-repeat;
                background-size:cover;
                -webkit-background-size:cover;
                background-attachment:fixed;
                background-position: center; 
                height: {$portfolio_web_header_height}px;
            }";
	    }

        /*color*/
        $custom_css .= "
            .top-header{
                background-color: {$portfolio_web_header_top_bg_color};
            }";
        $custom_css .= "
            .site-footer{
                background-color: {$portfolio_web_footer_bg_color};
            }";
        $custom_css .= "
            .copy-right{
                background-color: {$portfolio_web_footer_bottom_bg_color};
            }";
        $custom_css .= "
            .site-title:hover,
	        .site-title a:hover,
			 .at-social .socials li a,
			 .primary-color,
			 #portfolio-web-breadcrumbs a:hover,
			 .woocommerce .star-rating, 
            .woocommerce ul.products li.product .star-rating,
            .woocommerce p.stars a,
            .woocommerce ul.products li.product .price,
            .woocommerce ul.products li.product .price ins .amount,
            .woocommerce a.button.add_to_cart_button:hover,
            .woocommerce a.added_to_cart:hover,
            .woocommerce a.button.product_type_grouped:hover,
            .woocommerce a.button.product_type_external:hover,
            .woocommerce .cart .button:hover,
            .woocommerce .cart input.button:hover,
            .woocommerce #respond input#submit.alt:hover,
			.woocommerce a.button.alt:hover,
			.woocommerce button.button.alt:hover,
			.woocommerce input.button.alt:hover,
			.woocommerce .woocommerce-info .button:hover,
			.woocommerce .widget_shopping_cart_content .buttons a.button:hover,
			.woocommerce div.product .woocommerce-tabs ul.tabs li a,
			.woocommerce-message::before,
			i.slick-arrow:hover,
			.acme-contact .contact-page-content ul li strong,
			.acme-contact .contact-page-content ul li strong,
            .main-navigation .acme-normal-page .current_page_item > a,
            .main-navigation .acme-normal-page .current-menu-item > a,
            .main-navigation .acme-normal-page .current_page_item li a:hover,
            .main-navigation .acme-normal-page .current-menu-item li a:hover,
            .main-navigation .acme-normal-page ul li a:hover,
            .at-sticky .main-navigation .acme-normal-page .current_page_item > a,
            .at-sticky .main-navigation .acme-normal-page .current-menu-item > a,
            .at-sticky .main-navigation .acme-normal-page .current_page_item li a:hover,
            .at-sticky .main-navigation .acme-normal-page .current-menu-item li a:hover,
            .at-sticky .main-navigation .acme-normal-page ul li a:hover,
            .main-navigation .active a,
            .main-navigation .navbar-nav > li a:hover,
            .main-navigation li li a:hover{
                color: {$portfolio_web_primary_color};
            }";

        /*background color*/
        $custom_css .= "
            .main-navigation .current_page_ancestor > a:before,
            .comment-form .form-submit input,
            .btn-primary,
            .wpcf7-form input.wpcf7-submit,
            .wpcf7-form input.wpcf7-submit:hover,
            .sm-up-container,
            .btn-primary.btn-reverse:before,
            #at-shortcode-bootstrap-modal .modal-header,
            .primary-bg,
			.navigation.pagination .nav-links .page-numbers.current,
			.navigation.pagination .nav-links a.page-numbers:hover,
			.woocommerce .product .onsale,
			.woocommerce a.button.add_to_cart_button,
			.woocommerce a.added_to_cart,
			.woocommerce a.button.product_type_grouped,
			.woocommerce a.button.product_type_external,
			.woocommerce .single-product #respond input#submit.alt,
			.woocommerce .single-product a.button.alt,
			.woocommerce .single-product button.button.alt,
			.woocommerce .single-product input.button.alt,
			.woocommerce #respond input#submit.alt,
			.woocommerce a.button.alt,
			.woocommerce button.button.alt,
			.woocommerce input.button.alt,
			.woocommerce .widget_shopping_cart_content .buttons a.button,
			.woocommerce div.product .woocommerce-tabs ul.tabs li:hover,
			.woocommerce div.product .woocommerce-tabs ul.tabs li.active,
			.woocommerce .cart .button,
			.woocommerce .cart input.button,
			.woocommerce input.button:disabled, 
			.woocommerce input.button:disabled[disabled],
			.woocommerce input.button:disabled:hover, 
			.woocommerce input.button:disabled[disabled]:hover,
			 .woocommerce nav.woocommerce-pagination ul li a:focus, 
			 .woocommerce nav.woocommerce-pagination ul li a:hover, 
			 .woocommerce nav.woocommerce-pagination ul li span.current,
			 .woocommerce a.button.wc-forward,
			 .woocommerce .widget_price_filter .ui-slider .ui-slider-range,
			 .woocommerce .widget_price_filter .ui-slider .ui-slider-handle,
			 .navbar .cart-wrap .acme-cart-views a span,
			 .acme-gallery .read-more,
             .woocommerce a.button.alt.disabled, 
              .woocommerce a.button.alt.disabled:hover, 
              .woocommerce a.button.alt:disabled, 
              .woocommerce a.button.alt:disabled:hover, 
              .woocommerce a.button.alt:disabled[disabled], 
              .woocommerce a.button.alt:disabled[disabled]:hover, 
              .woocommerce button.button.alt.disabled,
             .woocommerce-MyAccount-navigation ul > li> a:hover,
             .woocommerce-MyAccount-navigation ul > li.is-active > a,
             .featured-button.btn,
             .btn-primary:hover,
             .btn-primary:active,
             .btn-primary:active:hover,
             .btn-primary:active:focus,
             .btn-primary:focus{
                background-color: {$portfolio_web_primary_color};
                color:#fff;
                border:1px solid {$portfolio_web_primary_color};
            }";

        /*borders*/
	    $custom_css .= "
            .woocommerce .cart .button, 
            .woocommerce .cart input.button,
            .woocommerce a.button.add_to_cart_button,
            .woocommerce a.added_to_cart,
            .woocommerce a.button.product_type_grouped,
            .woocommerce a.button.product_type_external,
            .woocommerce .cart .button,
            .woocommerce .cart input.button
            .woocommerce .single-product #respond input#submit.alt,
			.woocommerce .single-product a.button.alt,
			.woocommerce .single-product button.button.alt,
			.woocommerce .single-product input.button.alt,
			.woocommerce #respond input#submit.alt,
			.woocommerce a.button.alt,
			.woocommerce button.button.alt,
			.woocommerce input.button.alt,
			.woocommerce .widget_shopping_cart_content .buttons a.button,
			.woocommerce div.product .woocommerce-tabs ul.tabs:before{
                border: 1px solid {$portfolio_web_primary_color};
            }";
        $custom_css .= "
            .blog article.sticky{
                border-bottom: 2px solid {$portfolio_web_primary_color};
            }";

	    $custom_css .= "
        a,
        .posted-on a,
        .single-item .fa,
        .author.vcard a,
        .comments-link a,
        .edit-link a,
        .tags-links a,
        .byline a,
        .nav-links a,
        .widget li a,
        .entry-meta i.fa, 
        .entry-footer i.fa,
        .counter-item i,
        .testimonial-content::after
         {
            color: {$portfolio_web_link_color};
        }";
	    $custom_css .= "
        a:hover,
        a:active,
        a:focus,
        .posted-on a:hover,
        .single-item .fa:hover,
        .author.vcard a:hover,
        .comments-link a:hover,
        .edit-link a:hover,
        .tags-links a:hover,
        .byline a:hover,
        .nav-links a:hover,
        .widget li a:hover{
            color: {$portfolio_web_link_hover_color};
        }";

        $custom_css .= "
       .btn-reverse,
       .image-slider-wrapper .slider-content .btn-reverse:hover,
       .at-widgets.at-parallax .btn-reverse:hover,
       .at-price h2{
            color: {$portfolio_web_primary_color};
        }";

        $custom_css .= "
       .btn-reverse:hover,
       .image-slider-wrapper .slider-content .btn-reverse:hover,
       .at-widgets.at-parallax .btn-reverse:hover{
            background: {$portfolio_web_primary_color};
            color:#fff;
            border-color:{$portfolio_web_primary_color};
        }";
        
        $custom_css .= "        
       .woocommerce #respond input#submit, 
       .woocommerce a.button, 
       .woocommerce button.button, 
       .woocommerce input.button{
            background: {$portfolio_web_primary_color};
            color:#fff;
        }";

        /*secondary color*/
	    $custom_css .= "
       .team-img-box:before{
            -webkit-box-shadow: 0 -106px 92px -35px {$portfolio_web_header_top_bg_color} inset;
			box-shadow: 0 -106px 92px -35px {$portfolio_web_header_top_bg_color} inset;
        }";

	    $custom_css .= "
       .at-pricing-box:hover .at-pricing-img-box::before{
            -webkit-box-shadow: 0 -130px 92px -35px {$portfolio_web_header_top_bg_color} inset;
            box-shadow: 0 -130px 92px -35px {$portfolio_web_header_top_bg_color} inset;
        }";
	    
        // Added color options
        $custom_css .= "
        
       .filters.button-group button:hover{
            background: {$portfolio_web_primary_color};
            color:#fff;
        }";
        $custom_css .= "
        .acme-services.normal .column .single-item:hover,
        .acme-services.normal .column .single-item:hover:after,
        .acme-services.normal .column .single-item:hover:before{
            border-color: {$portfolio_web_primary_color};
        }";

        $custom_css .= "
        .contact-form div.wpforms-container-full .wpforms-form input[type='submit'], 
        .contact-form div.wpforms-container-full .wpforms-form button[type='submit'], 
        .contact-form div.wpforms-container-full .wpforms-form .wpforms-page-button{
			background-color: {$portfolio_web_primary_color};
            color:#fff;
            border:1px solid {$portfolio_web_primary_color};
        }";

        $custom_css .= "
        .acme-accordions .accordion-title:hover,    
        .acme-accordions .accordion-title.active,    
        .acme-accordions .accordion-title.active a{
            border-color:{$portfolio_web_primary_color};
            background:{$portfolio_web_primary_color};
             
        }";

        $custom_css .= "
       .scroll-box span:after,
       .sm-up-container,
       .at-pricing-img-box .at-price,
       .at-timeline-block.current .at-timeline-img,
       .navbar-toggle:hover span.menu-icon span,
       .at-action-wrapper .slick-arrow{
            background:{$portfolio_web_link_color};
             
        }";
        $custom_css .= "
       .sm-up-container:hover,
       .at-action-wrapper .slick-arrow:hover{
            background:{$portfolio_web_link_hover_color};
             
        }";

        wp_add_inline_style( 'portfolio-web-style', $custom_css );
    }
endif;
add_action( 'wp_enqueue_scripts', 'portfolio_web_dynamic_css', 99 );