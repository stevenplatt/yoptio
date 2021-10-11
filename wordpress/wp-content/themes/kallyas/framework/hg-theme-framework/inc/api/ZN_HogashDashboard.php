<?php if(! defined('ABSPATH')){ return; }

/**
 * Theme's service. Interacts with our demo server and retrieves the list of all available demos.
 * @requires valid user
 */
class ZN_HogashDashboard
{
	const DASH_ENDPOINT_URL = 'http://my.hogash.com/hg_api/';

	const THEME_CHECK_TRANSIENT = 'hg_dash_theme_check';

	const THEME_DEMOS_TRANSIENT = 'hg_dash_theme_demos';

	const THEME_PLUGINS_TRANSIENT = 'hg_dash_plugins';

	const THEME_API_KEY_OPTION = 'hg_dash_api_key';

	const NETWORK_MENU_SLUG = 'kdash_';

	/**
	 * Whether or not is connected
	 * @var null
	 */
	static $isConnected = null;

	/**
	 * Whether or not this is GoDaddy hosting
	 * @see isGoDaddy()
	 * @var bool
	 */
	private static $_isGoDaddy = null;

	/**
	 * Stores the theme ID
	 * @var string
	 */
	private static $_themeID = '';


	public static function init()
	{
		add_action( 'network_admin_menu', array( get_class(), 'createNetworkMenu' ) );
		add_action( 'dash_clear_cached_data', array( get_class(), 'clearCachedData' ), 0 );
		self::$_themeID = ZNHGTFW()->getThemeId();
	}

	//<editor-fold desc="::: ADMIN MENU">
	public static function createNetworkMenu()
	{
		add_menu_page(
			ZNHGTFW()->getThemeName() . __( ' Dashboard', 'zn_framework' ),
			ZNHGTFW()->getThemeName() . __( ' Dashboard', 'zn_framework' ),
			'create_sites',
			self::NETWORK_MENU_SLUG,
			array( get_class(),
			'render_network_page'
		));
	}

	public static function render_network_page()
	{
		wp_enqueue_style( 'zn_about_style', ZNHGTFW()->getFwUrl( '/inc/admin/assets/css/zn_about.css' ), array(), ZNHGTFW()->getVersion() );
		include( ZNHGTFW()->getFwPath( '/inc/admin/tmpl/network-page.php' ) );
	}
	//</editor-fold desc="::: ADMIN MENU">

	//<editor-fold desc="::: DASHBOARD INTEGRATION">
	/**
	 * Connect the theme with the Hogash Dashboard
	 * @param $apiKey
	 * @return array|mixed|null|object
	 */
	public static function connectTheme( $apiKey )
	{
		$response = self::request( array(
			'body' => array(
				'action' => 'register',
				'api_key' => $apiKey,
				'site_url' => esc_url( home_url( '/' ) )
			)
		) );

		if ( is_wp_error( $response ) )
		{
			return array( 'error' => $response->get_error_message() );
		}

		//#! Check response headers
		if ( is_array( $response ) && isset( $response[ 'response' ] ) && isset( $response[ 'response' ][ 'code' ] ) )
		{
			if ( !in_array( (int)$response[ 'response' ][ 'code' ], array( 200, 302, 304 ) ) )
			{
				return array( 'error' =>
					esc_html(
					  sprintf(
						  __( 'An error occurred while trying to contact %s. Please check with your hosting company and make sure they whitelist our domain. The response code was: %s', 'zn_framework' )
						  ,self::DASH_ENDPOINT_URL, $response[ 'response' ][ 'code' ] ) ) );
			}
		}

		if ( !isset( $response[ 'body' ] ) || empty( $response[ 'body' ] ) )
		{
			return array( 'error' => __( 'Invalid response retrieved from server', 'zn_framework' ) );
		}

		return json_decode( $response[ 'body' ], true );
	}

