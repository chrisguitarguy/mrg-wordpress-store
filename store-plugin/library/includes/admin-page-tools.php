<?php
if( ! class_exists( 'davispressAdminTools' ) ):

abstract class davispressAdminTools extends davispressFormFields
{
	protected function init()
	{
		add_action( 'admin_menu', array( &$this, 'menu_page' ) );
		add_action( 'admin_init', array( &$this, 'register_settings' ) );	
	}
	
	abstract public function menu_page();
	abstract public function menu_page_cb();
	abstract public function register_settings();
	abstract public function clean_data( $in );
	
	protected function textinput( $id, $label, $default = '' )
	{
		$opts = get_option( $this->setting );
		$value = isset( $opts[$id] ) ? $opts[$id] : $default;
		$field = $this->get_text_input( $id, esc_attr( $value ) );
		$label = $this->get_label( $id, $label );
		return $this->form_row( $label, $field );
	}
	
	protected function image( $id, $label, $click, $default = '' )
	{
		$opts = get_option( $this->setting );
		$value = isset( $opts[$id] ) ? $opts[$id] : $default;
		$field = $this->get_image_input( $id, esc_url( $value ), $click );
		$label = $this->get_label( $id, $label );
		return $this->form_row( $label, $field );
	}
	
	protected function checkbox( $id, $label, $default = 'off' )
	{
		$opts = get_option( $this->setting );
		$value = isset( $opts[$id] ) ? $opts[$id] : $default;
		$field = $this->get_checkbox_input( $id, esc_attr( $value  ) );
		$label = $this->get_label( $id, $label );
		return $this->form_row( $label, $field );
	}
	
	protected function alt_checkbox( $id, $label, $default = 'off' )
	{
		$opts = get_option( $this->setting );
		$value = isset( $opts[$id] ) ? $opts[$id] : $default;
		$field = $this->get_checkbox_input( $id, esc_attr( $value ) );
		$label = $this->get_label( $id, $label );
		return '<p>' . $field . $label . '</p>';
	}
	
	function textarea( $id, $label, $help = '', $default = '' )
	{
		$opts = get_option( $this->setting );
		$value = isset( $opts[$id] ) ? $opts[$id] : $default;
		$field = $this->get_textarea( $id, esc_attr( $value ), 'davispress-textarea' );
		if( $help ) $field .= $this->get_help( $help );
		$label = $this->get_label( $id, $label );
		return $this->form_row( $label, $field );
	}
	
	protected function radio( $id, $label, $options, $default = '' )
	{
		$opts = get_option( $this->setting );
		$value = isset( $opts[$id] ) ? $opts[$id] : $default;
		$fields = $this->get_radio_buttons( $id, $options, esc_attr( $value ) );
		return $this->form_row( $label, $fields );
	}
	
	protected function alt_radio( $id, $label, $options, $default = '' )
	{
		$opts = get_option( $this->setting );
		$value = isset( $opts[$id] ) ? $opts[$id] : $default;
		$fields = $this->get_radio_buttons( $id, $options, $value );
		$out = "<div class='pmg-radio-container'>";
		$out .= "<p>" . $label . "</p>";
		$out .= $fields;
		$out .= '</div>';
		return $out;
	}
	
	protected function select( $id, $label, $options, $help = '', $default = '' )
	{
		$opts = get_option( $this->setting );
		$value = isset( $opts[$id] ) ? $opts[$id] : $default;
		$field = $this->get_select( $id, $options, $value );
		if( $help ) $trail .= $this->get_help( $help );
		$label = $this->get_label( $id, $label );
		return $this->form_row( $label, $field );
	}
	
	protected function form_row( $label, $field )
	{
		return '<tr class="form-field"><th scope="row" valign="top">' . $label . '</th><td>' . $field . '</td></tr>';	
	}
	
	
	protected function get_help( $text )
	{
		return 	'<p class="description">' . $text . '</p>';
	}
	
	protected function submit( $text = 'Save Settings' )
	{
		$text = __( $text );
		return "<p><input type='submit' class='button-primary' value='{$text}' /></p>";
	}
	
	public function thickbox_scripts()
	{
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'davispress-thickbox-hijack' );
	}
	
	public function thickbox_styles()
	{
		wp_enqueue_style( 'thickbox' );
	}
	
	public function postbox_scripts()
	{
		wp_enqueue_script( 'postbox' );
		wp_enqueue_script( 'dashboard' );	
	}
	
	public function postbox_styles()
	{
		wp_enqueue_style( 'dashboard' );
		wp_enqueue_style( 'thickbox' );
		wp_enqueue_style( 'global' );
		wp_enqueue_style( 'wp-admin' );
	}
} // end class

endif;