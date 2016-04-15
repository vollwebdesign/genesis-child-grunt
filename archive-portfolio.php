<?php
/**
 * Portfolio Archive
 * Author: James Roberts
 *
 */

// Force full width content (optional)
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
//remove standard loop (optional)
remove_action( 'genesis_loop', 'genesis_do_loop' );
// Add our custom loop
add_action( 'genesis_loop', 'lp_filterable_portfolio' );

// Enqueue javascript
wp_enqueue_script('isotope', get_stylesheet_directory_uri() . '/js/isotope.pkgd.min.js', array('jquery'), '1.5.25', true);
wp_enqueue_script('isotope_init', get_stylesheet_directory_uri() . '/js/isotopes_init.js', array('isotope'), '', true);

/**
* Get Excerpt.
*
* @since 1.0
*
*/
function the_excerpt_max_charlength($charlength) {
	$excerpt = get_the_excerpt();
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '[...]';
	} else {
		echo $excerpt;
	}
}

/**
* Output filterable portfolio items.
*
* @since 1.0
*
*/
function lp_filterable_portfolio( ){

    $args = array(
            'post_per_page' => 9999
    );
    $loop = new WP_Query( $args );
    $terms = get_terms( 'portfolio_category' );
    $count=0;
    ?>

<div class="archive-description">
        <?php if( $terms ) { ?>
            <ul id="portfolio-cats" class="filter clearfix">
                <li><a href="#" class="active" data-filter="*"><button><?php _e('All', 'lp'); ?></button></a></li>
                <?php
                    foreach( $terms as $term ){
                        echo "<li><a href='#' data-filter='.$term->slug'><button>$term->name</button></a></li>";
                    }
                ?>
            </ul><!-- /portfolio-cats --><br/><br/>
        <?php } ?>

         <?php if( have_posts() ) { ?>
            <div id="portfolio-wrap" class="clearfix filterable-portfolio">
                <div class="portfolio-content">
                    <?php while( have_posts() ): the_post(); ?>
                        <?php $count++; ?>
                        <?php $terms = get_the_terms( get_the_ID(), 'portfolio_category' ); ?>
                        <?php if ( has_post_thumbnail($post->ID) ) { ?>
                            <article class="portfolio-item col-<?php echo $count; ?> <?php if( $terms ) foreach ( $terms as $term ) { echo $term->slug .' '; }; ?>">
                                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php echo genesis_get_image( array( size => 'lp-portfolio' ) ); ?>
                                <div class="portfolio-overlay">
                                    <h3><?php the_title(); ?></h3>
                                    <p><?php the_excerpt_max_charlength(50);?></p>
                                </div><!-- overlay --></a>
                            </article>
                        <?php } ?>
                    <?php endwhile; ?>
                </div><!-- /themes-content -->
            </div><!-- /themes-wrap -->
        <?php } ?>
        <?php wp_reset_postdata(); ?>
</div>

<?php

    wp_reset_postdata();

}

genesis();
