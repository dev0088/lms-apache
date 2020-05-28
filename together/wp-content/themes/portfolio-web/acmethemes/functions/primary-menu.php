<?php
/**
 * Display Primary Menu
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( !function_exists('portfolio_web_primary_menu') ) :

	function portfolio_web_primary_menu() {
		global $portfolio_web_customizer_all_values;
		?>
        <div class="search-woo">
			<?php
			$portfolio_web_menu_right_button_link_options = $portfolio_web_customizer_all_values['portfolio-web-menu-right-button-options'];
			$portfolio_web_button_title = $portfolio_web_customizer_all_values['portfolio-web-menu-right-button-title'];
			$portfolio_web_button_link = $portfolio_web_customizer_all_values['portfolio-web-menu-right-button-link'];
			if( 'disable' != $portfolio_web_menu_right_button_link_options ){
				$portfolio_web_button_title = !empty( $portfolio_web_button_title )?$portfolio_web_button_title:esc_html__('Book Table','portfolio-web');
				if( 'booking' == $portfolio_web_menu_right_button_link_options ){
					?>
                    <a class="featured-button btn btn-primary" href="#" data-toggle="modal" data-target="#at-shortcode-bootstrap-modal"><?php echo esc_html( $portfolio_web_button_title ); ?></a>
					<?php
				}
				else{
					?>
                    <a class="featured-button btn btn-primary" href="<?php echo esc_url( $portfolio_web_button_link ); ?>"><?php echo esc_html( $portfolio_web_button_title ); ?></a>
					<?php
				}
			}
			$portfolio_web_enable_woo_cart = $portfolio_web_customizer_all_values['portfolio-web-enable-cart-icon'];

			if( 1 == $portfolio_web_enable_woo_cart && class_exists( 'WooCommerce' ) ) {
				$cart_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : WC()->cart->get_cart_url();
				?>
                <div class="cart-wrap desktop-only">
                    <div class="acme-cart-views">
                        <a href="<?php echo esc_url( $cart_url ); ?>" class="cart-contents">
                            <i class="fa fa-shopping-cart"></i>
                            <span class="cart-value"><?php echo wp_kses_post ( WC()->cart->cart_contents_count ); ?></span>
                        </a>
                    </div>
					<?php the_widget( 'WC_Widget_Cart', '' ); ?>
                </div>
				<?php
			}
			?>
        </div>
		<div class="main-navigation navbar-collapse collapse">
			<?php
			if( is_front_page() && !is_home() && has_nav_menu( 'one-page') ){
				wp_nav_menu(
					array(
						'theme_location' => 'one-page',
						'menu_id' => 'primary-menu',
						'menu_class' => 'nav navbar-nav  acme-one-page',
						'container' => false,
					)
				);
			}
			else{
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id' => 'primary-menu',
						'menu_class' => 'nav navbar-nav  acme-normal-page',
						'container' => false
					)
				);
			}
			?>
		</div><!--/.nav-collapse -->
		<?php
	}
endif;