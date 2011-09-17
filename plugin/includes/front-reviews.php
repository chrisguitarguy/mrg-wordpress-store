<?php
/**
 * This file takes care of all the review rendering.  That includes
 * custom callbacks for comment lists and a few additional actions for the
 * comment form
 */

/**
 * Hack to only add the comment field to our reviews
 * Seemed safer than relying on the $post variable
 */
add_action( 'template_redirect', 'cd_mrg_fields_filter_hack' );
function cd_mrg_fields_filter_hack()
{
	if( ! is_singular( 'mrg_product' ) ) return;
	add_action( 'comment_form_after_fields', 'cd_mrg_add_comment_form_fields' );
	add_action( 'comment_form_logged_in_after', 'cd_mrg_add_comment_form_fields' );
}

function cd_mrg_add_comment_form_fields()
{
	?>
		<p>
			<label for="mrg_review_title">Review Title</label>
			<input type="text" name="mrg_title" id="mrg_review_title" size="30" />
		</p>
		
		<label for="mrg_raiting">Rating</label>
		<p id="mrg-stars"></p>
	<?php
}

/**
 * Save our rating meta data
 */
add_action( 'comment_post', 'cd_mrg_save_product_rating', 10, 1 );
function cd_mrg_save_product_rating( $comment_id )
{
	if( isset( $_POST['mrg_rating'] ) )
		update_comment_meta( $comment_id, 'mrg_rating', esc_attr( $_POST['mrg_rating'] ) ); 
	if( isset( $_POST['mrg_title'] ) )
		update_comment_meta( $comment_id, 'mrg_title', esc_attr( $_POST['mrg_title'] ) );
}

/**
 * Add our star js
 */
add_action( 'wp_print_scripts', 'cd_mrg_add_stars_js' );
function cd_mrg_add_stars_js()
{
	if( ! is_singular( 'mrg_product' ) ) return;
	wp_enqueue_script( 'mrg-stars-js', MRGSTORE_URL . 'js/stars.js', array( 'prototype' ), NULL, true );
}

/**
 * Add the stars css
 */
add_action( 'wp_print_styles', 'cd_mrg_add_stars_css' );
function cd_mrg_add_stars_css()
{
	if( ! is_singular( 'mrg_product' ) ) return;
	wp_enqueue_style( 'mrg-stars-css', MRGSTORE_URL . 'css/stars.css', array(), NULL, 'all' );
}

/**
 * Make the stars work
 */
add_action( 'wp_footer', 'cd_mrg_add_stars_functionality' );
function cd_mrg_add_stars_functionality()
{
	?>
	<script type="text/javascript">
		new Starry('mrg-stars', {name: 'mrg_rating', startAt: 5, sprite: "<?php echo MRGSTORE_URL; ?>images/stars.gif"});
	</script>
	<?php
}

/**
 * The comment callback function for wp_list_comments
 */
function cd_mrg_comment_cb( $comment, $args, $depth )
{
	$GLOBALS['comment'] = $comment;
	$rating = get_comment_meta( $comment->comment_ID, 'mrg_rating', true );
	$title = get_comment_meta( $comment->comment_ID, 'mrg_title', true );
	?>
	<div itemprop="reviews" itemscope itemtype="http://schema.org/Review" <?php comment_class( 'review' ); ?> id="review-<?php comment_ID() ?>">
		
		<?php if( $title ): ?>
			<h4 class="review-title" itemprop="name"><?php echo esc_attr( $title ); ?></h2>
		<?php endif; ?>
		
		<div class="review-image" itemprop="image">
			<?php echo get_avatar($comment,$size='48'); ?>
		</div>
		
		<div class="review-meta">
			<?php printf( '<h4 class="review-author"> by <span itemprop="name">%s</span></h4>', get_comment_author_link() ); ?>
			
			<?php if( absint( $rating ) ): ?>
				<p class="review-stars"> 
					<?php echo cd_mrg_get_stars( $rating ); ?>
				</p>
			<?php endif; ?>
			
			<?php /* What follows are some meta values for schema markup */ ?>
			<meta itemprop="worstRating" value="0" />
			<meta itemprop="bestRating" value="5" />
			<meta itemprop="ratingValue" value="<?php echo absint( $rating ); ?>" />
			<meta itemprop="datePublished" value="<?php comment_date( 'Y-m-d' ); ?>" />
		</div>
		
		<div class="review-content" itemprop="description">
		
			<?php comment_text() ?>
			
		</div>
		
	</div>
	<?php
}

/**
 * Prettify our stars
 */
add_action( 'wp_head', 'cd_mrg_star_prettify' );
function cd_mrg_star_prettify()
{
	?>
		<style type="text/css">
			span.mrg-star {
				display: inline-block;
				margin-right:2px;
				width: 30px;
				height: 30px;
				background: url('<?php echo MRGSTORE_URL; ?>images/stars.gif') no-repeat 0 -60px;
			}
			span.mrg-star-blank {
				display: inline-block;
				margin-right:2px;
				width: 30px;
				height: 30px;
				background: url('<?php echo MRGSTORE_URL; ?>images/stars.gif') no-repeat 0 -30px;
			}
		</style>
	<?php
}
