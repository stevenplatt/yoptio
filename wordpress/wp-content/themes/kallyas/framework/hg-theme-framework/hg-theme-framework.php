<?php

class ZnHgTFw_ThemeFramework{

	/**
	 * Holds the theme configuration
	 * @var array
	 */
	public static $instance = null;
	private $registeredComponent = array();

	/**
	 * Holds the current Theme Version
	 * @var string
	 */
	private $_version;

	/**
	 * Holds the current Theme Name
	 * @var string
	 */
	private $_theme_name;

	/**
	 * Holds the current Framework path
	 * @var string
	 */
	private $_fwPath;


	/**
	 * Holds the current Theme path
	 * @var string
	 */
	private $_themePath;

	/**
	 * Holds the current Theme URI
	 * @var string
	 */
	private $_themeUri;


	/**
	 * Holds the current Framework URL
	 * @var string
	 */
	private $_fwUrl;

	/**
	 * Holds the Theme options id
	 * @see get_option()
	 * @var string
	 */
	private $theme_db_id;

	/**
	 * Holds internal theme id
	 * @var string
	 */
	private $theme_id;

	/**
	 * Holds the theme server URL
	 * The URL is usually used as an API
	 * @var string
	 */
	private $server_url;

	/**
	 * Holds the theme logo url
	 * The logo is usually used in admin pages
	 * @var string
	 */
	private $themeLogoUrl = false;

	public static function getInstance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	function __construct(){

		// Set FW vars
		$this->initVars();
		// Load theme config
		$this->initFwConfig();
		// Register all FW components
		$this->_registerComponents();

		// Load all helper functions
		$this->initHelpers();

		// Main class init
		add_action( 'init', array( $this, 'initFw' ), 1 );

	}

	/**
	 *	Load theme config
	 */
	function initFwConfig(){
		// Get the theme config
		$config = apply_filters('znhgtfw_config', array());

		// Setup vars
		$keys = array_keys( get_object_vars( $this ) );
		foreach ( $keys as $key ) {
			if ( isset( $config[ $key ] ) ) {
				$this->$key = $config[ $key ];
			}
		}
	}

	/**
	 * Main Framework init
	 * @see WordPress init action
	 * @return void
	 */
	public function initFw() {

		// Load mega menu component
		$this->_loadComponent( 'mega-menu' );
		$this->_loadComponent( 'scripts-manager' );

		if( is_admin() ){
			// Load admin stuff
			// Load theme Installer
			$this->_loadComponent( 'installer' );
			// TODO: REORGANIZE THE ADMIN CLASS
			$this->_loadComponent( 'admin' );
			// Load Updater class
			$this->_loadComponent( 'updater' );
		}
	}


	/**
	 * Checks if the Hogash framework is installeds
	 * @return boolean [description]
	 */
	function isFwInstalled(){
		return class_exists( 'ZnHg_Framework' );
	}


	/**
	 * Will load all helper functions
	 * @return void
	 */
	function initHelpers(){

		// Load integrations
		require ( $this->getFwPath( 'inc/integrations/znhgfw_integration.php' ) );

		// Backend functions
		require ( $this->getFwPath( 'inc/helpers/theme_ajax.php' ) );
		// Backend functions
		require ( $this->getFwPath( 'inc/helpers/functions-helper.php' ) );
		// Backend functions
		require ( $this->getFwPath( 'inc/helpers/functions-backend.php' ) );
		// Image resize helper functions
		require ( $this->getFwPath( 'inc/helpers/functions-image-helpers.php' ) );

		if( ! $this->isRequest('admin') || $this->isRequest('ajax') ){
			require ( $this->getFwPath( 'inc/helpers/functions-frontend.php' ) );
		}
	}


	/**
	 * Sets framework/theme vars
	 * @return void
	 */
	function initVars(){
		// Get active theme version even if it is a child theme
		$active_theme = wp_get_theme();
		$this->_version = $active_theme->parent() ? $active_theme->parent()->get('Version') : $active_theme->get('Version');
		$this->_theme_name = $active_theme->parent() ? $active_theme->parent()->get('Name') : $active_theme->get('Name');

		// FW PATHS
		$theme_base = get_template_directory();
		$this->childthemePath = get_stylesheet_directory();

		// Set the path to the theme's directory
		//#! For when ABSPATH is "/"
		$this->_themePath = str_replace( '//', '/', $theme_base );

		// FW URLS
		$this->_themeUri = esc_url( get_template_directory_uri() );
		$this->childthemeUri = esc_url( get_stylesheet_directory_uri() );

		// FW PATHS
		$this->_fwPath = wp_normalize_path( dirname( __FILE__ ) );
		$fw_basename = str_replace( wp_normalize_path( $this->_themePath ), '', $this->_fwPath );
		$this->_fwUrl = $this->_themeUri . $fw_basename;
	}


	/**
	 * What type of request is this?
	 * @var string $type ajax, frontend or admin
	 * @return bool
	 */
	public function isRequest( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ! is_admin();
		}

		return false;
	}



	public function getVersion() {
		return $this->_version;
	}

	public function getThemeName() {
		return $this->_theme_name;
	}

	public function getFwPath( $path = '' ) {
		return trailingslashit( $this->_fwPath ) . $path;
	}

	public function getFwUrl( $path = '' ) {
		return trailingslashit( $this->_fwUrl ) . $path;
	}

	public function getLogoUrl(){
		return $this->themeLogoUrl;
	}

	/**
	 * Returns the path to the current master theme
	 * @param  string $path the path that will be added to the theme path
	 * @return string The requested path based on current master theme path
	 */
	public function getThemePath( $path = '' ) {
		return trailingslashit( $this->_themePath ) . $path;
	}


	/**
	 * Returns the url to the current master theme
	 * @param  string $path the url that will be added to the theme path
	 * @return string The requested url based on current master theme url
	 */
	public function getThemeUrl( $path = '' ) {
		return trailingslashit( $this->_themeUri ) . $path;
	}


	public function getThemeDbId(){
		return $this->theme_db_id;
	}

	public function getThemeId(){
		return $this->theme_id;
	}

	public function getThemeServerUrl(){
		return $this->server_url;
	}

	/**
	 * Will register all components by name
	 */
	private function _registerComponents() {
		$this->registerComponent( 'mega-menu', $this->getFwPath( 'inc/mega-menu/class-mega-menu.php' ) );
		$this->registerComponent( 'admin', $this->getFwPath( 'inc/admin/class-zn-admin.php' ) );
		$this->registerComponent( 'installer', $this->getFwPath( 'inc/installer/class-theme-install.php' ) );
		$this->registerComponent( 'updater', $this->getFwPath( 'inc/updater/class-theme-updater.php' ) );
		$this->registerComponent( 'scripts-manager', $this->getFwPath( 'inc/scripts-manager/class-scripts-manager.php' ) );
	}

	public function registerComponent( $componentName, $path ) {
		$this->registeredComponent[ $componentName ] = $path;
	}

	private function _loadComponent( $component_name ) {
		$this->components[ $component_name ] = require_once( $this->registeredComponent[ $component_name ] );
	}

	public function getComponent( $component_name ) {
		if ( empty( $this->components[ $component_name ] ) ) {
			$this->_loadComponent( $component_name );
		}
		return $this->components[ $component_name ];
	}

}

function ZNHGTFW(){
	return ZnHgTFw_ThemeFramework::getInstance();
}

ZNHGTFW();
