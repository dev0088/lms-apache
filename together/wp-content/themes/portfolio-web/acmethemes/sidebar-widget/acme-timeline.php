<?php
/**
 * Class for adding About Section Widget
 *
 * @package Acme Themes
 * @subpackage Portfolio Web
 * @since 1.0.0
 */
if ( ! class_exists( 'Portfolio_Web_Timeline' ) ) {

	class Portfolio_Web_Timeline extends WP_Widget {

		/*defaults values for fields*/
		private $defaults = array(
			'unique_id'                     => '',
			'title'                         => '',
			'at_all_timeline_items'        => '',
			'content_from'                  => 'excerpt',
			'content_number'                => -1,
            'background_options'            => 'default'
		);

		function __construct() {
			parent::__construct(
			        /*Base ID of your widget*/
			        'portfolio_web_timeline',
                    /*Widget name will appear in UI*/
                    esc_html__( 'AT Timeline Widget', 'portfolio-web' ),
                    /*Widget description*/
                    array(
                            'description' => esc_html__( 'Show Timeline Section beautifully', 'portfolio-web' )
                    )
			);
		}

		/*Widget Backend*/
		public function form( $instance ) {
			$instance =                 wp_parse_args( (array) $instance, $this->defaults );
			/*default values*/
			$unique_id                  = esc_attr( $instance['unique_id'] );
			$title                      = esc_attr( $instance['title'] );
			$at_all_timeline_items     = $instance['at_all_timeline_items'];
			$content_from               = esc_attr( $instance['content_from'] );
			$content_number             = intval( $instance['content_number'] );
			$background_options         = esc_attr( $instance['background_options'] );
			?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'unique_id' ) ); ?>"><?php esc_html_e( 'Section ID', 'portfolio-web' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'unique_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'unique_id' ) ); ?>" type="text" value="<?php echo $unique_id; ?>"/>
                <br/>
                <small><?php esc_html_e( 'Enter a Unique Section ID. You can use this ID in Menu item for enabling One Page Menu.', 'portfolio-web' ) ?></small>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'portfolio-web' ); ?></label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo $title; ?>"/>
            </p>
            <!--updated code-->
            <label><?php esc_html_e( 'Add Timeline Options', 'portfolio-web' ); ?></label>
            <br/>
            <small><?php esc_html_e( 'Add Timeline, Reorder and Remove.', 'portfolio-web' ); ?></small>
            <div class="at-repeater">
				<?php
				$total_repeater = 0;
				if  ( is_array( $at_all_timeline_items ) && count( $at_all_timeline_items ) > 0 ){
					foreach ( $at_all_timeline_items as $timeline_detail ){

						$repeater_timeline_page_id  = $this->get_field_id( 'at_all_timeline_items') .$total_repeater.'page_id';
						$repeater_timeline_page_name  = $this->get_field_name( 'at_all_timeline_items' ).'['.$total_repeater.']['.'page_id'.']';
						
						$repeater_timeline_title_id  = $this->get_field_id( 'at_all_timeline_items') .$total_repeater.'timeline_title';
						$repeater_timeline_title_name  = $this->get_field_name( 'at_all_timeline_items' ).'['.$total_repeater.']['.'timeline_title'.']';

						$repeater_timeline_duration_id  = $this->get_field_id( 'at_all_timeline_items') .$total_repeater.'duration';
						$repeater_timeline_duration_name  = $this->get_field_name( 'at_all_timeline_items' ).'['.$total_repeater.']['.'duration'.']';

						$repeater_timeline_link_id  = $this->get_field_id( 'at_all_timeline_items') .$total_repeater.'timeline_link';
						$repeater_timeline_link_name  = $this->get_field_name( 'at_all_timeline_items' ).'['.$total_repeater.']['.'timeline_link'.']';

						?>
                        <div class="repeater-table">
                            <div class="at-repeater-top">
                                <div class="at-repeater-title-action">
                                    <button type="button" class="at-repeater-action">
                                        <span class="at-toggle-indicator" aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="at-repeater-title">
                                    <h3><?php esc_html_e( 'Timeline', 'portfolio-web' )?><span class="in-at-repeater-title"></span></h3>
                                </div>
                            </div>
                            <div class='at-repeater-inside hidden'>

	                            <?php
	                            /* see more here https://codex.wordpress.org/Function_Reference/wp_dropdown_pages*/
	                            $args = array(
		                            'selected'          => $timeline_detail['page_id'],
		                            'name'              => $repeater_timeline_page_name,
		                            'id'                => $repeater_timeline_page_id,
		                            'class'             => 'widefat at-select',
		                            'show_option_none'  => esc_html__( 'Select Page', 'portfolio-web'),
		                            'option_none_value' => 0 // string
	                            );
	                            wp_dropdown_pages( $args );
	                            ?>
                                <div class="at-repeater-control-actions">
		                            <?php
		                            if( get_edit_post_link( $timeline_detail['page_id'] ) ){
			                            ?>
                                        <a class="button button-link at-postid alignright" target="_blank" href="<?php echo esc_url( get_edit_post_link( $timeline_detail['page_id'] ) ); ?>">
				                            <?php esc_html_e('Full Edit','portfolio-web');?>
                                        </a>
			                            <?php
		                            }
		                            ?>
                                </div>

                                <p>
                                    <label><?php esc_html_e( 'Enter Timeline Title', 'portfolio-web' ); ?></label>
                                    <input type="text" class="widefat" name="<?php echo esc_attr( $repeater_timeline_title_name ); ?>" id="<?php echo esc_attr( $repeater_timeline_title_id ); ?>" value="<?php echo esc_attr( $timeline_detail['timeline_title'] ); ?>" />
                                </p>
                                <p>
                                    <label><?php esc_html_e( 'Enter Timeline Date (Duration)', 'portfolio-web' ); ?></label>
                                    <input type="text" class="widefat" name="<?php echo esc_attr( $repeater_timeline_duration_name ); ?>" id="<?php echo esc_attr( $repeater_timeline_duration_id ); ?>" value="<?php echo esc_attr( $timeline_detail['duration'] ); ?>" />
                                </p>
                                <p>
                                    <label><?php esc_html_e( 'Enter Timeline Link', 'portfolio-web' ); ?></label>
                                    <input type="url" class="widefat" name="<?php echo esc_attr( $repeater_timeline_link_name ); ?>" id="<?php echo esc_attr( $repeater_timeline_link_id ); ?>" value="<?php echo esc_url( $timeline_detail['timeline_link'] ); ?>" />
                                </p>

                                <div class="at-repeater-control-actions">
                                    <button type="button" class="button-link button-link-delete at-repeater-remove"><?php esc_html_e('Remove','portfolio-web');?></button> |
                                    <button type="button" class="button-link at-repeater-close"><?php esc_html_e('Close','portfolio-web');?></button>
                                </div>
                            </div>
                        </div>
						<?php
						$total_repeater = $total_repeater + 1;
					}
				}
				$coder_repeater_depth = 'coderRepeaterDepth_'.'0';

				$repeater_timeline_page_id  = $this->get_field_id( 'at_all_timeline_items') .$coder_repeater_depth.'page_id';
				$repeater_timeline_page_name  = $this->get_field_name( 'at_all_timeline_items' ).'['.$coder_repeater_depth.']['.'page_id'.']';
				
				$repeater_timeline_title_id  = $this->get_field_id( 'at_all_timeline_items') .$coder_repeater_depth.'timeline_title';
				$repeater_timeline_title_name  = $this->get_field_name( 'at_all_timeline_items' ).'['.$coder_repeater_depth.']['.'timeline_title'.']';

				$repeater_timeline_duration_id  = $this->get_field_id( 'at_all_timeline_items') .$coder_repeater_depth.'duration';
				$repeater_timeline_duration_name  = $this->get_field_name( 'at_all_timeline_items' ).'['.$coder_repeater_depth.']['.'duration'.']';

				$repeater_timeline_link_id  = $this->get_field_id( 'at_all_timeline_items') .$coder_repeater_depth.'timeline_link';
				$repeater_timeline_link_name  = $this->get_field_name( 'at_all_timeline_items' ).'['.$coder_repeater_depth.']['.'timeline_link'.']';

				?>
                <script type="text/html" class="at-code-for-repeater">
                    <div class="repeater-table">
                        <div class="at-repeater-top">
                            <div class="at-repeater-title-action">
                                <button type="button" class="at-repeater-action">
                                    <span class="at-toggle-indicator" aria-hidden="true"></span>
                                </button>
                            </div>
                            <div class="at-repeater-title">
                                <h3><?php esc_html_e( 'Timeline', 'portfolio-web' )?><span class="in-at-repeater-title"></span></h3>
                            </div>
                        </div>
                        <div class='at-repeater-inside hidden'>

	                        <?php
	                        /* see more here https://codex.wordpress.org/Function_Reference/wp_dropdown_pages*/
	                        $args = array(
		                        'selected'          => '',
		                        'name'              => $repeater_timeline_page_name,
		                        'id'                => $repeater_timeline_page_id,
		                        'class'             => 'widefat at-select',
		                        'show_option_none'  => esc_html__( 'Select Page', 'portfolio-web'),
		                        'option_none_value' => 0 // string
	                        );
	                        wp_dropdown_pages( $args );
	                        ?>
                            <p>
                                <label><?php esc_html_e( 'Enter Timeline Title', 'portfolio-web' ); ?></label>
                                <input type="text" class="widefat" name="<?php echo esc_attr( $repeater_timeline_title_name ); ?>" id="<?php echo esc_attr( $repeater_timeline_title_id ); ?>" />
                            </p>
                            <p>
                                <label><?php esc_html_e( 'Enter Timeline Date (Duration)', 'portfolio-web' ); ?></label>
                                <input type="text" class="widefat" name="<?php echo esc_attr( $repeater_timeline_duration_name ); ?>" id="<?php echo esc_attr( $repeater_timeline_duration_id ); ?>" />
                            </p>
                            <p>
                                <label><?php esc_html_e( 'Enter Timeline Link', 'portfolio-web' ); ?></label>
                                <input type="url" class="widefat" name="<?php echo esc_attr( $repeater_timeline_link_name ); ?>" id="<?php echo esc_attr( $repeater_timeline_link_id ); ?>" />
                            </p>

                            <div class="at-repeater-control-actions">
                                <button type="button" class="button-link button-link-delete at-repeater-remove"><?php esc_html_e('Remove','portfolio-web');?></button> |
                                <button type="button" class="button-link at-repeater-close"><?php esc_html_e('Close','portfolio-web');?></button>
                            </div>
                        </div>
                    </div>

                </script>
				<?php
				/*most imp for repeater*/
				echo '<input class="at-total-repeater" type="hidden" value="'.esc_attr( $total_repeater ).'">';
				$add_field = esc_html__('Add Item', 'portfolio-web');
				echo '<span class="button-primary at-add-repeater" id="'.esc_attr( $coder_repeater_depth ).'">'.$add_field.'</span><br/>';
				?>
            </div>
            <!--updated code-->
            <p>
                <label for="<?php echo $this->get_field_id( 'content_from' ); ?>"><?php _e( 'Content From', 'portfolio-web' ); ?>:</label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'content_from' ); ?>" name="<?php echo $this->get_field_name( 'content_from' ); ?>">
					<?php
					$portfolio_web_about_content_from = portfolio_web_content_from();
					foreach ( $portfolio_web_about_content_from as $key => $value ) {
						?>
                        <option value="<?php echo esc_attr( $key ) ?>" <?php selected( $key, $content_from ); ?>><?php echo esc_attr( $value ); ?></option>
						<?php
					}
					?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id( 'content_number' ); ?>"><?php _e( 'Number of words in content', 'portfolio-web' ); ?>:</label>
                <br/>
                <small>
					<?php esc_html_e('Please enter -1 to show full content or 0 to show none','portfolio-web'); ?>
                </small>
                <input class="widefat" id="<?php echo $this->get_field_id( 'content_number' ); ?>" name="<?php echo $this->get_field_name( 'content_number' ); ?>" type="number" value="<?php echo $content_number; ?>" />
            </p>
            <hr /><!--view all link separate-->

            <hr />
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'background_options' ) ); ?>"><?php esc_html_e( 'Background Options', 'portfolio-web' ); ?></label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'background_options' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'background_options' ) ); ?>">
					<?php
					$portfolio_web_background_options = portfolio_web_background_options();
					foreach ( $portfolio_web_background_options as $key => $value ) {
						?>
                        <option value="<?php echo esc_attr( $key ) ?>" <?php selected( $key, $background_options ); ?>><?php echo esc_attr( $value ); ?></option>
						<?php
					}
					?>
                </select>
            </p>
			<?php
		}

		/**
		 * Function to Updating widget replacing old instances with new
		 *
		 * @access public
		 * @since 1.0
		 *
		 * @param array $new_instance new arrays value
		 * @param array $old_instance old arrays value
		 *
		 * @return array
		 *
		 */
		public function update( $new_instance, $old_instance ) {
			$instance                               = $old_instance;
			$instance['unique_id']                  = sanitize_key( $new_instance['unique_id'] );
			$instance[ 'title' ]                    = ( isset( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
			/*updated code*/
			$at_all_timeline_items_data = array();
			if( isset($new_instance['at_all_timeline_items'] )){
				$at_all_timeline_items               = $new_instance['at_all_timeline_items'];

				if  ( is_array($at_all_timeline_items) && count($at_all_timeline_items) > 0 ){
					foreach ( $at_all_timeline_items as $boxes => $box ){
						foreach ( $box as $key => $value ){
							$at_all_timeline_items_data[$boxes][$key] = wp_kses_post( $value );
						}
					}
				}
			}
			$instance[ 'at_all_timeline_items' ]     = $at_all_timeline_items_data;

			$portfolio_web_about_content_from   = portfolio_web_content_from();
			$instance['content_from']           = portfolio_web_sanitize_choice_options( $new_instance['content_from'], $portfolio_web_about_content_from, 'excerpt' );
			$instance['content_number']   = intval( $new_instance['content_number'] );

			$portfolio_web_widget_background_options    = portfolio_web_background_options();
			$instance[ 'background_options' ]           = portfolio_web_sanitize_choice_options( $new_instance[ 'background_options' ], $portfolio_web_widget_background_options, 'default' );

			return $instance;
		}

		/**
		 * Function to Creating widget front-end. This is where the action happens
		 *
		 * @access public
		 * @since 1.0
		 *
		 * @param array $args widget setting
		 * @param array $instance saved values
		 *
		 * @return void
		 *
		 */
		public function widget( $args, $instance ) {
			$instance                   = wp_parse_args( (array) $instance, $this->defaults );
			/*default values*/
			$unique_id                  = ! empty( $instance['unique_id'] ) ? esc_attr( $instance['unique_id'] ) : esc_attr( $this->id );
			$title                      = !empty( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
			$title                      = apply_filters( 'widget_title', $title, $instance, $this->id_base );
			$at_all_timeline_items      = $instance['at_all_timeline_items'];
			$content_from               = esc_attr( $instance['content_from'] );
			$content_number             = intval( $instance['content_number'] );
			$background_options         = esc_attr( $instance['background_options'] );
			$bg_gray_class              = $background_options == 'gray'?'at-gray-bg':'';

            echo $args['before_widget'];

			$animation = "init-animate zoomIn";
			?>
            <section id="<?php echo esc_attr( $unique_id ); ?>" class="at-widgets at-timelime tow <?php echo $bg_gray_class;?>">
            	<div class="container">
		            <?php
		            if( ! empty( $title ) ){
			            echo "<div class='at-widget-title-wrapper ".$animation."'>";
			            if ( ! empty( $title ) ) {
				            echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
			            }
			            echo "</div>";/*.at-widget-title*/
		            }
		            ?>
		            <div class="at-container at-timeline-section">
                        <?php
                        $post_in = array();
                        $menu_other_details = array();
                        if  ( is_array($at_all_timeline_items) && count($at_all_timeline_items) > 0 ){
	                        foreach ( $at_all_timeline_items as $timeline_details ){
		                        if( isset(  $timeline_details['page_id'] ) && !empty( $timeline_details['page_id'] ) ){
			                        $page_id = $timeline_details['page_id'];
			                        $post_in[] = $page_id;
			                        $menu_other_details[$page_id]['timeline_title'] = $timeline_details['timeline_title'];
			                        $menu_other_details[$page_id]['duration'] = $timeline_details['duration'];
			                        $menu_other_details[$page_id]['timeline_link'] = $timeline_details['timeline_link'];
		                        }
	                        }
                        }
                        if( !empty ( $post_in ) && is_array( $post_in ) ) :
	                        $portfolio_web_post_in_page_args = array(
		                        'post__in'          => $post_in,
		                        'orderby'           => 'post__in',
		                        'posts_per_page'    => count( $post_in ),
		                        'post_type'         => 'page',
		                        'no_found_rows'     => true,
		                        'post_status'       => 'publish'
	                        );
	                        $timeline_query = new WP_Query( $portfolio_web_post_in_page_args );
	                        /*The Loop*/
	                        if ( $timeline_query->have_posts() ):
		                        $portfolio_web_featured_index = 1;

		                        while( $timeline_query->have_posts() ):$timeline_query->the_post();
			                        $timeline_title  = $menu_other_details[get_the_ID()]['timeline_title'];
			                        $duration  = $menu_other_details[get_the_ID()]['duration'];
			                        $timeline_link  = $menu_other_details[get_the_ID()]['timeline_link'];

			                        $current = '';
			                        if( $portfolio_web_featured_index == 1 ){
			                            $current = 'current';
                                    }
                                    if( $portfolio_web_featured_index % 2 == 0 ){
	                                    $current.=' at-even';
                                    }
                                    else{
	                                    $current.=' at-odd';
                                    }
			                        ?>
                                    <div class="at-timeline-block <?php echo $current;?> <?php echo esc_attr( $animation );?>">
                                        <div class="at-timeline-img">
                                        </div> <!-- at-timeline-img -->
                                        <div class="at-timeline-content">
                                            <h3>
                                                <?php
                                                if( !empty( $timeline_link ) ){
                                                    echo "<a href='".$timeline_link."'>";
                                                }
                                                the_title();
                                                if( !empty( $timeline_link ) ){
                                                    echo "</a>";
                                                }

                                                ?>
                                            </h3>
                                            <?php
                                            if( 0 != $content_number ){
                                                ?>
                                                <div class="timeline-content">
			                                        <?php
			                                        portfolio_web_advanced_content( $content_number, $content_from );
			                                        ?>
                                                </div><!--.timeline-content-->
		                                        <?php
	                                        }
	                                        ?>
                                            <span class="at-date">
                                                <?php
                                                if( !empty( $timeline_title )){
	                                                echo '<strong>'.esc_html( $timeline_title ).'</strong>';
                                                }
                                                if( !empty( $duration )){
	                                                echo esc_html( $duration );
                                                }
                                                ?>
                                            </span><!--.at-date-->
                                        </div> <!-- at-timeline-content -->
                                    </div><!--.at-timeline-block-->
			                        <?php
			                        $portfolio_web_featured_index++;
		                        endwhile;
	                        endif;
	                        wp_reset_postdata();
                        endif;
                        ?>
		            </div><!--.at-timeline-section-->
            	</div><!--.container-->
            </section><!--.at-timelime-->
            <?php

			echo $args['after_widget'];
		}
	} // Class Portfolio_Web_Timeline ends here
}