	/**
	 * Check to see whether or not this is one of the theme option pages where we need to check the connection to Dash
	 * @return bool
	 */
	public static function __canCheckConnection(){
		// ?page=kdash_ -> wpmu kallyas dashboard page
		// ?page=zn-about -> wpmu/single kallyas dashboard page

		if( is_admin()) {
			if ( isset( $_REQUEST[ 'page' ] ) ){
				$page = wp_strip_all_tags($_REQUEST[ 'page' ]);
				return in_array($page, array('kdash_', 'zn-about'));
			}
		}
		return false;
	}


	/**
	 * Check to see whether or not the theme is connected with the Hogash Dashboard
	 * @param bool|true $useCurrent Whether or not to use the saved value
	 * @return bool
	 */
	public static function isConnected( $useCurrent = true )
	{
		if( null !== self::$isConnected ){
			return self::$isConnected;
		}

		//#! Save requests to Dash. Since this is not one of the pages we're interested in,
		//#! return whatever info is in cache, it's irrelevant for this request anyway,
		//#! unless there are any *listeners* (classes) expecting a response
		if( ! self::__canCheckConnection() ){
			$info = self::getThemeCheckTransient();
			self::$isConnected = ( '1x' == $info );
			return self::$isConnected;
		}

		//#! If to use the cached info
		if ( $useCurrent )
		{
			$info = self::getThemeCheckTransient();
			if( ! empty($info) )
			{
				self::$isConnected = ( '1x' == $info );
				return self::$isConnected;
			}
		}

		$apiKey = self::getApiKey();
		if ( empty( $apiKey ) )
		{
			self::$isConnected = false;
			return false;
		}

		$response = self::request( array(
			'body' => array(
				'action' => 'theme_check',
				'api_key' => $apiKey,
				'site_url' => esc_url( home_url( '/' ) )
			)
		) );

		if ( is_wp_error( $response ) )
		{
			error_log(__METHOD__.'() Error: '.$response->get_error_message());
			return false;
		}

		//#! Check response headers
		if ( is_array( $response ) && isset( $response[ 'response' ] ) && isset( $response[ 'response' ][ 'code' ] ) )
		{
			if ( !in_array( (int)$response[ 'response' ][ 'code' ], array( 200, 302, 304 ) ) )
			{
				error_log(esc_html(
					sprintf(
						__( 'An error occurred while trying to contact %s. Please contact your hosting company support department and make sure they whitelist Hogash.com. The response code was: %s', 'zn_framework' )
						, ZNHGTFW()->getThemeServerUrl(), $response[ 'response' ][ 'code' ] ) ));
				return false;
			}
		}

		if ( !is_array( $response ) || !isset( $response[ 'body' ] ) || empty( $response[ 'body' ] ) )
		{
			error_log(__METHOD__.'() Error: $response[body] was either not found or empty');
			return false;
		}

		$data = json_decode( $response[ 'body' ], true );

		if ( !is_array( $data ) || !isset( $data[ 'success' ] ) || !isset( $data[ 'data' ] ) )
		{
			error_log(__METHOD__.'() Error: $data is not an array or $data[success] not found or $data[data] not found. $data = '.var_export($data,1));
			return false;
		}

		if ( $data[ 'success' ] && 1 == intval( $data[ 'data' ] ) )
		{
			self::$isConnected = true;
			self::setThemeCheckTransient('1x');
			return true;
		}

		return false;
	}
	//</editor-fold desc="::: DASHBOARD INTEGRATION">

