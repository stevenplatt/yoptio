<?php if(! defined('ABSPATH')){ return; }

/**
 * Save the theme optiosn in DB
 */
function znklfw_save_theme_options( $options ){
	if( empty( $options ) || ! is_array( $options ) ) { return false; }

	// Pass options to filter
	$options = apply_filters( 'zn_options_to_save', $options );

	// Regenerate dynamic css
	generate_options_css($options);

	return update_option( ZNHGTFW()->getThemeDbId(), $options );

}

/*--------------------------------------------------------------------------------------------------
	Create dynamic css
--------------------------------------------------------------------------------------------------*/
function generate_options_css( $data = false ) {

	global $saved_options;

	/* CLEAR THE FW OPTIONS CACHE */
	$saved_options = ! empty( $data ) ? $data : false;

	/** Define some vars **/
	$uploads = wp_upload_dir();
	$css_dir = apply_filters( 'zn_dynamic_css_location', ZNHGTFW()->getThemePath( 'css/' )); // Shorten code, save 1 call
	$dynamic_css_file = $css_dir . 'dynamic_css.php';

	// Bail if the theme doesn't have a dynamic css file
	if( ! is_file( $dynamic_css_file ) ){
		return;
	}

	/** Capture CSS output **/
	ob_start();
	require($css_dir . 'dynamic_css.php');
	$css = ob_get_clean();

	// Add timestamp
	update_option( 'znhgfw_dynamic_css_time', microtime( true ) );

	$css = apply_filters('zn_dynamic_css',$css);
	$css = zn_minimify( $css );
	$zn_uploads_dir = trailingslashit( $uploads['basedir'] );
	/** Write to zn_dynamic.css file **/
	file_put_contents( $zn_uploads_dir . 'zn_dynamic.css', $css );

}
