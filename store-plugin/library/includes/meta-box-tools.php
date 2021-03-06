<?php

if( ! class_exists( 'davispressMetaBoxTools' ) ):
abstract class davispressMetaBoxTools extends davispressFormFields
{
	protected function textinput( $id, $label, $post_id, $default = '', $class = 'widefat' )
	{
		$meta = get_post_meta( $post_id, $id, true );
		$value = $meta ? $meta : $default;
		$field = $this->get_text_input( $id, esc_attr( $value ) );
		$label = $this->get_label( $id, $label );
		return $this->form_row( $label, $field );
	}
	
	protected function image( $id, $label, $post_id, $click = '', $default = '' )
	{
		$meta = get_post_meta( $post_id, $id, true );
		$value = $meta ? $meta : $default;
		if( ! $click ) $click = __( 'Upload an Image' );
		$field = $this->get_image_input( $id, esc_url( $value ), $click );
		$label = $this->get_label( $id, $label );
		return $this->form_row( $label, $field );
	}
	
	protected function checkbox( $id, $label, $post_id, $default = 'off' )
	{
		$meta = get_post_meta( $post_id, $id, true );
		$value = $meta ? $meta : $default;
		$field = $this->get_checkbox_input( $id, esc_attr( $value  ) );
		$label = $this->get_label( $id, $label );
		return $this->form_row( $label, $field );
	}
	
	protected function textarea( $id, $label, $post_id, $default = '' )
	{
		$meta = get_post_meta( $post_id, $id, true );
		$value = $meta ? $meta : $default;
		$field = $this->get_textarea( $id, esc_attr( $value ) );
		$label = $this->get_label( $id, $label, false );
		return $this->form_row( $label, $field );
	}
	
	protected function alt_checkbox( $id, $label, $post_id, $default = 'off' )
	{
		$meta = get_post_meta( $post_id, $id, true );
		$value = $meta ? $meta : $default;
		$field = $this->get_checkbox_input( $id, esc_attr( $value ) );
		$label = $this->get_label( $id, $label );
		return '<p>' . $field . $label . '</p>';
	}
	
	protected function radio( $id, $label, $options, $post_id, $default = '' )
	{
		$meta = get_post_meta( $post_id, $id, true );
		$value = $meta ? $meta : $default;
		$fields = $this->get_radio_buttons( $id, $options, esc_attr( $value ) );
		return $this->form_row( $label, $fields );
	}
	
	protected function alt_radio( $id, $label, $options, $post_id, $default = '' )
	{
		$meta = get_post_meta( $post_id, $id, true );
		$value = $meta ? $meta : $default;
		$fields = $this->get_radio_buttons( $id, $options, esc_attr( $value ) );
		$out = "<div class='pmg-radio-container'>";
		$out .= "<p>" . $label . "</p>";
		$out .= $fields;
		$out .= '</div>';
		return $out;
	}
	
	protected function tab( $id, $content, $title = false )
	{
		?>
		<div class="davispress-tab" id="<?php echo esc_attr( $id ); ?>-tab">
			<?php if( $title ): ?>
				<h4><?php echo esc_attr( $title ); ?></h4>
			<?php endif; ?>
			<?php echo $content; ?>
		</div>	
		<?php
	}
	
	protected function tab_nav( $tabs = array( 'id' => 'label' ) )
	{
		?>
		<ul class="davispress-meta-box-nav">
			<?php
			foreach( $tabs as $id => $label )
			{
				echo '<li id="' . esc_attr( $id ) . '"><a href="javascript:null(void);">' . $label . '</a></li>';
			}
			do_action( 'davispress_meta_box_nav_tabs' );
			?>
		</ul>
		<?php
	}
} // end class
endif;