	//<editor-fold desc="::: THEME DEMOS">
	/**
	 * Retrieve the list of all demos
	 * @return array
	 */
	public static function getAllDemos()
	{
		$apiKey = self::getApiKey();
		if ( empty( $apiKey ) )
		{
			return false;
		}

		if ( !self::isConnected() )
		{
			self::clearDemosList();
			self::clearPluginsList();
			return array( 'error' => __( 'You need to connect the theme with the Hogash Dashboard in order to be able to install any demo.', 'zn_framework' ) );
		}

		// Check transient
		$cache = self::getThemeDemosTransient();
		if ( !empty( $cache ) )
		{
			return $cache;
		}

		$response = self::request( array(
			'body' => array(
				'action' => 'list_demos',
				'api_key' => $apiKey,
				'site_url' => esc_url( home_url( '/' ) ),
				'theme' => self::$_themeID,
			)
		) );

		if ( is_wp_error( $response ) )
		{
			return array( 'error' => $response->get_error_message() );
		}
		if ( !is_array( $response ) || !isset( $response[ 'body' ] ) || empty( $response[ 'body' ] ) )
		{
			return array( 'error' => __( '[0001] Invalid response from server.', 'zn_framework' ) );
		}

		$data = json_decode( $response[ 'body' ], true );

		if ( !is_array( $data ) || !isset( $data[ 'success' ] ) || !isset( $data[ 'data' ] ) )
		{
			return array( 'error' => __( '[0002]  Invalid response from server.', 'zn_framework' ) );
		}

		if ( !$data[ 'success' ] )
		{
			return array( 'error' => __( '[0003]  Response from server: ' . $data[ 'data' ], 'zn_framework' ) );
		}

		if ( empty( $data[ 'data' ] ) )
		{
			return array( 'error' => __( 'No demos retrieved.', 'zn_framework' ) );
		}

		$result = $data[ 'data' ];

		self::setThemeDemosTransient($result);
		return $result;
	}
	public static function getDemo( $demoName = '', $savePath = '' )
	{
		if ( empty( $demoName ) || empty( $savePath ) )
		{
			return false;
		}

		$apiKey = self::getApiKey();
		if ( empty( $apiKey ) || !self::isConnected() )
		{
			return false;
		}

		$response = self::request( array(
			'body' => array(
				//@since Dash v2.0 - use the new endpoint method
				//@see #2056
				'action' => 'get_demo_url',
				'api_key' => $apiKey,
				'site_url' => esc_url( home_url( '/' ) ),
				'theme' => self::$_themeID,
				'demo' => $demoName,
				'api_version' => '2',
			)
		) );

		global $wp_filesystem;
		if ( is_array( $response ) && isset( $response[ 'body' ] ) )
		{
			$downloadURL = trim( $response[ 'body' ] );
			if( filter_var( $downloadURL, FILTER_VALIDATE_URL ) )
			{
				$response = wp_remote_get( $downloadURL, array( 'timeout' => 30 ) );

				if( is_array( $response ) && isset( $response['body'] ) ) {
					$content = $response['body'];
					if( ! empty( $content ) ) {
						$r = $wp_filesystem->put_contents( $savePath, $content );
						if ( $r ) {
							return $savePath;
						}
					}
				}
			}
			else {
				//#! Check for errors first
				if( false !== ($decoded = json_decode($response['body'], true))){
					if( isset($decoded['success']) && $decoded['success'] == false ){
						error_log('Server error: '.$decoded['data']);
						return false;
					}
				}
			}
			return false;
		}
		return false;
	}

	/**
	 * Retrieve the information about the theme from Dashboard
	 * @return bool|mixed
	 */
	public static function getThemeInfo()
	{
		/*
		 * #! When clicking the Force check in wp-admin/updates, this code is executed twice because WordPress has a registered
		 * hook too, and the first request is not triggering the "pre_set_site_transient_update_themes" filter, only the second
		 * request, so obviously, we'd want to ignore the first request.
		 */
		if( ! isset($GLOBALS['kallyas_check_ignore_first_load'])){
			$GLOBALS['kallyas_check_ignore_first_load'] = true;
			return false;
		}

		$apiKey = self::getApiKey();
		if ( empty( $apiKey ) || !self::isConnected( false ) )
		{
			return false;
		}

		$response = self::request( array(
			'body' => array(
				'action' => 'get_theme_info',
				'api_key' => $apiKey,
				'site_url' => esc_url( home_url( '/' ) ),
				'theme' => self::$_themeID,
				'api_version' => '2',
			)
		) );

		if ( is_array( $response ) && isset( $response[ 'body' ] ) )
		{
			$response = json_decode( $response[ 'body' ], true );
			if ( !is_array( $response ) || !isset( $response[ 'success' ] ) || !$response[ 'success' ] )
			{
				return false;
			}

			if ( !isset( $response[ 'data' ] ) || empty( $response[ 'data' ] ) )
			{
				return false;
			}

			return $response[ 'data' ];
		}
		return false;
	}

