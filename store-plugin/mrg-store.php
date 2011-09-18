<?php
/*
Plugin Name: My Rare Guitars Store
Plugin URI: http://www.christopherguitar.net/wordpress/
Description: A custom built store especially for MyRareGuitars.com
Version: 1.0
Author: Christopher Davis
Author URI: http://www.christopherguitar.net/
License: GPL2
*/

define( 'MRGSTORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'MRGSTORE_URL', plugin_dir_url( __FILE__ ) );
define( 'MRGSTORE_INC', MRGSTORE_PATH . 'includes/' );
define( 'MRGSTORE_THEME', MRGSTORE_PATH . 'theme/' );

// load some shared files
require_once( MRGSTORE_INC . 'post-type.php' );
require_once( MRGSTORE_INC . 'functions.php' );

if( is_admin() )
{
	// Load the davispress library (plugin framework)
	require_once( MRGSTORE_PATH . 'library/davispress-library.php' );
	$mrgs_library = new davispressLibrary();
	
	require_once( MRGSTORE_INC . 'post-type-admin.php' );
	require_once( MRGSTORE_INC . 'meta-box.php' );
	require_once( MRGSTORE_INC . 'admin-page.php' );
}
else
{
	require_once( MRGSTORE_INC . 'front-display.php' );
	require_once( MRGSTORE_INC . 'front-reviews.php' );
}


add_action( 'after_setup_theme', 'cd_mrg_add_image_size' );
function cd_mrg_add_image_size()
{
	// Add our huge image size.
	add_image_size( 'hero-image', 450, 9999 );
	
	// thumbnail for admin view
	add_image_size( 'mrg-product-admin', 50, 9999 );
	
	// Let's just make sure thumbnails get added (f*cking woo themes)
	add_theme_support( 'post-thumbnails', array( 'mrg_product' ) );
}

/*
 * Register a new sidebar just for product pages
 */
add_action( 'widgets_init', 'cd_mrg_register_product_sidebars' );
function cd_mrg_register_product_sidebars()
{
	register_sidebar(array(
		'name'          => __( 'Product Page Widget Area' ),
		'id'            => 'product-page-widget-area',
		'description'   => 'Displayed below the product thumbnails on all singular product pages',
		'before_widget' => '<div id="%1$s" class="product-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="featured-header">',
		'after_title'   => '</h2>'
	));
}


