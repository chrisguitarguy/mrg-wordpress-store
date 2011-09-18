<?php
/*
 * This file handles all the scripts and styles 
 * for the front end display of products and archives
 */
 
/*
 * Enqueue our light box
 * but only if we're on the mrg product pages
 * and we're linking to image files
 */
add_action( 'wp_print_scripts', 'cd_mrg_singular_front_scripts' );
function cd_mrg_singular_front_scripts()
{
	if( is_admin() || ! is_singular( 'mrg_product' ) ) return;
	$opts = get_option( 'mrgstore_options' );
	if( isset( $opts['image_links'] ) && 'file' != $opts['image_links'] ) return;
	
	$dev = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.dev' : '';
	wp_enqueue_script( 'mrgproduct-lightbox', MRGSTORE_URL . 'js/lightbox' . $dev . '.js', array( 'jquery' ), NULL, true );
}

/*
 * Add the javascript that makes the lightbox work to our footer
 */
add_action( 'wp_footer', 'cd_mrg_singular_front_footer' );
function cd_mrg_singular_front_footer()
{
	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('div.gallery a').lightBox({
				imageLoading: '<?php echo MRGSTORE_URL; ?>images/lightbox-ico-loading.gif',
				imageBtnClose: '<?php echo MRGSTORE_URL; ?>images/lightbox-btn-close.gif',
				imageBtnPrev: '<?php echo MRGSTORE_URL; ?>images/lightbox-btn-prev.gif',
				imageBtnNext: '<?php echo MRGSTORE_URL; ?>images/lightbox-btn-next.gif',
				imageBlank: '<?php echo MRGSTORE_URL; ?>images/lightbox-blank.gif'
			});
		});
	</script>
	<?php
}

/*
 * Add the styles for the lightbox and our product display
 */
add_action( 'wp_print_styles', 'cd_mrg_singular_front_styles' );
function cd_mrg_singular_front_styles()
{
	if( is_admin() || ! is_singular( 'mrg_product' ) ) return;
	$opts = get_option( 'mrgstore_options' );
	if( isset( $opts['image_links'] ) && 'file' == $opts['image_links'] )
	{	
		// Lightbox
		wp_enqueue_style( 'mrgproduct-lightbox-style', MRGSTORE_URL . 'css/lightbox.css', array(), NULL, 'all' );
	}
	
	// Normal styles
	wp_enqueue_style( 'mrgproduct-singular-css', MRGSTORE_URL . 'css/singular.css', array(), NULL, 'all' );
}

/**
 * Change the included file on singular mrg_product pages
 * mrg_product posttype archives, and the two taxonomies
 * registered for the store
 * 
 * @uses is_singular()
 */
add_filter( 'template_include', 'cd_mrg_template_hijack' );
function cd_mrg_template_hijack( $template )
{
	if( is_singular( 'mrg_product' ) )
	{
		$template = MRGSTORE_THEME . 'singular.php';
	}
	return $template;
}

/**
 * Change our comments file for reviews
 * 
 * @uses is_singular()
 */
add_filter( 'comments_template', 'cd_mrg_comments_hijack' );
function cd_mrg_comments_hijack( $file )
{
	if( is_singular( 'mrg_product' ) )
	{
		$file = MRGSTORE_THEME . 'reviews.php';
	}
	return $file;
}
