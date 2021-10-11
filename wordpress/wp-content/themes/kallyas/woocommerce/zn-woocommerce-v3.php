<?php

// Add Classes
add_filter('woocommerce_single_product_image_gallery_classes', 'zn_woocommerce_single_product_image_gallery_classes');
function zn_woocommerce_single_product_image_gallery_classes($classes){

	$classes[] = 'zn-wooProdGallery';

	if( zget_option('zn_woo_enable_zoom', 'zn_woocommerce_options', false, 'no') == 'yes' ){
		$classes[] = 'zn-wooZoomGallery';
	}

	if( zget_option('zn_woo_enable_slider', 'zn_woocommerce_options', false, 'no') == 'yes' ){
		$classes[] = 'zn-wooSlickGallery';
	}
	else {
		$classes[] = 'zn-wooSlickGallery--disabled';
	}

	return $classes;
}

// Load Zooming Script
add_action( 'wp_enqueue_scripts', 'zn_load_zoom_script', 99 );
function zn_load_zoom_script() {
	if( zget_option('zn_woo_enable_zoom', 'zn_woocommerce_options', false, 'no') == 'yes' ){
		wp_enqueue_script( 'zoom');
	}
}

