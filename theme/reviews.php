<div class="product-reviews">
	<?php if( isset( $mrg_opts['reviews_header'] ) && $mrg_opts['reviews_header'] ): ?>
		<h2 class="features-header"><?php echo esc_attr( $mrg_opts['reviews_header'] ); ?></h2>
	<?php endif; /* reviews header */ ?>

	<?php 
	if ( have_comments() ):
	wp_list_comments( array( 'type' => 'comment', 'callback' => 'cd_mrg_comment_cb', 'reverse_top_level'    => true ) ); 
	endif;
	?>

	<?php 
	comment_form(array(
		'id_form' 	   		   => 'review-form',
		'title_reply'  		   => 'Review this Product',
		'label_submit' 		   => 'Submit Review',
		'comment_notes_after'  => '',
		'comment_field'        => '<label for="comment">Review</label><p><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
		'comment_notes_before' => '<p class="comment-notes">Your email address will not be published. Required fields: *</p>'
	)); 
	?>

</div>
