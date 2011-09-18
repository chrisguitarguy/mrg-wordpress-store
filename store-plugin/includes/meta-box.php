<?php
class cd_mrg_meta_box_display extends davispressMetaBoxTools
{
	function __construct()
	{
		add_action( 'add_meta_boxes_mrg_product', array( &$this, 'meta_box' ) );
		add_action( 'load-post.php', array( &$this, 'load_post' ) );
		add_action( 'load-post-new.php', array( &$this, 'load_post' ) );
		add_action( 'edit_post', array( &$this, 'save' ), 10, 1 );
	}
	
	function meta_box()
	{
		add_meta_box( 
			'mrg-product-opts', 
			__( 'Product Options' ), 
			array( &$this, 'meta_box_cb' ),
			'mrg_product',
			'normal',
			'high'
		);
	}
	
	function meta_box_cb( $post )
	{
		wp_nonce_field( 'mrg_save_product', 'mrg_metabox_nonce', false );
		$this->tab_nav( array( 'mrg-cart' => __( 'Cart Settings' ), 'mrg-attributes' => __( 'Product Attributes' ) ) );
		
		$c = $this->checkbox( '_mrg_stock', __( 'In Stock?' ), $post->ID );
		$c .= $this->textinput( '_mrg_price', __( 'Price' ), $post->ID );
		$c .= $this->textarea( '_mrg_buy', __( 'Add to Cart/Buy Button' ), $post->ID );
		$c .= $this->textarea( '_mrg_cart', __( 'View Cart Button' ), $post->ID );
		$c = $this->form_table( $c );
		$this->tab( 'mrg-cart', $c, __( 'Shopping Cart Settings' ) );
		
		if( $attributes = cd_mrg_get_product_atts() )
		{
			$atts = '';
			foreach( $attributes as $name => $key )
			{
				$atts .= $this->textinput( $key, __( ucwords( $name ) ), $post->ID );
			}
			$atts = $this->form_table( $atts );
		}
		else
		{
			$atts = '<p>No attributes specified.  Please visit the <a href="' . admin_url( 'edit.php?post_type=mrg_product&page=mrg-store-options' ) . '">store options page</a>.</p>';
		}
		$this->tab( 'mrg-attributes', $atts, __( 'Product Attributes' ) );
	}
	
	function load_post()
	{
		if( 
			( isset( $_REQUEST['post'] ) && 'mrg_product' == get_post_type( $_REQUEST['post'] ) ) || 
			( isset( $_GET['post_type'] ) && 'mrg_product' == $_GET['post_type'] ) 
		)
		{
			wp_enqueue_script( 'davispress-metaboxtabsjs' );
			wp_enqueue_style( 'davispress-metaboxtabscss' );
		}
	}
	
	function save( $id )
	{
		if( ! isset( $_POST['mrg_metabox_nonce'] ) || ! wp_verify_nonce( $_POST['mrg_metabox_nonce'], 'mrg_save_product' ) ) return;
		if( ! current_user_can( 'edit_post' ) ) return;
		
		$on = isset( $_POST['_mrg_stock'] ) && $_POST['_mrg_stock'] ? 'on' : 'off';
		update_post_meta( $id, '_mrg_stock', esc_attr( $on ) );
			
		if( isset( $_POST['_mrg_price'] ) )
			update_post_meta( $id, '_mrg_price', esc_attr( strip_tags( $_POST['_mrg_price'] ) ) );
			
		if( isset( $_POST['_mrg_buy'] ) )
			update_post_meta( $id, '_mrg_buy', $_POST['_mrg_buy'] );
		
		if( isset( $_POST['_mrg_cart'] ) )
			update_post_meta( $id, '_mrg_cart', $_POST['_mrg_cart'] );
			
		$opts = get_option( 'mrgstore_options' );
		if( ! isset( $opts['atts'] ) ) return;
		
		foreach( cd_mrg_get_product_atts() as $name => $key )
		{
			if( isset( $_POST[$key] ) )
				update_post_meta( $id, $key, esc_attr( strip_tags( $_POST[$key] ) ) );
		}
	}
	
} // end class;

new cd_mrg_meta_box_display();
