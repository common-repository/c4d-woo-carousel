<?php
/*
Plugin Name: C4D Woocommerce Carousel Product
Plugin URI: http://coffee4dev.com/
Description: Create carousel slider for product
Author: Coffee4dev.com
Author URI: http://coffee4dev.com/
Text Domain: c4d-woo-carousel
Version: 2.0.0
*/

define('C4DWC_PLUGIN_URI', plugins_url('', __FILE__));

add_action( 'wp_enqueue_scripts', 'c4d_woo_carousel_safely_add_stylesheet_to_frontsite');
add_action('wp_ajax_c4d_woo_carousel', 'c4d_woo_carousel_ajax');
add_action('wp_ajax_nopriv_c4d_woo_carousel', 'c4d_woo_carousel_ajax');
add_filter( 'plugin_row_meta', 'c4d_woo_carousel_plugin_row_meta', 10, 2 );
add_shortcode('c4d_woo_carousel', 'c4d_woo_carousel');

function c4d_woo_carousel_plugin_row_meta( $links, $file ) {
    if ( strpos( $file, basename(__FILE__) ) !== false ) {
        $new_links = array(
            'visit' => '<a href="http://coffee4dev.com">Visit Plugin Site</<a>',
            'premium' => '<a href="http://coffee4dev.com">Premium Support</<a>'
        );
        
        $links = array_merge( $links, $new_links );
    }
    
    return $links;
}

function c4d_woo_carousel_safely_add_stylesheet_to_frontsite( $page ) {
	wp_enqueue_style( 'c4d-woo-carousel-frontsite-style', C4DWC_PLUGIN_URI.'/assets/default.css' );
	wp_enqueue_script( 'c4d-woo-carousel-frontsite-plugin-js', C4DWC_PLUGIN_URI.'/assets/default.js', array( 'jquery' ), false, true ); 	
	wp_enqueue_style( 'owl-carousel', C4DWC_PLUGIN_URI.'/libs/owl-carousel/owl.carousel.css' );
	wp_enqueue_style( 'owl-carousel-theme', C4DWC_PLUGIN_URI.'/libs/owl-carousel/owl.theme.css' );
	wp_enqueue_script( 'owl-carousel', C4DWC_PLUGIN_URI.'/libs/owl-carousel/owl.carousel.js', array( 'jquery' ), false, true ); 
	
	wp_localize_script( 'jquery', 'c4d_woo_carousel',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) 
  );
}

function c4d_woo_carousel_ajax($params = array()) {
	$ajax = false;
	try {
		if (isset($_REQUEST['c4dajax'])) {
			$params['category'] = isset($_REQUEST['category']) ? esc_sql($_REQUEST['category']) : '';
			$params['count'] = isset($_REQUEST['count']) ? esc_sql($_REQUEST['count']) : 3;
		}
		
		$args = array(
	        'numberposts' 		=> isset($params['count']) ? esc_sql($params['count']) : 10 ,
	        'post_type' 		=> 'product',
	        'orderby'   		=> 'date',
        	'order'     		=> 'desc',
	        'post_status'       => 'publish'
	  );

		$category = explode(',', esc_sql($params['category']));

		if (count($category) > 0 && $params['category'] != '') {
			$args['tax_query'] = array(
		        array(
		            'taxonomy'  => 'product_cat',
		            'field'     => 'id', 
		            'terms'     => $category
		        )
		   );
		}
		
		if (isset($params['order'])) {
			$orderby = $params['order'];
	    	if ($orderby == 'best_selling_products') {
		    		$args = array_merge($args, array(
		    		'meta_key'            => 'total_sales',
					'orderby'             => 'meta_value_num'
		    	));
	    	}

	    if ($orderby == 'best-selling') {
				$args = array_merge($args, array('meta_key' => 'total_sales', 'orderby' => 'meta_value_num'));
			}

			if ($orderby == 'top-rated') {
				$args = array_merge($args, array('meta_key' => '_wc_average_rating', 'orderby' => 'meta_value_num'));
			}
		}
	   
	  $q = new WP_Query( $args );
		
		if (!$q->have_posts()) {
			$html = '<div class="c4d-woo-carousel__noti">'.esc_html__('No products!', 'c4d-woo-carousel').'</div>';
			throw new Exception($html);
		}

		ob_start();
		$template = get_template_part('c4d-woo-carousel/templates/default');
		if ($template && file_exists($template)) {
			require $template;
		} else {
			require dirname(__FILE__). '/templates/default.php';
		}
		$html = ob_get_contents();
		$html = do_shortcode($html);
		ob_end_clean();
		
		woocommerce_reset_loop();
		wp_reset_postdata();

		throw new Exception($html);
	} catch(Exception $e) {
		if (isset($_REQUEST['c4dajax'])) {
			echo $e->getMessage(); wp_die();
		}
		return $e->getMessage();
	}
}

function c4d_woo_carousel ($params) {
	$html = c4d_woo_carousel_ajax($params);
	return $html;
}


