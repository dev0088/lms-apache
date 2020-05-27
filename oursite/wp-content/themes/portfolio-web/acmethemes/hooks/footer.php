<?php
/**
 * Footer content
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'portfolio_web_footer' ) ) :

    function portfolio_web_footer() {

        global $portfolio_web_customizer_all_values;

        $portfolio_web_footer_bg_img = $portfolio_web_customizer_all_values['portfolio-web-footer-bg-img'];
	    $style = '';
        if( !empty( $portfolio_web_footer_bg_img ) ){
	        $style .= 'background-image:url(' . esc_url( $portfolio_web_footer_bg_img ) . ');background-repeat:no-repeat;background-size:cover;background-position:center;';
        }
	    ?>
        <div class="clearfix"></div>
        </div><!-- #page -->
        <footer class="site-footer" style=" <?php echo $style; ?>">
            <?php
            $footer_column = 0;
            if(is_active_sidebar('footer-col-one') ){
	            $footer_column++;
            }
            if(is_active_sidebar('footer-col-two') ){
	            $footer_column++;
            }
            if(is_active_sidebar('footer-col-three') ){
	            $footer_column++;
            }
            if(is_active_sidebar('footer-col-four') ){
	            $footer_column++;
            }
            if( 0 !=$footer_column ) {
                ?>
                <div class="footer-columns at-fixed-width">
                    <div class="container">
                        <div class="row">
			                <?php
			                if ( 2 == $footer_column ){
				                $footer_top_col = 'col-sm-6 init-animate';
			                }
                            elseif ( 3 == $footer_column ){
				                $footer_top_col = 'col-sm-4 init-animate';
			                }
                            elseif ( 4 == $footer_column ){
				                $footer_top_col = 'col-sm-6 col-md-6 col-lg-3 init-animate';
			                }
			                else{
				                $footer_top_col = 'col-sm-12 init-animate';
			                }
			                $footer_top_col .= ' zoomIn';
			                if (is_active_sidebar('footer-col-one')) : ?>
                                <div class="footer-sidebar <?php echo esc_attr($footer_top_col); ?>">
					                <?php dynamic_sidebar('footer-col-one'); ?>
                                </div>
			                <?php endif;
			                if (is_active_sidebar('footer-col-two')) : ?>
                                <div class="footer-sidebar <?php echo esc_attr($footer_top_col); ?>">
					                <?php dynamic_sidebar('footer-col-two'); ?>
                                </div>
			                <?php endif;
			                if (is_active_sidebar('footer-col-three')) : ?>
                                <div class="footer-sidebar <?php echo esc_attr($footer_top_col); ?>">
					                <?php dynamic_sidebar('footer-col-three'); ?>
                                </div>
			                <?php endif;
			                if (is_active_sidebar('footer-col-four')) : ?>
                                <div class="footer-sidebar <?php echo esc_attr($footer_top_col); ?>">
					                <?php dynamic_sidebar('footer-col-four'); ?>
                                </div>
			                <?php endif; ?>
                        </div>
                    </div><!-- bottom-->
                </div>
                <div class="clearfix"></div>
                <?php
            }
            ?>
            <div class="copy-right">
                <div class='container'>
                    <div class="row">
                        <div class="col-sm-6 init-animate">
                            <div class="footer-copyright text-left">
	                            <?php
	                            if( isset( $portfolio_web_customizer_all_values['portfolio-web-footer-copyright'] ) ): ?>
                                    <p class="at-display-inline-block">
			                            <?php echo wp_kses_post( $portfolio_web_customizer_all_values['portfolio-web-footer-copyright'] ); ?>
                                    </p>
	                            <?php endif;
	                            if( 1 == $portfolio_web_customizer_all_values['portfolio-web-enable-footer-power-text'] ){
		                            echo '<div class="site-info at-display-inline-block">';
		                            printf( esc_html__( '%1$s by %2$s', 'portfolio-web' ), 'Portfolio Web', '<a href="http://www.acmethemes.com/" rel="designer">Acme Themes</a>' );
		                            echo '</div><!-- .site-info -->';
	                            }
	                            ?>
                            </div>
                        </div>
                        <div class="col-sm-6 init-animate">
                            <?php
                            $portfolio_web_footer_copyright_beside_option = $portfolio_web_customizer_all_values['portfolio-web-footer-copyright-beside-option'];
                            if( 'hide' != $portfolio_web_footer_copyright_beside_option ){
	                            if ( 'social' == $portfolio_web_footer_copyright_beside_option ) {
		                            /**
		                             * Social Sharing
		                             * portfolio_web_action_social_links
		                             * @since Portfolio Web 1.0.0
		                             *
		                             * @hooked portfolio_web_social_links -  10
		                             */
		                            echo "<div class='text-right'>";
		                            do_action('portfolio_web_action_social_links');
		                            echo "</div>";
	                            }
	                            else{
		                            echo "<div class='at-first-level-nav text-right'>";
		                            wp_nav_menu(
			                            array(
				                            'theme_location' => 'footer-menu',
				                            'container' => false,
				                            'depth' => 1
                                        )
		                            );
		                            echo "</div>";
	                            }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <a href="#page" class="sm-up-container"><i class="fa fa-angle-up sm-up"></i></a>
            </div>
        </footer>
    <?php
    }
endif;
add_action( 'portfolio_web_action_footer', 'portfolio_web_footer', 10 );

/**
 * Page end
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return null
 *
 */
if ( ! function_exists( 'portfolio_web_page_end' ) ) :

    function portfolio_web_page_end() {
	    global $portfolio_web_customizer_all_values;
	    $portfolio_web_booking_form_title = $portfolio_web_customizer_all_values['portfolio-web-popup-widget-title'];
	    ?>
        <!-- Modal -->
        <div id="at-shortcode-bootstrap-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
					    <?php
					    if( !empty( $portfolio_web_booking_form_title ) ){
						    ?>
                            <h4 class="modal-title"><?php echo esc_html( $portfolio_web_booking_form_title ); ?></h4>
						    <?php
					    }
					    ?>
                    </div>
				    <?php
				    if( is_active_sidebar( 'popup-widget-area' ) ) :
					    echo "<div class='modal-body'>";
					    dynamic_sidebar( 'popup-widget-area' );
					    echo "</div>";
				    endif;
				    ?>
                </div><!--.modal-content-->
            </div>
        </div><!--#at-shortcode-bootstrap-modal-->
    <?php
    }
endif;
add_action( 'portfolio_web_action_after', 'portfolio_web_page_end', 10 );