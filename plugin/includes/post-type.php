<?php
class cd_mrg_store_post_type
{
	function __construct()
	{
		add_action( 'init', array( &$this, 'register_post_type' ) );
		add_action( 'init', array( &$this, 'register_type_taxonomy' ), 11 );
		add_action( 'init', array( &$this, 'register_manufacturer_taxonomy' ), 11 );
	}
	
	function register_post_type()
	{
		$labels = array(
			'name' 				 => __( 'Products' ),
			'singular_name'		 => __( 'Product' ),
			'add_new'			 => _x( 'Add New Product', 'mrg_product' ),
			'all_items'			 => __( 'All Products' ),
			'add_new_item'		 => __( 'Add New Product' ),
			'edit_item'			 => __( 'Edit Product' ),
			'new_item'			 => __( 'New Product' ),
			'view_item'			 => __( 'View Product' ),
			'search_items'		 => __( 'Search Products' ),
			'not_found'			 => __( 'No Products Found' ),
			'not_found_in_trash' => __( 'No products found in the trash' ),
			'menu_name'			 => __( 'Store' )	
		);
		
		$args = array(
			'labels' 			=> $labels,
			'description' 		=> __( 'The post type for all product the My Rare Guitars Store.' ),
			'public' 			=> true,
			'show_in_menu'		=> true,
			'menu_position'		=> 5,
			'capability_type'	=> 'post',
			'supports'			=> array( 'title', 'editor', 'thumbnail', 'custom-fields', 'comments' ),
			'has_archive'		=> true,
			'rewrite' 			=> array( 'slug' => 'store', 'with_front' => true ),
			'show_in_nav_menus' => false,
		);
		
		register_post_type( 'mrg_product', $args );
	}
	
	function register_type_taxonomy()
	{
		$labels = array(
			'name'					=> _x( 'Product Types', 'taxonomy general name' ),
			'singular_name'			=> _x( 'Product Type', 'taxonomy singular name' ),
			'search_items'			=> __( 'Search Product Types' ),
			'popular_items'			=> __( 'Popular Product Types' ),
			'all_items'				=> __( 'All Product Types' ),
			'parent_item'			=> __( 'Parent Type' ),
			'parent_item_colon' 	=> __( 'Parent Type:' ),
			'edit_item'				=> __( 'Edit Product Type' ),
			'update_item'			=> __( 'Update Product Type' ),
			'add_new_item'			=> __( 'Add New Product Type' ),
			'new_item_name'			=> __( 'New Type Name' ),
			'choose_from_most_used'	=> __( 'Choose from the most used product types' ),
			'menu_name'				=> __( 'Product Types' )
		);
		
		$args = array(
			'labels' 		=> $labels,
			'public'		=> true,
			'hierarchical' 	=> true,
			'rewrite'		=> array( 'slug' => 'store/type', 'with_front' => true, 'hierarchical' => true ),
		);
		
		register_taxonomy( 'mrg_types', 'mrg_product', $args );
	}
	
	function register_manufacturer_taxonomy()
	{
		$labels = array(
			'name'					=> _x( 'Product Manufacturers', 'taxonomy general name' ),
			'singular_name'			=> _x( 'Product Manufacturer', 'taxonomy singular name' ),
			'search_items'			=> __( 'Search Manufacturers' ),
			'popular_items'			=> __( 'Popular Manufacturers' ),
			'all_items'				=> __( 'All Manufacturers' ),
			'parent_item'			=> __( 'Parent Manufacturer' ),
			'parent_item_colon' 	=> __( 'Parent Manufacturer:' ),
			'edit_item'				=> __( 'Edit Manufacturer' ),
			'update_item'			=> __( 'Update Manufacturer' ),
			'add_new_item'			=> __( 'Add New Manufacturer' ),
			'new_item_name'			=> __( 'New Manufacturer Name' ),
			'choose_from_most_used'	=> __( 'Choose from the most used manufacturers' ),
			'menu_name'				=> __( 'Manufacturers' )
		);
		
		$args = array(
			'labels' 		=> $labels,
			'public'		=> true,
			'hierarchical' 	=> true,
			'rewrite'		=> array( 'slug' => 'store/manufacturer', 'with_front' => true, 'hierarchical' => true ),
		);
		
		register_taxonomy( 'mrg_manufacturers', 'mrg_product', $args );
	}
}

new cd_mrg_store_post_type();