	/**
	 * Retrieve the download URL for the theme update archive
	 * @return bool
	 */
	public static function getThemeDownloadUrl() {
		$apiKey = self::getApiKey();
		if ( empty( $apiKey ) || !self::isConnected( false ) )
		{
			return false;
		}

		$response = self::request( array(
			'body' => array(
				'action' => 'get_theme_download_url',
				'api_key' => $apiKey,
				'site_url' => esc_url( home_url( '/' ) ),
				'theme' => self::$_themeID,
				'api_version' => '2',
			)
		) );

		if ( is_array( $response ) && isset( $response[ 'body' ] ) )
		{
			$response = json_decode( $response[ 'body' ], true );
			if ( !is_array( $response ) || !isset( $response[ 'success' ] ) || !$response[ 'success' ] )
			{
				return false;
			}

			if ( !isset( $response[ 'data' ] ) || empty( $response[ 'data' ] ) )
			{
				return false;
			}

			return $response[ 'data' ];
		}
		return false;

	}

	/**
	 * Retrieve the demo information from the transient
	 * @param string $demoName
	 * @return null|array
	 */
	public static function __get_demo_install_info( $demoName )
	{
		$transData = self::getThemeDemosTransient();
		if( empty($transData) ){
			return null;
		}
		foreach( $transData as $demo_name => $info )
		{
			if( strcasecmp( $demoName, $demo_name ) == 0 ){
				return $info;
			}
		}
		return;
	}
	//</editor-fold desc="::: THEME DEMOS">

	//<editor-fold desc="::: THEME PLUGINS">
	public static function getAllPlugins()
	{
		$apiKey = self::getApiKey();
		if ( empty( $apiKey ) || !self::isConnected() )
		{
			self::clearCachedData();
			return array( 'error' => __( 'You need to connect the theme with the Hogash Dashboard in order to be able to install any plugin.', 'zn_framework' ) );
		}

		// Check transient
		$cache = self::getThemePluginsTransient();
		if ( !empty( $cache ) )
		{
			return $cache;
		}

		$response = self::request( array(
			'body' => array(
				'action' => 'list_plugins',
				'api_key' => $apiKey,
				'site_url' => esc_url( home_url( '/' ) ),
				'theme' => self::$_themeID,
			)
		) );

		if ( is_wp_error( $response ) )
		{
			return array( 'error' => $response->get_error_message() );
		}
		if ( !is_array( $response ) || !isset( $response[ 'body' ] ) || empty( $response[ 'body' ] ) )
		{
			return array( 'error' => __( '[0001] Invalid response from server.', 'zn_framework' ) );
		}

		$data = json_decode( $response[ 'body' ], true );

		if ( !is_array( $data ) || !isset( $data[ 'success' ] ) || !isset( $data[ 'data' ] ) )
		{
			return array( 'error' => __( '[0002]  Invalid response from server.', 'zn_framework' ) );
		}

		if ( !$data[ 'success' ] )
		{
			return array( 'error' => __( '[0003]  Invalid response from server: ' . $data[ 'data' ], 'zn_framework' ) );
		}

		if ( empty( $data[ 'data' ] ) )
		{
			return array( 'error' => __( 'No plugins retrieved.', 'zn_framework' ) );
		}

		$result = $data[ 'data' ];

		self::setThemePluginsTransient($result);
		return $result;
	}

