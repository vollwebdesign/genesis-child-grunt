<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Genesis Sample Theme' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '2.2.2' );

//* Enqueue Google Fonts
add_action( 'wp_enqueue_scripts', 'genesis_sample_scripts' );
function genesis_sample_scripts() {

	$suffix = '.min';

	//* Should we load minified scripts? Also enqueue live reload to allow for extensionless reloading with 'grunt watch'
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG == true ) {

		$suffix = '';
		wp_enqueue_script( 'live-reload', '//localhost:35729/livereload.js', array(), CHILD_THEME_VERSION, true );

	}

	//* Add Google Fonts
	wp_register_style( 'google-fonts', '//fonts.googleapis.com/css?family=Monoton|Montserrat&effect=shadow-multiple|Lato:400,700,300', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'google-fonts' );

	//* Remove default stylesheet
	wp_deregister_style( 'genesis-sample-theme' );

	//* Add compiled stylesheet
	wp_register_style( 'genesis-sample-theme', get_stylesheet_directory_uri() . '/style' . $suffix . '.css', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'genesis-sample-theme' );

	//* Add compiled JS
	wp_enqueue_script( 'genesis-sample-scripts', get_stylesheet_directory_uri() . '/js/script' . $suffix . '.js', array( 'jquery' ), CHILD_THEME_VERSION, true );


}

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add Accessibility support
add_theme_support( 'genesis-accessibility', array( 'headings', 'drop-down-menu',  'search-form', 'skip-links', 'rems' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

/** Remove jQuery and other scripts loading from header */
add_action('wp_enqueue_scripts', 'remove_header_scripts');
function remove_header_scripts() {
      wp_deregister_script( 'jquery' );
      wp_deregister_script( 'jquery-migrate' );
}

/** Load jQuery and other scripts just before closing Body tag */
add_action('genesis_after_footer', 'add_footer_scripts');
function add_footer_scripts() {
      wp_register_script( 'jquery', 'https://code.jquery.com/jquery-1.12.2.min.js', false, null);
      wp_enqueue_script( 'jquery');
      wp_register_script( 'jquery-migrate', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.4.0/jquery-migrate.min.js', false, null);
      wp_enqueue_script( 'jquery-migrate');
}

//* Change the footer text
add_filter('genesis_footer_creds_text', 'sp_footer_creds_filter');
function sp_footer_creds_filter( $creds ) {
	$creds = '<span class="footer-left">[footer_copyright] &middot; SG Dornheim 1886 e.V. Abteilung Tanzsport &middot; Webdesign: <a href="http://vollwebdesign.de" title="Voll WebDesign" target="_blank">Voll WebDesign</a></span> &nbsp; <span class="footer-right"> <a href="/kontakt-vorstand" title="Kontakt & Vorstand" itemprop="url">Kontakt/Vorstand</a> &middot; <a href="/impressum/" itemprop="url" title="Impressum">Impressum</a> &middot; <a href="/datenschutz/" itemprop="url" title="Datenschutz">Datenschutz</a> &nbsp; &nbsp; <a href="https://www.facebook.com/tanzen.sgdornheim" target="_blank" itemprop="url" title="SG Dornheim Tanzsport bei Facebook"><img src="/wp-content/uploads/2016/05/facebook.png" alt="SG Dornheim Tanzsport bei Facebook" width="28"></a></span>';
	return $creds;
}

// Add To-Top button
add_action( 'genesis_before', 'genesis_to_top');
	function genesis_to_top() {
	 echo '<a href="#0" class="to-top" title="Back To Top">Top</a>';
}

//* Add Archive Settings option to Portolio CPT
add_post_type_support( 'portfolio', 'genesis-cpt-archives-settings' );
//* Define custom image size for Portfolio images in Portfolio archive
add_image_size( 'portfolio-image', 330, 230, true );

/** Customise the post info info function */
add_filter( 'genesis_post_info', 'genesischild_post_info' );
function genesischild_post_info($post_info) {
 if (!is_page()) {
 $post_info = '';
 return $post_info;
 }
}

//* Remove the post meta function
remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
