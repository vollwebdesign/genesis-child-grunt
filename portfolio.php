<?php
/**
 * Template Name: Filterable Portfolio
 *
 * By Sridhar Katakam (http://sridharkatakam.com) based on Adapt Theme (http://www.wpexplorer.com/adapt-free-responsive-wordpress-theme/)
 */
?>

<?php
# Force full width content
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
wp_enqueue_script('isotope', get_stylesheet_directory_uri() . '/js/isotope.pkgd.min.js', array('jquery'), '1.5.25', true);
wp_enqueue_script('isotope_init', get_stylesheet_directory_uri() . '/js/isotope_init.js', array('isotope'), '', true);
//* Add custom body class
add_filter( 'body_class', 'filerable_portfolio_add_body_class' );
//* Filterable Portfolio custom body class
function filerable_portfolio_add_body_class( $classes ) {
    $classes[] = 'filterable-portfolio-page';
        return $classes;
}
remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'filterable_portfolio_do_loop' );
/**
 * Outputs a custom loop
 *
 * @global mixed $paged current page number if paginated
 * @return void
 */
function filterable_portfolio_do_loop() { ?>

    <header id="page-heading" class="entry-header">
        <?php genesis_do_post_title(); ?>
        <?php $terms = get_terms( 'portfolio-category' ); ?>
        <?php if( $terms[0] ) { ?>
            <ul id="portfolio-cats" class="filter clearfix">
                <li><a href="#" class="active" data-filter="*"><span><?php _e('All', 'genesis'); ?></span></a></li>
                <?php foreach ($terms as $term ) : ?>
                    <li><a href="#" data-filter=".<?php echo $term->slug; ?>"><span><?php echo $term->name; ?></span></a></li>
                <?php endforeach; ?>
            </ul><!-- /portfolio-cats -->
        <?php } ?>
    </header><!-- /page-heading -->

    <div class="entry-content" itemprop="text">
         <?php $wpex_port_query = new WP_Query(
            array(
                'post_type' => 'portfolio',
                'showposts' => '-1',
                'no_found_rows' => true,
            )
        );
        if( $wpex_port_query->posts ) { ?>
            <div id="portfolio-wrap" class="clearfix filterable-portfolio">
                <div class="portfolio-content">
                    <?php $wpex_count=0; ?>
                    <?php while ( $wpex_port_query->have_posts() ) : $wpex_port_query->the_post(); ?>
                        <?php $wpex_count++; ?>
                        <?php $terms = get_the_terms( get_the_ID(), 'portfolio-category' ); ?>
                        <?php if ( has_post_thumbnail($post->ID) ) { ?>
                            <article class="portfolio-item col-<?php echo $wpex_count; ?> <?php if( $terms ) foreach ( $terms as $term ) { echo $term->slug .' '; }; ?>">
                                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php echo genesis_get_image( array( size => 'portfolio' ) ); ?>
                                <div class="portfolio-overlay"><h3><?php the_title(); ?></h3></div><!-- portfolio-overlay --></a>
                            </article>
                        <?php } ?>
                    <?php endwhile; ?>
                </div><!-- /portfolio-content -->
            </div><!-- /portfolio-wrap -->
        <?php } ?>
        <?php wp_reset_postdata(); ?>
    </div><!-- /entry-content -->

<?php }
genesis();