	public static function getPluginDownloadUrl( $slug = '', $source = '' )
	{
		$apiKey = self::getApiKey();
		if ( empty( $apiKey ) || !self::isConnected( false ) )
		{
			return array( 'error' => __( 'You need to connect the theme with the Hogash Dashboard in order to be able to install any plugin.', 'zn_framework' ) );
		}

		if ( empty( $slug ) )
		{
			return array( 'error' => __( 'You need to provide the plugin slug in order to be able to install or update it.', 'zn_framework' ) );
		}

		$response = self::request( array(
			'body' => array(
				'action' => 'get_plugin_download_url',
				'api_key' => $apiKey,
				'site_url' => esc_url( home_url( '/' ) ),
				'theme' => self::$_themeID,
				'slug' => esc_attr( $slug ),
				'source' => esc_attr( $source ),
			)
		) );

		if ( is_wp_error( $response ) )
		{
			return array( 'error' => $response->get_error_message() );
		}
		if ( !is_array( $response ) || !isset( $response[ 'body' ] ) || empty( $response[ 'body' ] ) )
		{
			return array( 'error' => __( '[0001] Invalid response from server.', 'zn_framework' ) );
		}

		$data = json_decode( $response[ 'body' ], true );

		if ( !is_array( $data ) || !isset( $data[ 'success' ] ) || !isset( $data[ 'data' ] ) )
		{
			return array( 'error' => __( '[0002]  Invalid response from server.', 'zn_framework' ) );
		}

		if ( !$data[ 'success' ] )
		{
			return array( 'error' => __( '[0003]  Invalid response from server: ' . $data[ 'data' ], 'zn_framework' ) );
		}

		if ( empty( $data[ 'data' ] ) )
		{
			return array( 'error' => __( 'No URL retrieved.', 'zn_framework' ) );
		}

		return $data[ 'data' ];
	}
	//</editor-fold desc="::: THEME PLUGINS">

	//<editor-fold desc="::: UTILITY METHODS">
	/**
	 * This function will search in various places for any of the default GoDaddy files, and if any is found then we assume this is a GoDaddy hosting
	 * @return bool
	 */
	public static function isGoDaddy()
	{
		if( ! is_null(self::$_isGoDaddy)){
			return self::$_isGoDaddy;
		}

		$root = trailingslashit(ABSPATH);
		$pluginsDir = (defined('WP_CONTENT_DIR') ? trailingslashit(WP_CONTENT_DIR).'mu-plugins/' : $root.'wp-content/mu-plugins/');
		if( is_file( $root.'gd-config.php' )){
			self::$_isGoDaddy = true;
			return true;
		}
		elseif( is_dir($pluginsDir.'gd-system-plugin') || is_file($pluginsDir.'gd-system-plugin.php') ){
			self::$_isGoDaddy = true;
			return true;
		}
		elseif( class_exists('\WPaaS\Plugin') ){
			self::$_isGoDaddy = true;
			return true;
		}
		return false;
	}

	public static function request( $args = array() )
	{
		$timeout = apply_filters( 'http_request_timeout', 30 );
		$timeout = ($timeout < 30) ? 30 : $timeout;
		$args = array_merge( array(
			'timeout' => $timeout,
			'redirection' => apply_filters( 'http_request_redirection_count', 10 ),
			'sslverify' => false,
		), $args );

		//@since Dash v2.0
		//@see #2060
		if( isset($args['body']) ){
			if(! isset($args['body']['version'])){
				$args['body']['version'] = ZNHGTFW()->getVersion();
			}
			//#! If not specifically set, use the default v1
			if(! isset($args['body']['api_version'])){
				$args['body']['api_version'] = '1';
			}
			//#! If not specifically set
			if(! isset($args['body']['theme'])){
				$args['body']['theme'] = self::$_themeID;
			}
		}
		return wp_remote_post( self::DASH_ENDPOINT_URL, $args );
	}

