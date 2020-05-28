<?php
/**
 * Custom Metabox
 * Only added icon not special data
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if( !function_exists( 'portfolio_web_meta_add_featured_icon' )):
    function portfolio_web_meta_add_featured_icon() {
        add_meta_box(
            'portfolio_web_meta_fields', // $id
            esc_html__( 'Featured Icon', 'portfolio-web' ), // $title
            'portfolio_web_meta_featured_icon_callback', // $callback
            'page', // $page
            'side', // $context
            'core'// $priority
        );
    }
endif;
add_action('add_meta_boxes', 'portfolio_web_meta_add_featured_icon');

/**
 * Callback function for metabox
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( !function_exists('portfolio_web_meta_featured_icon_callback') ) :
    function portfolio_web_meta_featured_icon_callback(){
        global $post;
        $portfolio_web_featured_icon = get_post_meta( $post->ID, 'portfolio-web-featured-icon', true );
        wp_nonce_field( basename( __FILE__ ), 'portfolio_web_meta_fields_nonce' );
       ?>
        <div class="at-icons-wrapper">
            <div class="icon-preview">
                <?php if( !empty( $portfolio_web_featured_icon ) ) { echo '<i class="fa '. esc_attr( $portfolio_web_featured_icon ) .'"></i>'; } ?>
            </div>
            <div class="icon-toggle">
                <?php echo ( empty( $portfolio_web_featured_icon )? esc_html__('Add Icon','portfolio-web'): esc_html__('Edit Icon','portfolio-web') ); ?>
                <span class="dashicons dashicons-arrow-down"></span>
            </div>
            <div class="icons-list-wrapper hidden">
                <input class="icon-search widefat" type="text" placeholder="<?php esc_attr_e('Search Icon','portfolio-web')?>">
                <?php
                $fa_icon_list_array = portfolio_web_icons_array();
                foreach ( $fa_icon_list_array as $single_icon ) {
                    if( $portfolio_web_featured_icon == $single_icon ) {
                        echo '<span class="single-icon selected"><i class="fa '. esc_attr( $single_icon ) .'"></i></span>';
                    } else {
                        echo '<span class="single-icon"><i class="fa '. esc_attr( $single_icon ) .'"></i></span>';
                    }
                }
                ?>
            </div>
            <input class="widefat at-icon-value" id="portfolio-web-featured-icon" type="hidden" name="portfolio-web-featured-icon" value="<?php echo esc_attr( $portfolio_web_featured_icon ); ?>" placeholder="fa-desktop"/>
        </div>
        <?php
}
endif;

/**
 * Save the custom metabox data
 * @hooked to save_post hook
 *
 * @since Portfolio Web 1.0.0
 *
 * @param null
 * @return void
 *
 */
if ( !function_exists('portfolio_web_meta_save_featured_icon') ) :
    function portfolio_web_meta_save_featured_icon( $post_id ) {

        /*
         * A Guide to Writing Secure Themes – Part 4: Securing Post Meta
         *https://make.wordpress.org/themes/2015/06/09/a-guide-to-writing-secure-themes-part-4-securing-post-meta/
         * */
        if (
            !isset( $_POST[ 'portfolio_web_meta_fields_nonce' ] ) ||
            !wp_verify_nonce( $_POST[ 'portfolio_web_meta_fields_nonce' ], basename( __FILE__ ) ) || /*Protecting against unwanted requests*/
            ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || /*Dealing with autosaves*/
            ! current_user_can( 'edit_post', $post_id )/*Verifying access rights*/
        ){
            return;
        }
        //Execute this saving function
        if(isset($_POST['portfolio-web-featured-icon'])){
            $new = sanitize_text_field( $_POST['portfolio-web-featured-icon'] );
            update_post_meta( $post_id, 'portfolio-web-featured-icon', $new );
        }
    }
endif;
add_action('save_post', 'portfolio_web_meta_save_featured_icon' );