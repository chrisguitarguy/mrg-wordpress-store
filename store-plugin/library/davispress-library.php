<?php
if( ! class_exists( 'davispressLibrary' ) ):
class davispressLibrary
{	
	function __construct()
	{
		add_action( 'plugins_loaded', array( &$this, 'set_constants' ), 1 );
		add_action( 'init', array( &$this, 'register_scripts' ) );
		add_action( 'init', array( &$this, 'register_styles' ) );
		
		// Load our admin classes
		define( 'DAVISPRESS_LIBRARY_PATH', dirname( __FILE__ ) . 'includes/' );
		require_once( DAVISPRESS_LIBRARY_PATH . '/form-fields.php' );
		require_once( DAVISPRESS_LIBRARY_PATH . '/admin-page-tools.php' );
		require_once( DAVISPRESS_LIBRARY_PATH . '/meta-box-tools.php' );
	}
	
	function set_constants()
	{
		define( 'DAVISPRESS_LIBRARY_URL', plugin_dir_url( __FILE__ ) );
		define( 'DAVISPRESS_LIBRARY_CSS', DAVISPRESS_LIBRARY_URL . 'css/' );
		define( 'DAVISPRESS_LIBRARY_JS', DAVISPRESS_LIBRARY_URL . 'js/' );
		
		define( 'DAVISPRESS_LIBRARY_VER', '1.0' );
	}

	
	function register_scripts()
	{
		$dev = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';
		
		// Thickbox hijacking scrip
		wp_register_script( 
			'davispress-thickbox-hijack', 
			DAVISPRESS_LIBRARY_JS . 'thickbox-hijack' . $dev . '.js', 
			array( 'jquery' ), 
			DAVISPRESS_LIBRARY_VER, 
			true 
		);
		
		// Smoke.js
		wp_register_script( 
			'davispress-smokejs',
			DAVISPRESS_LIBRARY_JS . 'smoke' . $dev . '.js',
			array(),
			DAVISPRESS_LIBRARY_VER
		);
		
		// Meta Box tab nav
		wp_register_script( 
			'davispress-metaboxtabsjs',
			DAVISPRESS_LIBRARY_JS . 'metabox-tabs' . $dev . '.js',
			array( 'jquery' ),
			DAVISPRESS_LIBRARY_VER,
			true
		);
	}
	
	function register_styles()
	{
		$dev = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';
		
		// Smoke.js
		wp_register_style(
			'davispress-smokecss',
			DAVISPRESS_LIBRARY_CSS . 'smoke' . $dev . '.css',
			array(),
			DAVISPRESS_LIBRARY_VER,
			'screen'
		);
		
		// meta box tabs
		wp_register_style(
			'davispress-metaboxtabscss',
			DAVISPRESS_LIBRARY_CSS . 'metabox-tabs' . $dev . '.css',
			array(),
			DAVISPRESS_LIBRARY_VER,
			'screen'
		);
	}
}
endif;
