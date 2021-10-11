<?php if ( !defined( 'ABSPATH' ) ) {
	return;
}

/**
 * Class ZnHgFw_ShortcodesManager
 */
class ZnHgFw_ShortcodesManager {
	private $_registeredShortcodes = '';
	private $_includePaths         = array();
	private $_internalPath         = '';

	public function __construct() {
		$this->_internalPath = wp_normalize_path( trailingslashit( dirname( __FILE__ ) ) . 'inc' );
		array_push( $this->_includePaths, $this->_internalPath );
		$this->_includePaths = apply_filters( 'znhgfw_shortcodes_registered_paths', $this->_includePaths );
		//#! Register shortcodes
		$this->loadShortcodes();

		// Add shortcode button after media button
		add_action( 'media_buttons', array( $this, 'addMediaButton' ), 999 );

	}

	function enqueueScripts(){
		// Load the css files
		wp_enqueue_style( 'znhgtfw-shortcode-mngr-css', ZNHGFW()->getFwUrl('/assets/dist/css/shortcodes.css'), array(), ZNHGFW()->getVersion() );
		wp_enqueue_style( 'znhg-options-machine', ZNHGFW()->getFwUrl('/assets/dist/css/options.css'), array('wp-color-picker'), ZNHGFW()->getVersion() );

		// Load the main shortcodes Scripts
		wp_register_script( 'znhg-options-machine', ZNHGFW()->getFwUrl('/assets/dist/js/admin/options/options.min.js'), array( 'backbone', 'wp-color-picker', 'wp-color-picker-alpha', 'jquery-ui-slider' ), ZNHGFW()->getVersion(), true );
		wp_register_script( 'znhgtfw-shortcode-mngr-js', ZNHGFW()->getFwUrl('/assets/dist/js/admin/shortcodes/shortcodes.min.js'), array( 'backbone', 'jquery-ui-accordion', 'znhg-options-machine' ), ZNHGFW()->getVersion(), true );

		// Finally enqueue the script
		wp_enqueue_script( 'znhgtfw-shortcode-mngr-js' );
	}


	/**
	 * Will add the shortcode button after insert media button
	 */
	function addMediaButton(){
		// global $pagenow;
		$this->enqueueScripts();
		echo '<span id="znhgtfw-shortcode-modal-open" title="Add shortcode" class="button"></span>';
	}


	public function loadShortcodes() {
		$scanPaths = $this->_includePaths;
		foreach ( $scanPaths as $path ) {
			$path = trailingslashit( wp_normalize_path( $path ) );
			if ( is_dir( $path ) ) {
				$files = glob( $path . '*.php' );
				if ( !empty( $files ) ) {
					foreach ( $files as $filePath ) {
						$fn = basename( $filePath, '.php' );
						if ( $this->isShortcodeRegistered( $fn ) ) {
							continue;
						}
						require_once( $filePath );
						if ( !is_callable( array( $fn, 'render' ) ) || !is_callable( array( $fn, 'getTag' ) ) ) {
							continue;
						}
						$shTag = call_user_func( array( $fn, 'getTag' ) );
						add_shortcode( $shTag, array( $fn, 'render' ) );
					}
				}
			}
		}
	}

	public function isShortcodeRegistered( $shortcodeName ) {
		return isset( $this->_registeredShortcodes[ "$shortcodeName" ] );
	}

}

return new ZnHgFw_ShortcodesManager();
