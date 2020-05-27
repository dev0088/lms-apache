<?php
/**
 * Template part for displaying posts and search results.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Acme Themes
 * @subpackage Portfolio Web
 */
global $portfolio_web_customizer_all_values;
$content_from = $portfolio_web_customizer_all_values['portfolio-web-blog-archive-content-from'];
$no_blog_image = '';

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="content-wrapper">
        <?php
        $thumbnail = $portfolio_web_customizer_all_values['portfolio-web-blog-archive-img-size'];
        if( has_post_thumbnail() && 'disable' != $thumbnail):
	        ?>
            <!--post thumbnal options-->
            <div class="image-wrap">
                <div class="post-thumb">
                    <a href="<?php the_permalink(); ?>">
				        <?php the_post_thumbnail( $thumbnail ); ?>
                    </a>
                </div><!-- .post-thumb-->
            </div>
	        <?php
        else:
	        $no_blog_image = 'no-image';
        endif;
        ?>
        <div class="entry-content <?php echo $no_blog_image?>">
        	<div class="">

        		<div class="blog-header">
        			<?php
        			if ( 'post' === get_post_type() ) : ?>
        				<header class="entry-header <?php echo $no_blog_image; ?>">
        					<div class="entry-meta">
                                <i class="fa fa-tag" aria-hidden="true"></i>
        						<?php
        						portfolio_web_cats_lists()
        						?>
        					</div><!-- .entry-meta -->
        				</header><!-- .entry-header -->
        				<?php
        			endif; ?>
        			<div class="entry-header-title">
        				<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
        			</div>
        			
        		</div>

        	</div><!--.blog-header-wrap-->
			<?php
            if ( 'content' == $content_from ) :
	            the_content( sprintf(
	            /* translators: %s: Name of current post. */
		            wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'portfolio-web' ), array( 'span' => array( 'class' => array() ) ) ),
		            the_title( '<span class="screen-reader-text">"', '"</span>', false )
	            ) );
	            wp_link_pages( array(
		            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'portfolio-web' ),
		            'after'  => '</div>',
	            ) );
            else :
                the_excerpt();
            endif;
			?>
            <footer class="entry-footer">
                <?php portfolio_web_entry_footer(1,  1, 1, 1 ); ?>
            </footer><!-- .entry-footer -->
		</div><!-- .entry-content -->
	</div>
</article><!-- #post-## -->