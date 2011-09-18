<?php
$opts = get_option( 'mrgstore_options' );
$links_to = isset( $opts['image_links'] ) && 'file' == $opts['image_links'] ? ' link="file"' : '';
$mrg_pt_id = has_post_thumbnail() ? get_post_thumbnail_id() : 0;
$mrg_builtins = cd_mrg_get_product_builtins();
$mrg_opts = get_option( 'mrgstore_options' );
$mrg_rating = cd_mrg_get_rating();
get_header(); 
?>

		<div id="main" class="col-full fullwidth">

		<?php if (have_posts()) : ?>
			<?php while (have_posts()) : the_post(); ?>	
	    		<div itemscope itemtype="http://schema.org/Product" id="post-<?php the_ID(); ?>" <?php post_class( 'box' ); ?>>      
					
					<h1 itemprop="name" class="title"><?php the_title(); ?></h1>
					
	            	<div class="product-left">
						
						<div class="entry">
							<?php if( isset( $mrg_opts['display_stars'] ) && 'on' == $mrg_opts['display_stars'] && $mrg_rating ): ?>
								
								<div class="aggregate-rating"  itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" >
									
									<p><?php echo cd_mrg_get_stars( $mrg_rating['rating'] ); ?></p>
									
									<meta itemprop="ratingValue" value="<?php echo $mrg_rating['rating']; ?>" />
									<meta itemprop="reviewCount" value="<?php echo absint( $mrg_rating['count'] ); ?>" />
									
								</div>
								
							<?php endif /* display stars */ ?>
							
							<?php if( isset( $mrg_builtins['instock'] ) && 'on' == $mrg_builtins['instock'] ): ?>
								<div class="product-meta-container">
									
									<?php if( isset( $mrg_builtins['price'] ) ): ?>
										<h2 class="product-price">$<?php echo $mrg_builtins['price']; ?></h2>
									<?php endif; /* price */ ?>
									
									<?php if( isset( $mrg_builtins['buy'] ) ): ?>
										<div class="buy-button"> 
											<?php echo $mrg_builtins['buy']; ?>
										</div>
									<?php endif; /* buy button */ ?>
									
									<?php if( isset( $mrg_builtins['cart'] ) ): ?>
										<div class="cart-button"> 
											<?php echo $mrg_builtins['cart']; ?>
										</div>
									<?php endif; /* cart button */ ?>
									
								</div>
							<?php endif; /* instock */ ?>
							
							<div itemprop="description">
								
								<?php the_content(); ?> 
								
							</div>
						</div>
	                    
	                    <?php if( $mrg_traits = cg_mrg_product_atts_front() ): ?>
	                    
							<?php if( isset( $mrg_opts['features_header'] ) && $mrg_opts['features_header'] ): ?>
							
								<h2 class="features-header"><?php echo esc_attr( $mrg_opts['features_header'] ); ?></h2>
								
							<?php endif; /* features header */ ?>
							
							<table class="mrg-product-traits">
								
								<?php foreach( $mrg_traits as $name => $value ): ?>
								
									<tr>
										<th scope="row"><?php echo esc_attr( $name ); ?></th>
										<td><?php echo esc_attr( $value ); ?></td>
									</tr>
																
								<?php endforeach; ?>
								
							</table>
							
						<?php endif; /* traits */ ?>
	                    
	                    <div class="product-reviews">
							<?php if( isset( $mrg_opts['reviews_header'] ) && $mrg_opts['reviews_header'] ): ?>
								<h2 class="features-header"><?php echo esc_attr( $mrg_opts['reviews_header'] ); ?></h2>
							<?php endif; /* reviews header */ ?>

							<?php comments_template( 'hijacked' ); /* Filtered in front-display.php to grab a different file */ ?>

						</div>
	                     
	                </div>
                    
                    <div class="product-right">
	                    <?php if( has_post_thumbnail() ) : ?>
		                    <div class="product-image">
		                    	
		                    	<?php the_post_thumbnail( 'hero-image', array( 'itemprop' => 'image' ) ); ?>
		                    
		                    </div>
	                    <?php endif; ?>
	                    
	                    <div class="product-gallery">
							<?php if( isset( $mrg_opts['thumbs_header'] ) && $mrg_opts['thumbs_header'] ): ?>
								<h2 class="features-header"><?php echo esc_attr( $mrg_opts['thumbs_header'] ); ?></h2>
							<?php endif /* thumbs header */ ?>
							
	                    	<?php echo do_shortcode( '[gallery' . $links_to . ' columns="2" exclude="' . $mrg_pt_id . '"]' ); ?>
	                    </div>
	                    
	                    <div class="entry product-widget-area">
	                    
							<?php dynamic_sidebar( 'product-page-widget-area' ); ?>
	                    
	                    </div>
	                 </div>
                   
                   	<div class="fix"></div>
	    
	            
	            </div>
            
            <?php endwhile; ?>
	
		<?php endif; ?>							

		</div><!--/#main-->

<?php get_footer(); ?>
