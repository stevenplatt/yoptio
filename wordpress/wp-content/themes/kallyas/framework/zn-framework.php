<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_upload_dir;
$wp_upload_dir = wp_upload_dir();

/**
 * Class Zn_Framework
 */
final class Zn_Framework {

	protected static $_instance = null;
	public $theme_data = array();
	public $pagebuilder;

	/**
	 * Main Zn_Framework Instance
	 *
	 * Ensures only one instance of Zn_Framework is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see ZN()
	 * @return Zn_Framework - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * @param $key
	 * @return mixed
	 */
	public function __get( $key ) {
		return $this->$key();
	}


	/**
	 * Class constructor
	 *
	 * @access public
	 */
	public function __construct() {

		// SET-UP THE FRAMEWORK BASED ON CONFIG FILE
		$config_file = apply_filters( 'zn_theme_config_file', get_template_directory().'/template_helpers/theme_config.php' );
		$theme_config = '';
		if ( file_exists( $config_file ) ) {
			include( $config_file );
			$this->theme_data = apply_filters( 'zn_theme_config', $theme_config );

			$this->define_constants();

			// Actions
			add_action( 'init', array( $this, 'init' ) );
		}


	}

	/**
	 * Define ZN Constants
	 */
	private function define_constants() {

		// TODO : BETTER WRITE THIS
		define( 'FW_URL', esc_url( get_template_directory_uri() . '/framework' ) );
	}

	public function init() {

		do_action( 'zn_framework_init' );

		// Don't load the internal PB if the PB plugin is active
		if( class_exists( 'ZnBuilder' ) ){
			return;
		}

		include( dirname( __FILE__ ) .'/pagebuilder/class-page-builder.php' );
		$this->pagebuilder = new ZnPageBuilder();
	}

	function is_debug(){
		return defined( 'ZN_FW_DEBUG' ) && ZN_FW_DEBUG == true;
	}

}


/**
 * Returns the main instance of ZnFramework to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return Zn_Framework
 */
function ZN() {
	return Zn_Framework::instance();
}


/**
 * Returns the main instance of Pagebuilder
 *
 * @since  1.0.0
 * @return Zn_Framework
 */
function ZNPB() {
	return Zn_Framework::instance()->pagebuilder;
}


// Global for backwards compatibility.
$GLOBALS['zn_framework'] = ZN();
