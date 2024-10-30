<div class="item">
	<?php 
		global $product;
		$pid = get_the_ID();
		
		$attr = get_post_meta($pid, '_product_attributes', true);
		$cate = get_the_terms( $pid, 'product_cat' );
		do_action('c4d_woo_carousel_item');
		echo woocommerce_show_product_loop_sale_flash();
	?>
	<div class="image">
		<a href="<?php echo get_the_permalink(); ?>">
			<?php woocommerce_template_loop_product_thumbnail(); ?>
			<?php //echo do_shortcode('[c4d-woo-aci-image]'); ?>
		</a>

		<?php echo do_shortcode('[c4d-woo-aci-color]'); ?>
		<div class="buttons">
			<?php //echo do_shortcode('[c4d-woo-compare-button]'); ?>
			<?php //echo do_shortcode('[c4d-woo-wishlist-button]'); ?>
			<?php echo woocommerce_template_loop_add_to_cart(); ?>
			<?php //echo do_shortcode('[c4d-woo-qv]'); ?>
		</div>
	</div>
	<div class="category">
		<a href="<?php echo get_term_link( $cate[0]->term_id, 'product_cat' ); ?>">
			<h4 class="category"><?php echo $cate[0]->name; ?></h4>
		</a>
	</div>
	<a href="<?php echo get_the_permalink(); ?>"><h3 class="title"><?php echo get_the_title(); ?></h3></a>
	<div class="price">
		<?php echo woocommerce_template_loop_price(); ?>
	</div>
</div>