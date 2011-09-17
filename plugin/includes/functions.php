<?php
/**
 * this file contains helper functions used throughout the store
 * plugin
 */
 
/*
 * Returns a list of the product traits in name => postmeta_key form
 */
function cd_mrg_get_product_atts()
{
	$opts = get_option( 'mrgstore_options' );
	$traits = isset( $opts['atts'] ) && $opts['atts'] ? explode( "\n", $opts['atts'] ) : false;
	if( ! $traits ) return false;
	
	$out = array();
	foreach( $traits as $t )
	{
		$new = trim( str_replace( ' ', '_', $t ) );
		$new = '_mrg_' . $new;
		$out[$t] = $new;
	}
	return $out;
}

/*
 * Returns the array of post meta names and values for the front end
 */
function cg_mrg_product_atts_front( $post_id = false )
{
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();
	$meta = get_post_custom( $post_id );
	$traits = cd_mrg_get_product_atts();
	$out = array();
	foreach( $traits as $name => $key )
	{
		if( isset( $meta[$key][0] ) && $meta[$key][0] )
		{
			$out[$name] = $meta[$key][0];
		}
	}
	return $out;
}

/*
 * Get the array of built in post meta values
 */
function cd_mrg_get_product_builtins( $post_id = false )
{
	$meta = get_post_custom( $post_id );
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();
	$meta = get_post_custom( $post_id );
	$out = array();
	if( isset( $meta['_mrg_stock'][0] ) ) $out['instock'] = esc_attr( $meta['_mrg_stock'][0] );
	if( isset( $meta['_mrg_price'][0] ) ) $out['price'] = esc_attr( $meta['_mrg_price'][0] );
	if( isset( $meta['_mrg_buy'][0] ) ) $out['buy'] = $meta['_mrg_buy'][0];
	if( isset( $meta['_mrg_cart'][0] ) ) $out['cart'] = $meta['_mrg_cart'][0];
	return $out;
}

/**
 * Get the aggregate rating out of the comments
 */
function cd_mrg_get_rating( $post_id = false )
{
	if( ! is_singular( 'mrg_product' ) ) return;
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();
	if( ! $post_id ) return false;
	
	$comments = get_comments(array(
		'post_id' => $post_id,
		'status'  => 'approved'
	));
	// bail if we don't have comments yet
	if( ! $comments ) return false;
	
	$rating = 0;
	foreach( $comments as $c )
	{
		$r = get_comment_meta( $c->comment_ID, 'mrg_rating', true );
		$rating += absint( $r );
	}
	$count = count( $comments );
	$rating = $rating / $count;
	return array( 'rating' => $rating, 'count' => $count );
}

function cd_mrg_get_stars( $count )
{
	$out = '';
	$count = absint( $count );
	$remain = 5 - $count;
	for( $i = 1; $i <= $count; $i++ )
	{
		$out .= '<span class="mrg-star"></span>';
	}
	for( $i = 1; $i <= $remain; $i++ )
	{
		$out .= '<span class="mrg-star-blank"></span>';
	}
	return $out;
}