	/**
	 * Check if the server can communicate with our server ZNHGTFW()->getThemeServerUrl()
	 * @return bool
	 */
	public static function checkConnection()
	{
		$response = wp_remote_get( ZNHGTFW()->getThemeServerUrl() );
		$response_code = wp_remote_retrieve_response_code( $response );

		if ( !in_array( (int)$response_code, array( 200, 302, 304 ) ) )
		{
			set_transient( 'zn_server_connection_check', 'notok', 10 );
			return false;
		}
		set_transient( 'zn_server_connection_check', 'ok', YEAR_IN_SECONDS );
		return true;
	}

	/**
	 * Retrieve the saved API key
	 * @return string
	 */
	public static function getApiKey()
	{
		$apiKey = self::getThemeApiKeyOption();
		return ( empty( $apiKey ) ? '' : wp_strip_all_tags( $apiKey ) );

	}

	public static function updateApiKey( $apiKey = '' )
	{
		if ( empty( $apiKey ) )
		{
			return false;
		}
		return self::setThemeApiKeyOption($apiKey);
	}

	public static function isWPMU()
	{
		return ( function_exists( 'is_multisite' ) && is_multisite() );
	}

	/**
	 * Delete the cached list of demos
	 */
	public static function clearDemosList()
	{
		self::deleteThemeDemosTransient();
	}

	/**
	 * Delete the cached list of plugins
	 */
	public static function clearPluginsList()
	{
		self::deleteThemePluginsTransient();
	}

	public static function clearCachedData()
	{
		self::clearPluginsList();
		self::clearDemosList();
		self::deleteThemeCheckTransient();
		self::deleteThemeApiKeyOption();
	}

	public static function hasPlugins(){
		$data = self::getThemePluginsTransient();
		return (!empty($data));
	}
	public static function hasDemos(){
		$data = self::getThemeDemosTransient();
		return (!empty($data));
	}

	/**
	 * Utility method that child themes can use to directly register the theme on a MultiSite installation and when the Kallyas theme is not active on the main site
	 * @param string $apiKey The API Key to use for registration
	 * @since v4.9.1
	 */
	public static function directConnect( $apiKey )
	{
		if( ! function_exists('is_multisite') || ! is_multisite() ){
			error_log( __METHOD__.'() ERROR: This method can only be used on a MultiSite installation.' );
		}
		elseif( empty( $apiKey ) ) {
			error_log( __METHOD__.'() ERROR: Please provide an API Key.' );
		}
		elseif( ! self::isConnected() ) {
			if( self::isGoDaddy() ){
				wp_using_ext_object_cache( false );
			}
			$response = self::connectTheme( $apiKey );

			if( ! is_array($response) ) {
				error_log( __METHOD__.'() ERROR: An error occurred while contacting hogash.com. Please verify you can contact our server.' );
			}
			elseif( isset( $response[ 'error' ] ) ){
				error_log( __METHOD__.'() ERROR: '. var_export( $response[ 'error' ] ) );
			}
			elseif( isset( $response[ 'success' ] ) && $response[ 'success' ] ){
				self::updateApiKey( $apiKey );
				self::setThemeCheckTransient('1x');
				error_log( __METHOD__.'() SUCCESS: Thank you! Your theme is now connected with the Hogash Dashboard.' );
			}
			else {
				error_log( __METHOD__.'() ERROR: '.var_export( $response[ 'data' ], 1 ) );
			}
		}
	}

	//
	public static function getOptionsPrefix() {
		$prefix = self::$_themeID;
		if( empty($prefix) ) {
			$prefix = ZNHGTFW()->getThemeId();
		}
		return $prefix . '_';
	}

