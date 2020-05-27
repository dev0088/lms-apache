<?php
/**
 * Display Feature Columns
 * @since Portfolio Web 1.0.0
 *
 * @return void
 *
 */
if ( !function_exists('portfolio_web_feature_info') ) :
	function portfolio_web_feature_info() {
		global $portfolio_web_customizer_all_values;
		$portfolio_web_feature_info_number = $portfolio_web_customizer_all_values['portfolio-web-feature-info-number'];
		echo '<div class="info-icon-box-wrapper">';
		$number = $portfolio_web_feature_info_number;

		$portfolio_web_basic_info_data = array();

		$portfolio_web_first_info_icon = $portfolio_web_customizer_all_values['portfolio-web-first-info-icon'] ;
		$portfolio_web_first_info_title = $portfolio_web_customizer_all_values['portfolio-web-first-info-title'];
		$portfolio_web_first_info_desc = $portfolio_web_customizer_all_values['portfolio-web-first-info-desc'];
		$portfolio_web_basic_info_data[] = array(
			"icon"  => $portfolio_web_first_info_icon,
			"title" => $portfolio_web_first_info_title,
			"desc"  => $portfolio_web_first_info_desc
		);

		$portfolio_web_second_info_icon = $portfolio_web_customizer_all_values['portfolio-web-second-info-icon'] ;
		$portfolio_web_second_info_title = $portfolio_web_customizer_all_values['portfolio-web-second-info-title'];
		$portfolio_web_second_info_desc = $portfolio_web_customizer_all_values['portfolio-web-second-info-desc'];
		$portfolio_web_basic_info_data[] = array(
			"icon"  => $portfolio_web_second_info_icon,
			"title" => $portfolio_web_second_info_title,
			"desc"  => $portfolio_web_second_info_desc
		);

		$portfolio_web_third_info_icon = $portfolio_web_customizer_all_values['portfolio-web-third-info-icon'] ;
		$portfolio_web_third_info_title = $portfolio_web_customizer_all_values['portfolio-web-third-info-title'];
		$portfolio_web_third_info_desc = $portfolio_web_customizer_all_values['portfolio-web-third-info-desc'];
		$portfolio_web_basic_info_data[] = array(
			"icon"  => $portfolio_web_third_info_icon,
			"title" => $portfolio_web_third_info_title,
			"desc"  => $portfolio_web_third_info_desc
		);

		$portfolio_web_forth_info_icon = $portfolio_web_customizer_all_values['portfolio-web-forth-info-icon'] ;
		$portfolio_web_forth_info_title = $portfolio_web_customizer_all_values['portfolio-web-forth-info-title'];
		$portfolio_web_forth_info_desc = $portfolio_web_customizer_all_values['portfolio-web-forth-info-desc'];
		$portfolio_web_basic_info_data[] = array(
			"icon"  => $portfolio_web_forth_info_icon,
			"title" => $portfolio_web_forth_info_title,
			"desc"  => $portfolio_web_forth_info_desc
		);

		$col = " init-animate zoomIn";

		$i=0;
		foreach ( $portfolio_web_basic_info_data as $base_basic_info_data) {
			if( $i >= $number ){
				break;
			}
			?>
            <div class="info-icon-box <?php echo $col;?>">
				<?php
				if( !empty( $base_basic_info_data['icon'])){
					?>
                    <div class="info-icon">
                        <i class="fa <?php echo esc_attr( $base_basic_info_data['icon'] );?>"></i>
                    </div>
					<?php
				}
				if( !empty( $base_basic_info_data['title']) || !empty( $base_basic_info_data['desc']) ){
					?>
                    <div class="info-icon-details">
						<?php
						if( !empty( $base_basic_info_data['title']) ){
							echo '<h6 class="icon-title">'.esc_html( $base_basic_info_data['title'] ).'</h6>';
						}
						if( !empty( $base_basic_info_data['desc']) ){
							echo '<span class="icon-desc">'.wp_kses_post( $base_basic_info_data['desc'] ).'</span>';
						}
						?>
                    </div>
					<?php
				}
				?>
            </div>
			<?php
			$i++;
		}
		echo "</div>";/*.infowrapper*/
	}
endif;
add_action( 'portfolio_web_action_feature_info', 'portfolio_web_feature_info', 20 );