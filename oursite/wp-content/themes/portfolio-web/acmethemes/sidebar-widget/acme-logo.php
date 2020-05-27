<?php
/**
 * Class for adding About Section Widget
 *
 * @package Acme Themes
 * @subpackage Portfolio Web
 * @since 1.0.0
 */
if ( ! class_exists( 'Portfolio_Web_Advanced_Image_Logo' ) ) {

	class Portfolio_Web_Advanced_Image_Logo extends WP_Widget {
		/*defaults values for fields*/
		private $defaults = array(
		        'unique_id'                     => '',
                'title'                         => '',
		        'at_all_logo_items'             => '',
		        'column_number'                 => 4,
                'background_options'            => 'default'
		);

		function __construct() {
			parent::__construct(
			        /*Base ID of your widget*/
			        'portfolio_web_advanced_image_logo',
                    /*Widget name will appear in UI*/
                    esc_html__( 'AT Advanced Image/Logo', 'portfolio-web' ),
                    /*Widget description*/
                    array( 'description' => esc_html__( 'Show Image/Logo with advanced options', 'portfolio-web' ), )
			);
		}

		/*Widget Backend*/
		public function form( $instance ) {
			$instance =                 wp_parse_args( (array) $instance, $this->defaults );
			/*default values*/
			$unique_id                  = esc_attr( $instance['unique_id'] );
			$title                      = esc_attr( $instance['title'] );
			$at_all_logo_items          = $instance['at_all_logo_items'];
			$column_number              = absint( $instance['column_number'] );
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
            <label><?php esc_html_e( 'Add Image and Link', 'portfolio-web' ); ?></label>
            <br/>
            <small><?php esc_html_e( 'Add Logo, Reorder and Remove.', 'portfolio-web' ); ?></small>
            <div class="at-repeater">
				<?php
				$total_repeater = 0;
				if  ( is_array($at_all_logo_items) && count($at_all_logo_items) > 0 ){
					foreach ($at_all_logo_items as $logo_detail){
						$repeater_id  = $this->get_field_id( 'at_all_logo_items') .$total_repeater.'logo_img_url';
						$repeater_name  = $this->get_field_name( 'at_all_logo_items' ).'['.$total_repeater.']['.'logo_img_url'.']';

						$repeater_id_1  = $this->get_field_id( 'at_all_logo_items') .$total_repeater.'logo_img_link';
						$repeater_name_1  = $this->get_field_name( 'at_all_logo_items' ).'['.$total_repeater.']['.'logo_img_link'.']';
						?>
                        <div class="repeater-table">
                            <div class="at-repeater-top">
                                <div class="at-repeater-title-action">
                                    <button type="button" class="at-repeater-action">
                                        <span class="at-toggle-indicator" aria-hidden="true"></span>
                                    </button>
                                </div>
                                <div class="at-repeater-title">
                                    <h3><?php esc_html_e( 'Select Logo', 'portfolio-web' )?><span class="in-at-repeater-title"></span></h3>
                                </div>
                            </div>
                            <div class='at-repeater-inside hidden'>
	                            <?php
	                            $portfolio_web_display_none = '';
	                            if ( empty( $logo_detail['logo_img_url'] ) ){
		                            $portfolio_web_display_none = ' style="display:none;" ';
	                            }
	                            ?>
                                <span class="img-preview-wrap" <?php echo esc_attr( $portfolio_web_display_none ); ?>>
                                    <img class="widefat" src="<?php echo esc_url( $logo_detail['logo_img_url'] ); ?>" alt="<?php esc_attr_e( 'Image preview', 'portfolio-web' ); ?>"  />
                                </span><!-- .img-preview-wrap -->
                                <input type="text" class="widefat" name="<?php echo esc_attr( $repeater_name ); ?>" id="<?php echo esc_attr( $repeater_id ); ?>" value="<?php echo esc_url( $logo_detail['logo_img_url'] ); ?>" />
                                <input type="button" value="<?php esc_attr_e( 'Upload Image', 'portfolio-web' ); ?>" class="button media-image-upload" data-title="<?php esc_attr_e( 'Select Image','portfolio-web'); ?>" data-button="<?php esc_attr_e( 'Select Image','portfolio-web'); ?>"/>
                                <input type="button" value="<?php esc_attr_e( 'Remove Image', 'portfolio-web' ); ?>" class="button media-image-remove" />

                                <p>
                                    <label><?php esc_html_e( 'Enter Image Link', 'portfolio-web' ); ?></label>
                                    <input type="url" class="widefat" name="<?php echo esc_attr( $repeater_name_1 ); ?>" id="<?php echo esc_attr( $repeater_id_1 ); ?>" value="<?php echo esc_url( $logo_detail['logo_img_link'] ); ?>" />
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
				$repeater_id  = $this->get_field_id( 'at_all_logo_items') .$coder_repeater_depth.'logo_img_url';
				$repeater_name  = $this->get_field_name( 'at_all_logo_items' ).'['.$coder_repeater_depth.']['.'logo_img_url'.']';

				$repeater_id_1  = $this->get_field_id( 'at_all_logo_items') .$coder_repeater_depth.'logo_img_link';
				$repeater_name_1  = $this->get_field_name( 'at_all_logo_items' ).'['.$coder_repeater_depth.']['.'logo_img_link'.']';
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
                                <h3><?php esc_html_e( 'Select Logo', 'portfolio-web' )?><span class="in-at-repeater-title"></span></h3>
                            </div>
                        </div>
                        <div class='at-repeater-inside hidden'>
	                        <?php
	                        $portfolio_web_display_none = ' style="display:none;" ';
	                        ?>
                            <span class="img-preview-wrap" <?php echo esc_attr( $portfolio_web_display_none ) ; ?>>
                                <img class="widefat" src="" alt="<?php esc_attr_e( 'Image preview', 'portfolio-web' ); ?>"  />
                            </span><!-- .img-preview-wrap -->
                            <input type="text" class="widefat" name="<?php echo esc_attr( $repeater_name ); ?>" id="<?php echo esc_attr( $repeater_id ); ?>" value="" />
                            <input type="button" value="<?php esc_attr_e( 'Upload Image', 'portfolio-web' ); ?>" class="button media-image-upload" data-title="<?php esc_attr_e( 'Select Image','portfolio-web'); ?>" data-button="<?php esc_attr_e( 'Select Image','portfolio-web'); ?>"/>
                            <input type="button" value="<?php esc_attr_e( 'Remove Image', 'portfolio-web' ); ?>" class="button media-image-remove" />

                            <p>
                                <label><?php esc_html_e( 'Enter Image Link', 'portfolio-web' ); ?></label>
                                <input type="url" class="widefat" name="<?php echo esc_attr( $repeater_name_1 ); ?>" id="<?php echo esc_attr( $repeater_id_1 ); ?>" />
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
                <label for="<?php echo esc_attr( $this->get_field_id( 'column_number' ) ); ?>"><?php esc_html_e( 'Column Number', 'portfolio-web' ); ?></label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'column_number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'column_number' ) ); ?>">
					<?php
					$portfolio_web_logo_column_numbers = portfolio_web_widget_column_number();
					foreach ( $portfolio_web_logo_column_numbers as $key => $value ) {
						?>
                        <option value="<?php echo esc_attr( $key ) ?>" <?php selected( $key, $column_number ); ?>><?php echo esc_attr( $value ); ?></option>
						<?php
					}
					?>
                </select>
            </p>
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
			$logo_img_details = array();
			if( isset($new_instance['at_all_logo_items'] )){
				$at_all_logo_items                  = $new_instance['at_all_logo_items'];
				if  ( is_array($at_all_logo_items) && count($at_all_logo_items) > 0 ){
					foreach ($at_all_logo_items as $key=>$logo_detail ){
						$logo_img_details[$key]['logo_img_url'] = esc_url_raw( $logo_detail['logo_img_url'] );
						$logo_img_details[$key]['logo_img_link'] = esc_url_raw( $logo_detail['logo_img_link'] );
					}
				}
            }
			$instance[ 'at_all_logo_items' ]        = $logo_img_details;
			$instance[ 'column_number' ]            = absint( $new_instance['column_number'] );

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
			$at_all_logo_items          = $instance['at_all_logo_items'];
			$column_number              = absint( $instance['column_number'] );
			$background_options         = esc_attr( $instance['background_options'] );
			$bg_gray_class              = $background_options == 'gray'?'at-gray-bg':'';

			$div_attr = 'class="row featured-entries-col featured-entries-logo"';
            echo $args['before_widget'];

			$animation = "init-animate zoomIn";
			?>
            <section id="<?php echo esc_attr( $unique_id ); ?>" class="at-widgets acme-abouts <?php echo $bg_gray_class;?>">
                <div class="container">
                    <?php
                    if( ! empty( $title ) ){
	                    echo "<div class='at-widget-title-wrapper ".$animation."'>";
	                    if ( ! empty( $title ) ) {
                            echo $args['before_title'] . esc_html( $title ) . $args['after_title'];
                        }
                        echo "</div>";
                    }
	                ?>
                    <div <?php echo $div_attr;?>>
		                <?php
		                if  ( is_array($at_all_logo_items) && count($at_all_logo_items) > 0 ){
			                $portfolio_web_featured_index = 1;

			                foreach ( $at_all_logo_items as $logo_detail ){
				                if( isset( $logo_detail['logo_img_url'] ) && !empty( $logo_detail['logo_img_url'] ) ){
					                $logo_img_url = esc_url( $logo_detail['logo_img_url'] );
					                $logo_img_link = esc_url( $logo_detail['logo_img_link'] );
					                $portfolio_web_list_classes = 'single-list ';

					                if( 1 != $portfolio_web_featured_index && $portfolio_web_featured_index % $column_number == 1 ){
						                echo "<div class='clearfix'></div>";
					                }

					                if ( 1 == $column_number ) {
						                $portfolio_web_list_classes .= "col-sm-12";
					                } elseif ( 2 == $column_number ) {
						                $portfolio_web_list_classes .= "col-sm-6";
					                } elseif ( 3 == $column_number ) {
						                $portfolio_web_list_classes .= "col-sm-12 col-md-4";
					                } else {
						                $portfolio_web_list_classes .= "col-sm-12 col-md-3";
					                }
					                ?>
                                    <div class="<?php echo esc_attr( $portfolio_web_list_classes ); ?>">
                                        <div class="single-item <?php echo $animation;?>">
							                <?php
							                echo '<a href="'.$logo_img_link.'" class="all-link">';
							                ?>
                                            <img src=" <?php echo $logo_img_url?>">
							                <?php
							                echo '</a>';
							                ?>
                                        </div>
                                    </div><!--dynamic css-->
					                <?php
					                $portfolio_web_featured_index++;
				                }
			                }
		                }
		                ?>
                    </div>
                </div>
            </section>
			<?php
			echo $args['after_widget'];
		}
	} // Class Portfolio_Web_Advanced_Image_Logo ends here
}