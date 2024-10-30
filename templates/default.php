<?php 
$uid = 'c4d-woo-carousel-'.uniqid();
?>
<script>
	(function($){
		$(document).ready(function(){
			mc4dSlider['<?php echo $uid; ?>'] = <?php echo json_encode($params); ?>;
		});	
	})(jQuery);
</script>
<div class="c4d-woo-carousel">
	<div class="c4d-woo-carousel__categories">
		<span class="active" data-category="<?php echo esc_attr($params['category']); ?>"><?php esc_html_e('All', 'c4d-woo-carousel'); ?></span>
		<?php 
			$categories = explode(',', $params['category']);
			if (is_array($categories)) {
				foreach ($categories as $key => $value) {
					$cate = get_cat_name($value);
					if ($cate) {
						echo '<span data-category="'.esc_attr((int)$value).'">'.$cate.'</span>';	
					}
				}
			}
		?>
	</div>
	<div class="c4d-woo-carousel__slider">
		<ul id="<?php echo esc_attr($uid); ?>">
			<?php while ( $q->have_posts() ) : ?>
				<?php $p = $q->the_post(); ?>
				<?php wc_get_template( 'content-product.php'); ?>
			<?php endwhile; // end of the loop. ?>
		</ul>
	</div>
	<div class="c4d-woo-carousel__loading">
		<div class="mask-loading">
			<div class="spinner">
		    	<div class="double-bounce1"></div>
		    	<div class="double-bounce2"></div>
		  	</div>
		</div>
	</div>
</div>