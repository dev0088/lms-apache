<?php
/**
 * Template part for displaying single posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Acme Themes
 * @subpackage Portfolio Web
 */
$no_blog_image = '';
global $portfolio_web_customizer_all_values;

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('init-animate'); ?>>
	<div class="content-wrapper">
		<?php
		$portfolio_web_hide_single_featured_image = portfolio_web_featured_image_display( );
		if( has_post_thumbnail() && 'disable' != $portfolio_web_hide_single_featured_image ):
			echo '<div class="image-wrap"><figure class="post-thumb">';
			the_post_thumbnail( $portfolio_web_hide_single_featured_image );
			echo "</figure></div>";
		else:
			$no_blog_image = 'no-image';
		endif;

		?>
		<div class="entry-content <?php echo $no_blog_image?>">
			<?php
			if ( 'post' === get_post_type() && has_category() ) : ?>
				<div class="">
					<div class="blog-header">
		                <header class="entry-header <?php echo $no_blog_image; ?>">
		                    <div class="entry-meta">
								<?php
								portfolio_web_cats_lists()
								?>
		                    </div><!-- .entry-meta -->
		                </header><!-- .entry-header -->
		                <div class="entry-header-title">
		                	<?php
		                	the_title( '<h1 class="entry-title">', '</h1>' );
		                	?>
		                </div>
		                <footer class="entry-footer">
		                	<?php portfolio_web_entry_footer( 1,  1, 1, 0 ); ?>

		                </footer><!-- .entry-footer -->
						
					</div>
				</div>
			<?php
			endif;

			the_content();
			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'portfolio-web' ),
				'after'  => '</div>',
			) );
            ?>
		</div><!-- .entry-content -->
	</div>
</article><!-- #post-## -->