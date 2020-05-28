<?php
/**
 * Display Social Links
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( !function_exists('portfolio_web_social_links') ) :

    function portfolio_web_social_links( ) {

        global $portfolio_web_customizer_all_values;
        $portfolio_web_social_data = json_decode( $portfolio_web_customizer_all_values['portfolio-web-social-data'] );
        if( is_array( $portfolio_web_social_data )){
            echo '<ul class="socials at-display-inline-block">';
	        foreach ( $portfolio_web_social_data as $social_data ){
		        $icon = $social_data->icon;
		        $link = $social_data->link;
		        $checkbox = $social_data->checkbox;
		        echo '<li>';
		        echo '<a href="'.esc_url( $link ).'" target="'.($checkbox == 1? '_blank':'').'">';
		        echo '<i class="fa '.esc_attr( $icon ).'"></i>';
		        echo '</a>';
		        echo '</li>';
	        }
	        echo '</ul>';
        }
    }
endif;
add_action( 'portfolio_web_action_social_links', 'portfolio_web_social_links', 10 );


if ( !function_exists('portfolio_web_action_top_menu') ) :

	function portfolio_web_action_top_menu( ) {
		echo "<div class='at-first-level-nav at-display-inline-block text-right'>";
		wp_nav_menu(
			array(
				'theme_location' => 'top-menu',
				'container' => false,
				'depth' => 1
			)
		);
		echo "</div>";
	}
endif;
add_action( 'portfolio_web_action_top_menu', 'portfolio_web_action_top_menu', 10 );