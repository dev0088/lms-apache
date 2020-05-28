<?php
/**
 * Categories lists
 *
 * @package Acme Themes
 * @subpackage Portfolio Web
 */
if ( ! function_exists( 'portfolio_web_cats_lists' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function portfolio_web_cats_lists() {
		// Hide author, category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'portfolio-web' ) );
			if ( $categories_list && portfolio_web_categorized_blog() ) {
				printf( '<span class="cat-links">%1$s</span>', $categories_list ); // WPCS: XSS OK.
			}
		}
	}
endif;

/**
 * Custom template tags for this theme.
 *
 * @package Acme Themes
 * @subpackage Portfolio Web
 */
if ( ! function_exists( 'portfolio_web_entry_footer' ) ) :
/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function portfolio_web_entry_footer( $show_date = 1, $show_author = 1, $show_tag = 1, $show_comment = 1 ) {
	// Hide author, category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		if( 1 == $show_date ){
			$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
			}

			$time_string = sprintf( $time_string,
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_attr( get_the_modified_date( 'c' ) ),
				esc_html( get_the_modified_date() )
			);
			$posted_on = sprintf(
				'%s',
				'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
			);

			echo '<i class="fa fa-calendar-check-o" aria-hidden="true"></i> <span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

		}

		if( 1 == $show_author ){
			printf(
				'%s',
				'<span class="author vcard"><i class="fa fa-user"></i><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
			);
		}
		if( 1 == $show_tag ){
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'portfolio-web' ) );
			if ( $tags_list ) {
				printf( '<span class="tags-links"><i class="fa fa-tags"></i>%1$s</span>', $tags_list ); // WPCS: XSS OK.
			}
		}
	}

	if( 1 == $show_comment ){
		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link"><i class="fa fa-comment-o"></i>';
			comments_popup_link( esc_html__( 'Leave a comment', 'portfolio-web' ), esc_html__( '1 Comment', 'portfolio-web' ), esc_html__( '% Comments', 'portfolio-web' ) );
			echo '</span>';
		}
	}

	if ( get_edit_post_link() ) :
		edit_post_link(
			sprintf(
			/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'portfolio-web' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link"><i class="fa fa-edit "></i>',
			'</span>'
		);
	endif;
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
if ( ! function_exists( 'portfolio_web_categorized_blog' ) ) :
	function portfolio_web_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'portfolio_web_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields'     => 'ids',
				'hide_empty' => 1,

				// We only need to know if there is more than one category.
				'number'     => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'portfolio_web_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so portfolio_web_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so portfolio_web_categorized_blog should return false.
			return false;
		}
	}
endif;

/**
 * Flush out the transients used in portfolio_web_categorized_blog.
 */
if ( ! function_exists( 'portfolio_web_category_transient_flusher' ) ) :
	function portfolio_web_category_transient_flusher() {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// Like, beat it. Dig?
		delete_transient( 'portfolio_web_categories' );
	}
endif;
add_action( 'edit_category', 'portfolio_web_category_transient_flusher' );
add_action( 'save_post',     'portfolio_web_category_transient_flusher' );