<?php if(! defined('THEME_BASE')) { exit('Invalid Request');}

add_filter( 'zn_theme_update_scripts', 'zn_kallyas_updater_scripts' );
add_filter( 'zn_theme_normal_update_scripts', 'zn_kallyas_normal_updates_scripts' );

/**
 *	Updates that requires DB updates ( Normally it should only be the V3 to V4 update )
 */
function zn_kallyas_updater_scripts(){
	$updates = array(
			'4.0.0' => array(
				'file' => THEME_BASE .'/template_helpers/update/scripts/kallyas-update-4.0.0.php',
				'function' => 'zn_cnv_perform_updatev4'
			)
		);

	return $updates;
}

function zn_kallyas_normal_updates_scripts(){
	$updates = array(
		'4.0.5' => array(
			'function' => 'zn_update_405'
		),
		'4.0.9' => array(
			'function' => 'zn_update_409'
		),
		'4.0.12' => array(
			'function' => 'zn_update_4012'
		),
		'4.1.5' => array(
			'function' => 'zn_update_415'
		),
		'4.3.1' => array(
			'function' => 'zn_update_431'
		),
		'4.12.0' => array(
			'function' => 'zn_update_4_12_0'
		),
	);

	return $updates;
}



/*
 *	4.0.5 Update
 */
function zn_update_405(){

	$uploads = wp_upload_dir();
	$file_path = trailingslashit( $uploads['basedir'] ) . 'zn_custom_css.css';
	// Change the custom css saving from file to DB
	if ( file_exists( $file_path ) ){
		$saved_css = file_get_contents( $file_path );
		if( ! empty( $saved_css ) ){
			update_option( 'zn_'.ZNHGTFW()->getThemeId().'_custom_css', $saved_css, false );
		}
		@unlink( $file_path );
	}
}

/*
 * 4.0.9 update
 */
function zn_update_409(){

	$config = array(
		'tf_username' => zget_option( 'zn_theme_username', 'advanced_options', false, null ),
		'tf_api' => zget_option( 'zn_theme_api', 'advanced_options', false, null ),
	);

	update_option( 'kallyas_update_config', $config );
}

/*
 * 4.0.12 update
 */
function zn_update_4012(){
	// Remove the favicon option and set it as site_icon
	$favicon 	= zget_option( 'custom_favicon', 'general_options' );
	$site_icon 	= get_option( 'site_icon' );
	if( ! empty( $favicon ) && empty( $site_icon ) ){
		$favicon_image_id = ZngetAttachmentIdFromUrl( $favicon );
		update_option( 'site_icon', $favicon_image_id );
	}
}

/*
 * 4.1.5 update
 * "Fixes" the hide footer option ( see #1396 )
 */
function zn_update_415(){
	// Check if we need to change something
	$show_footer = zget_option( 'footer_show', 'general_options', false, 'yes' );
	$config = zn_get_pb_template_config( 'footer' );
	if( $show_footer == 'no' && $config['template'] !== 'no_template' && $config['location'] === 'replace' ){
		$all_options = zget_option( '', '', true );
		$all_options['general_options']['footer_show'] = 'yes';
		update_option( 'zn_kallyas_optionsv4', $all_options );
	}
}

function zn_update_431(){
	$permalinks = get_option( 'zn_permalinks' );
	$new_permalinks = array();

	// Convert old permalinks values
	if( is_array( $permalinks ) ){
		// Portfolio item
		if( ! empty( $permalinks['port_item'] ) ){
			$new_permalinks['portfolio'] = $permalinks['port_item'];
		}

		// Portfolio category
		if( ! empty( $permalinks['port_tax'] ) ){
			$new_permalinks['project_category'] = $permalinks['port_tax'];
		}

		// Documentation item
		if( ! empty( $permalinks['doc_item'] ) ){
			$new_permalinks['documentation'] = $permalinks['doc_item'];
		}

		// Documentation category
		if( ! empty( $permalinks['doc_tax'] ) ){
			$new_permalinks['documentation_category'] = $permalinks['doc_tax'];
		}

		update_option( 'zn_permalinks', $new_permalinks );

	}
}

/*
 * Update to v4.12
 */
function zn_update_4_12_0()
{
	if( ! class_exists( 'ZN_HogashDashboard' ) )
	{
		require_once( ZNHGTFW()->getFwPath('inc/api/ZN_HogashDashboard.php') );
	}

	//#! Migrate options
	//#! @since v4.11.2
	if( ZN_HogashDashboard::isGoDaddy() )
	{
		//#! Temporary disable db cache so we can retrieve the transient
		wp_using_ext_object_cache( false );

		$prefix = ZN_HogashDashboard::getOptionsPrefix();
		$optInfoName = $prefix . 'migrate_options_dash';

		if( false === get_site_option( $optInfoName ) )
		{
			//#! Rename all options/transients
			$oldValue = get_site_transient( ZN_HogashDashboard::THEME_CHECK_TRANSIENT );
			if( false !== $oldValue ){
				set_site_transient( $prefix.ZN_HogashDashboard::THEME_CHECK_TRANSIENT, $oldValue, DAY_IN_SECONDS );
				delete_site_transient( ZN_HogashDashboard::THEME_CHECK_TRANSIENT );
			}
			$oldValue = get_site_transient( ZN_HogashDashboard::THEME_DEMOS_TRANSIENT );
			if( false !== $oldValue ){
				set_site_transient( $prefix.ZN_HogashDashboard::THEME_DEMOS_TRANSIENT, $oldValue, DAY_IN_SECONDS );
				delete_site_transient( ZN_HogashDashboard::THEME_DEMOS_TRANSIENT );
			}
			$oldValue = get_site_transient( ZN_HogashDashboard::THEME_PLUGINS_TRANSIENT );
			if( false !== $oldValue ){
				set_site_transient( $prefix.ZN_HogashDashboard::THEME_PLUGINS_TRANSIENT, $oldValue, DAY_IN_SECONDS );
				delete_site_transient( ZN_HogashDashboard::THEME_PLUGINS_TRANSIENT );
			}
			$oldValue = get_site_option( ZN_HogashDashboard::THEME_API_KEY_OPTION );
			if( false !== $oldValue ){
				update_site_option( $prefix.ZN_HogashDashboard::THEME_API_KEY_OPTION, $oldValue );
				delete_site_option( ZN_HogashDashboard::THEME_API_KEY_OPTION );
			}
			update_site_option($optInfoName, true);
		}
	}
}
