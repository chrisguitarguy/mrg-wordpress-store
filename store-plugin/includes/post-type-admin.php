<?php
class cd_mrg_post_type_admin_display
{
	function __construct()
	{
		// List table display
		add_filter( 'manage_edit-mrg_product_columns', array( &$this, 'column_headers' ) );
		add_filter( 'manage_edit-mrg_product_sortable_columns', array( &$this, 'add_sortable' ) );
		add_filter( 'post_row_actions', array( &$this, 'post_row_actions' ), 10, 1 );	
		add_action( 'manage_mrg_product_posts_custom_column', array( &$this, 'column_content' ), 11, 2 ); // todo: price, stock 
		add_action( 'load-edit.php', array( &$this, 'load_edit' ) ); // adds request filter and hooks into admin_head
		add_action( 'admin_print_scripts-edit.php', array( &$this, 'scripts' ) );
		add_action( 'admin_print_styles-edit.php', array( &$this, 'styles' ) );
	}
	
	
	
	function column_headers( $columns )
	{
		$columns = array(
			'cb' 	=> '<input type="checkbox" />',
			'image'	=> __( 'Image' ),
			'title'	=> __( 'Product' ),
			'type'	=> __( 'Product Type' ),
			'maker' => __( 'Manufacturer' ),
			'price' => __( 'Price' ),
			'stock' => __( 'In Stock?' ),
			'id'  => __( 'Product ID' )
		);
		return $columns;
	}
	
	function column_content( $column, $post_id )
	{
		$opts = get_option( 'mrgstore_options' );
		
		global $post;
		if( 'image' == $column )
		{
				$thumb = get_the_post_thumbnail( $post_id, 'mrg-product-admin' );
				if( $thumb )
				{
					echo $thumb;	
				}
		}
		elseif( 'type' == $column )
		{
			$terms = get_the_terms( $post_id, 'mrg_types' );
			if( ! empty( $terms ) )
			{
				$out = array();
				foreach( $terms as $term )
				{
					$out [] = sprintf( 
						'<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'mrg_types' => $term->slug ), admin_url( 'edit.php' ) ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'mrg_types', 'display' ) )
					);
				}	
				echo implode( ', ', $out );
			}
			else
			{
				_e( 'No Type Specified' );	
			}
		}
		elseif( 'maker' == $column )
		{
			$terms = get_the_terms( $post_id, 'mrg_manufacturers' );
			if( ! empty( $terms ) )
			{
				$out = array();
				foreach( $terms as $term )
				{
					$out [] = sprintf( 
						'<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'mrg_manufacturers' => $term->slug ), admin_url( 'edit.php' ) ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'mrg_manufacturers', 'display' ) )
					);
				}	
				echo implode( ', ', $out );
			}
			else
			{
				_e( 'No Manufacturer Specified' );	
			}
		}
		elseif( 'price' == $column )
		{
			if( $price = get_post_meta( $post->ID, '_mrg_price', true ) )
			{
				echo absint( $price );
			}
			else
			{
				_e( 'No price specified' );
			}
		}
		elseif( 'stock' == $column )
		{
			if( $stock = get_post_meta( $post->ID, '_mrg_stock', true ) )
			{
				$instock = 'on' == $stock ? 'In Stock' : 'Out of Stock';
				echo $instock;
			}
			else
			{
				_e( 'Unknown' );
			}
		}
		elseif( 'id' == $column )
		{
			echo absint( $post_id );	
		}
	}
	
	function post_row_actions( $actions )
	{
		global $post;
		if( 'mrg_product' != $post->post_type ) return $actions;
		if( isset( $actions['inline hide-if-no-js'] ) ) unset( $actions['inline hide-if-no-js'] );
		$actions['shortcode'] = '<a href="javascript:null(void);" onclick="mrg_get_shortcode(' . absint( $post->ID ) . ');" class="cd-mrg-get-shortcode">' . __( 'Shortcode' ) . '</a>';
		return $actions;
	}
	
	function load_edit()
	{
		add_filter( 'request', array( &$this, 'sort_request' ), 10, 1 );
		
		// for our smoke.js stuff and some styles
		add_action( 'admin_head', array( &$this, 'admin_head' ) );	
	}
	
	function add_sortable( $columns )
	{
		$columns['type'] = 'type';
		$columns['maker'] = 'maker';
		$columns['price'] = 'price';
		$columns['id'] = 'id';
		return $columns;	
	}
	
	
	function sort_request( $vars )
	{
		if( ! isset( $vars['post_type'] ) || 'mrg_product' != $vars['post_type'] || ! isset( $vars['orderby'] ) ) return $vars;
		if( 'price' == $vars['orderby'] )
		{
			$vars_new = array( 
				'meta_key' => '_mrg_price',
				'orderby'  => 'meta_value_num'
			);
			$vars = array_merge( $vars, $vars_new );
		}
		return $vars;
	}
	
	function scripts()
	{
		if( !isset( $_REQUEST['post_type'] ) || 'mrg_product' != $_REQUEST['post_type'] ) return;
		wp_enqueue_script( 'davispress-smokejs' );	
	}
	
	function styles()
	{ 
		if( !isset( $_REQUEST['post_type'] ) || 'mrg_product' != $_REQUEST['post_type'] ) return;
		wp_enqueue_style( 'davispress-smokecss' );
	}
	
	function admin_head()
	{
		?>
		<script type="text/javascript">
			function mrg_get_shortcode( post_id )
			{
				smoke.alert('[product id="' + post_id + '"]');
			}
		</script>
		<style type="text/css">
			th#image {
				width: 75px;
			}
			th#id {
				width: 100px;
			}
		</style>
		<?php
	}
}

new cd_mrg_post_type_admin_display();
