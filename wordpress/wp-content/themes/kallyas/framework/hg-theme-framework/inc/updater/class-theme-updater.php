<?php if ( !defined( 'ABSPATH' ) )
{
	return;
}

/**
 * This class handles the theme update functionality
 */
class ZN_ThemeUpdater
{
	/**
	 * Initialize the class' functionality
	 */
	public static function init()
	{
		add_filter( "pre_set_site_transient_update_themes", array( get_class(), "checkForUpdates" ), 800 );
		/*
		 * This filter is triggered right before the actual theme update process and will update the theme's download url correctly
		 * @since 4.11.1
		 */
		add_filter( 'upgrader_package_options', array( get_class(), 'update_theme_download_url' ), 999 );
	}

	/**
	 * Retrieve and update the theme's download url
	 * @see init()
	 * @see add_filter( 'upgrader_package_options', array( get_class(), 'update_theme_download_url' ), 999 );
	 * @param array $options
	 * @return mixed
	 */
	public static function update_theme_download_url( $options = array() ) {
		if( ! is_admin() || ! self::__isConnected() )
		{
			return $options;
		}

		//#! Check $options['package'] for our custom URL
		$packageURL = (isset($options['package']) && !empty($options['package']) ? $options['package'] : null);

		if( empty($packageURL)){
			return $options;
		}

		//#! custom query vars: hogash_deferred_download, item_id
		if ( false !== strrpos( $packageURL, 'hogash_deferred_download' ) && false !== strrpos( $packageURL, 'item_id' ) ) {
			parse_str( parse_url( $packageURL, PHP_URL_QUERY ), $vars );
			if ( $vars['item_id'] ) {
				$url = ZN_HogashDashboard::getThemeDownloadUrl();
				if( !empty($url)) {
					$options[ 'package' ] = $url;
				}
			}
		}
		return $options;
	}

	/**
	 * Check to see if there is an update available for the theme and inject this info in WordPress' updates list
	 * @param array $updatesAvailable
	 * @return mixed
	 */
	public static function checkForUpdates( $updatesAvailable )
	{
		if ( ! is_admin() || ! self::__isConnected() )
		{
			return $updatesAvailable;
		}

		//#! Get the theme info from Dashboard
		$dashThemeInfo = ZN_HogashDashboard::getThemeInfo();

		if ( empty( $dashThemeInfo ) || !isset( $dashThemeInfo[ 'url' ] ) )
		{
			return $updatesAvailable;
		}
		if( !isset( $dashThemeInfo[ 'new_version' ] ) || empty($dashThemeInfo[ 'new_version' ])){
			return $updatesAvailable;
		}

		if( !isset( $dashThemeInfo[ 'package' ] ) ){
			return $updatesAvailable;
		}
		if( !isset( $dashThemeInfo[ 'theme_id' ] ) || empty($dashThemeInfo[ 'theme_id' ]) ){
			return $updatesAvailable;
		}
		//#! Check if the theme needs an update
		if ( !version_compare( ZNHGTFW()->getVersion(), $dashThemeInfo[ 'new_version' ], '<' ) )
		{
			return $updatesAvailable;
		}

		/*
		 * Add our custom query arg and provide a custom URL, not the actual download URL
		 * This URL will be updated with the correct theme download URL by the "upgrader_package_options" filter.
		 * This will prevent clients from reaching the requests limit set by the Envato API for this action.
		 * @see init()
		 * @since 4.11.2
		 */
		$dashThemeInfo['package'] = add_query_arg( array(
			'hogash_deferred_download' => true,
			'item_id' => $dashThemeInfo[ 'theme_id' ],
		), admin_url('admin.php?page=zn-about'));

		//#! Update and return the list
		$updatesAvailable->response[ ZNHGTFW()->getThemeName() ] = $dashThemeInfo;
		return $updatesAvailable;
	}

	private static function __isConnected() {
		$apiKey = ZN_HogashDashboard::getApiKey();
		if ( empty( $apiKey ) || !ZN_HogashDashboard::isConnected() )
		{
			return false;
		}
		return true;
	}
}

return ZN_ThemeUpdater::init();
