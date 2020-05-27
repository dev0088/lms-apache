<?php
/**
 * Template part for displaying recent posts from widgets.
 *
 * @package Acme Themes
 * @subpackage Portfolio Web
 */
global  $portfolio_web_read_more_text;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('init-animate'); ?>>
    <div class="content-wrapper">
        <?php
        $no_blog_image = '';
        if ( has_post_thumbnail() ) {
            ?>
            <!--post thumbnal options-->
            <div class="image-wrap"><div class="post-thumb">
                    <a href="<?php the_permalink(); ?>">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </a>
                </div><!-- .post-thumb-->
            </div>
            <?php
        }
        else{
            $no_blog_image = 'no-image';
        }
        ?>

        <div class="entry-content <?php echo $no_blog_image?>">
            <header class="entry-header <?php echo $no_blog_image; ?>">
                <div class="entry-meta">
			        <?php
			        portfolio_web_cats_lists()
			        ?>
                </div><!-- .entry-meta -->
            </header><!-- .entry-header -->
            <div class="entry-header-title">
                <?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
            </div>
            <?php
            the_excerpt();
            if( !empty( $portfolio_web_read_more_text ) ){
                ?>
                <a class="btn btn-primary" href="<?php the_permalink(); ?> ">
                    <?php echo esc_html( $portfolio_web_read_more_text ); ?>
                </a>
                <?php
            }
            ?>
        </div><!-- .entry-content -->
    </div>
</article><!-- #post-## -->