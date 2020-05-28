<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Acme Themes
 * @subpackage Portfolio Web
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('init-animate'); ?>>

	<?php
	$portfolio_web_hide_single_featured_image = portfolio_web_featured_image_display();
	$no_blog_image = '';
	if( has_post_thumbnail() && 'disable' != $portfolio_web_hide_single_featured_image):
		echo '<div class="single-feat clearfix"><figure class="single-thumb single-thumb-full">';
		the_post_thumbnail( $portfolio_web_hide_single_featured_image );
		echo "</figure></div>";
	else:
		$no_blog_image = 'no-image';
	endif;
	?>
	<div class="content-wrapper">
		<div class="entry-content">
			<?php
			the_content();
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'portfolio-web' ),
				'after'  => '</div>',
			) );
			?>
		</div><!-- .entry-content -->
        <?php
        if ( get_edit_post_link() ) :
            echo '<footer class="entry-footer">';
            edit_post_link(
                sprintf(
                /* translators: %s: Name of current post */
                    esc_html__( 'Edit %s', 'portfolio-web' ),
                    the_title( '<span class="screen-reader-text">"', '"</span>', false )
                ),
                '<span class="edit-link">',
                '</span>'
            );
            echo "</footer>";/*..entry-footer */
        endif;
        ?>
	</div>
</article><!-- #post-## -->