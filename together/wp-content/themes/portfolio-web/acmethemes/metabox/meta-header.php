<?php
/**
 * Portfolio Web  Template Builder Options
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return array
 *
 */
if ( !function_exists('portfolio_web_template_builder_header_options') ) :
	function portfolio_web_template_builder_header_options() {
		$portfolio_web_template_builder_header_options = array(
			'display-header' => array(
				'value'     => 'display-header',
				'title'     => esc_html__( 'Display Header', 'portfolio-web' )
			),
			'hide-header' => array(
				'value'     => 'hide-header',
				'title'     => esc_html__( 'Hide Header', 'portfolio-web' )
			)
		);
		return apply_filters( 'portfolio_web_template_builder_header_options', $portfolio_web_template_builder_header_options );
	}
endif;

/**
 * Custom Metabox
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if( !function_exists( 'portfolio_web_template_builder_metabox' )):
	function portfolio_web_template_builder_metabox() {
		add_meta_box(
			'portfolio_web_template_builder_metabox', // $id
			esc_html__( ' Template Builder Options', 'portfolio-web' ), // $title
			'portfolio_web_template_builder_callback', // $callback
			'post', // $page
			'normal', // $context
			'high'
		); // $priority

		add_meta_box(
			'portfolio_web_template_builder_metabox', // $id
			esc_html__( ' Template Builder Options', 'portfolio-web' ), // $title
			'portfolio_web_template_builder_callback', // $callback
			'page', // $page
			'normal', // $context
			'high'
		); // $priority
	}
endif;
add_action('add_meta_boxes', 'portfolio_web_template_builder_metabox');

/**
 * Callback function for metabox
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( !function_exists('portfolio_web_template_builder_callback') ) :
	function portfolio_web_template_builder_callback(){
		global $post;
		$portfolio_web_template_builder_header_options = portfolio_web_template_builder_header_options();
		$portfolio_web_default_header = 'display-header';
		$portfolio_web_template_builder_header_options_meta = get_post_meta( $post->ID, 'portfolio_web_template_builder_header_options', true );
		if( !portfolio_web_is_null_or_empty($portfolio_web_template_builder_header_options_meta) ){
			$portfolio_web_default_header = $portfolio_web_template_builder_header_options_meta;
		}
		wp_nonce_field( basename( __FILE__ ), 'portfolio_web_template_builder_meta_nonce' );
		?>
		<table class="form-table page-meta-box">
			<tr>
				<td colspan="4"><h4><?php esc_html_e( ' Header Option', 'portfolio-web' ); ?></h4></td>
			</tr>
			<tr>
				<td>
					<?php
					foreach ($portfolio_web_template_builder_header_options as $key=>$field) {
						?>
						<div>
							<input class="portfolio_web_template_builder_header_options" id="<?php echo esc_attr($key); ?>" type="radio" name="portfolio_web_template_builder_header_options" value="<?php echo esc_attr($key); ?>" <?php checked( $key, $portfolio_web_default_header ); ?> />
							<label class="description" for="<?php echo esc_attr($key); ?>">
								<?php echo esc_html( $field['title']); ?>
							</label>
						</div>
					<?php } // end foreach
					?>
					<div class="clear"></div>
				</td>
			</tr>
		</table>

	<?php }
endif;

/**
 * save the custom metabox data
 * @hooked to save_post hook
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( !function_exists('portfolio_web_save_template_builder_options') ) :
	function portfolio_web_save_template_builder_options( $post_id ) {

		if (
			!isset( $_POST[ 'portfolio_web_template_builder_meta_nonce' ] ) ||
			!wp_verify_nonce( $_POST[ 'portfolio_web_template_builder_meta_nonce' ], basename( __FILE__ ) ) || /*Protecting against unwanted requests*/
			( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || /*Dealing with autosaves*/
			! current_user_can( 'edit_post', $post_id )/*Verifying access rights*/
		){
			return;
		}
		if ('page' == $_POST['post_type']) {
			if (!current_user_can( 'edit_page', $post_id ) )
				return $post_id;
		} elseif (!current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		//Execute this saving function
		if( isset( $_POST['portfolio_web_template_builder_header_options'] )){
			$old = get_post_meta( $post_id, 'portfolio_web_template_builder_header_options', true );
			$new = esc_attr( $_POST['portfolio_web_template_builder_header_options'] );
			if ($new && $new != $old) {
				update_post_meta( $post_id, 'portfolio_web_template_builder_header_options', $new );
			} elseif ('' == $new && $old) {
				delete_post_meta( $post_id,'portfolio_web_template_builder_header_options', $old );
			}
		}
	}
endif;
add_action('save_post', 'portfolio_web_save_template_builder_options');