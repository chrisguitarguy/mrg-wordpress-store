<?php
class cd_mrg_admin_options_page extends davispressAdminTools
{
	protected $setting = 'mrgstore_options';
	
	function __construct()
	{
		$this->init();	
	}
	
	function menu_page()
	{
		$page = add_submenu_page( 
			'edit.php?post_type=mrg_product', 
			__( 'My Rare Guitars Store Options' ) , 
			__( 'Store Options' ), 
			'manage_options', 
			'mrg-store-options', 
			array( &$this, 'menu_page_cb' ) 
		);
	}
	
	function menu_page_cb()
	{
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2><?php _e( 'Store Options' ); ?></h2>
			<?php settings_errors( $this->setting ); ?>
			<form action="<?php echo admin_url( 'options.php' ); ?>" method="post">
				<?php
					settings_fields( $this->setting );
					
					$o = $this->textarea( 'atts', __( 'Product Attributes' ), __( 'One attribute per line' ) );
					$linkto = array( 'attachment_page' => __( 'Attachment Pages' ), 'file' => __( 'File URL' ) );
					$o .= $this->select( 'image_links', __( 'Gallery images link to...' ), $linkto );
					$o .= $this->textinput( 'features_header', __( 'Features (attributes) Header' ) );
					$o .= $this->textinput( 'thumbs_header', __( 'Gallery Header' ) );
					$o .= $this->textinput( 'reviews_header', __( 'Reviews Header' ) );
					$o .= $this->checkbox( 'display_stars', __( 'Display Aggregate Rating' ) );
					
					echo $this->form_table( $o );
					
					echo $this->submit();
				
				?>
			</form>
		</div>
		<?php
	}
	
	function register_settings()
	{
		register_setting( $this->setting, $this->setting, array( &$this, 'clean_data' ) );
	}
	
	function clean_data( $in )
	{
		$out = array();
		$out['atts'] = isset( $in['atts'] ) && $in['atts'] ? esc_attr( $in['atts'] ) : '';
		$out['image_links'] = isset( $in['image_links'] ) && $in['image_links'] ? esc_attr( $in['image_links'] ) : '';
		$out['features_header'] = isset( $in['features_header'] ) ? esc_attr( $in['features_header']  ) : '';
		$out['thumbs_header'] = isset( $in['thumbs_header'] ) ? esc_attr( $in['thumbs_header'] ) : '';
		$out['reviews_header'] = isset( $in['reviews_header'] ) ? esc_attr( $in['reviews_header'] ) : '';
		$out['display_stars'] = isset( $in['display_stars'] ) && $in['display_stars'] ? 'on' : 'off';
		return $out;
	}
} // end class

new cd_mrg_admin_options_page();