	public static function getThemeCheckTransient() {
		//#! Temporary disable db cache so we can retrieve the transient
		if( self::isGoDaddy() ){ wp_using_ext_object_cache( false ); }
		return get_site_transient( self::getOptionsPrefix() . self::THEME_CHECK_TRANSIENT );
	}
	public static function getThemeDemosTransient() {
		//#! Temporary disable db cache so we can retrieve the transient
		if( self::isGoDaddy() ){ wp_using_ext_object_cache( false ); }
		return get_site_transient( self::getOptionsPrefix() . self::THEME_DEMOS_TRANSIENT );
	}
	public static function getThemePluginsTransient() {
		//#! Temporary disable db cache so we can retrieve the transient
		if( self::isGoDaddy() ){ wp_using_ext_object_cache( false ); }
		return get_site_transient( self::getOptionsPrefix() . self::THEME_PLUGINS_TRANSIENT );
	}
	public static function getThemeApiKeyOption() {
		//#! Temporary disable db cache so we can retrieve the transient
		if( self::isGoDaddy() ){ wp_using_ext_object_cache( false ); }
		return get_site_option( self::getOptionsPrefix() . self::THEME_API_KEY_OPTION );
	}

	public static function setThemeCheckTransient( $value ) {
		//#! Temporary disable db cache so we can retrieve the transient
		if( self::isGoDaddy() ){ wp_using_ext_object_cache( false ); }
		return set_site_transient( self::getOptionsPrefix() . self::THEME_CHECK_TRANSIENT, $value, DAY_IN_SECONDS );
	}
	public static function setThemeDemosTransient( $value ) {
		//#! Temporary disable db cache so we can retrieve the transient
		if( self::isGoDaddy() ){ wp_using_ext_object_cache( false ); }
		return set_site_transient( self::getOptionsPrefix() . self::THEME_DEMOS_TRANSIENT, $value, DAY_IN_SECONDS );
	}
	public static function setThemePluginsTransient( $value ) {
		//#! Temporary disable db cache so we can retrieve the transient
		if( self::isGoDaddy() ){ wp_using_ext_object_cache( false ); }
		return set_site_transient( self::getOptionsPrefix() . self::THEME_PLUGINS_TRANSIENT, $value, DAY_IN_SECONDS );
	}
	public static function setThemeApiKeyOption( $value ) {
		//#! Temporary disable db cache so we can retrieve the transient
		if( self::isGoDaddy() ){ wp_using_ext_object_cache( false ); }
		return update_site_option( self::getOptionsPrefix() . self::THEME_API_KEY_OPTION, $value );
	}

	public static function deleteThemeCheckTransient() {
		//#! Temporary disable db cache so we can retrieve the transient
		if( self::isGoDaddy() ){ wp_using_ext_object_cache( false ); }
		return delete_site_transient( self::getOptionsPrefix() . self::THEME_CHECK_TRANSIENT );
	}
	public static function deleteThemeDemosTransient() {
		//#! Temporary disable db cache so we can retrieve the transient
		if( self::isGoDaddy() ){ wp_using_ext_object_cache( false ); }
		return delete_site_transient( self::getOptionsPrefix() . self::THEME_DEMOS_TRANSIENT );
	}
	public static function deleteThemePluginsTransient() {
		//#! Temporary disable db cache so we can retrieve the transient
		if( self::isGoDaddy() ){ wp_using_ext_object_cache( false ); }
		return delete_site_transient( self::getOptionsPrefix() . self::THEME_PLUGINS_TRANSIENT );
	}
	public static function deleteThemeApiKeyOption() {
		//#! Temporary disable db cache so we can retrieve the transient
		if( self::isGoDaddy() ){ wp_using_ext_object_cache( false ); }
		return delete_site_option( self::getOptionsPrefix() . self::THEME_API_KEY_OPTION );
	}
	//</editor-fold desc="::: UTILITY METHODS">
}

ZN_HogashDashboard::init();
