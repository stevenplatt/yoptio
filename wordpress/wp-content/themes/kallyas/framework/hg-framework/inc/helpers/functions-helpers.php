<?php

/*
*	Sanitize theme options
*	Will convert the string to a database sage option string
*/
function zn_fix_insecure_content($url){
	return preg_replace('#^https?://#', '//', $url);
}

function zn_uid( $prepend = 'eluid', $length = 8 ){
	return $prepend . substr(str_shuffle(MD5(microtime())), 0, $length);
}

function zn_create_folder( &$folder, $addindex = true ) {
	if( is_dir( $folder ) && $addindex == false)
		return true;

	$created = wp_mkdir_p( trailingslashit( $folder ) );
	// SET PERMISSIONS
	@chmod( $folder, 0777 );

	if($addindex == false) return $created;

	// ADD AN INDEX.PHP FILE
	$index_file = trailingslashit( $folder ) . 'index.php';
	if ( file_exists( $index_file ) )
		return $created;

	$handle = @fopen( $index_file, 'w' );
	if ($handle)
	{
		fwrite( $handle, "<?php\r\necho 'Directory browsing is not allowed!';\r\n?>" );
		fclose( $handle );
	}

	return $created;
}

function zn_delete_folder( $path ) {
	//echo $path;
	//check if folder exists
	if( is_dir( $path) )
	{

		$it = new RecursiveDirectoryIterator($path);
		$files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

		foreach($files as $file) {
			if ($file->getFilename() === '.' || $file->getFilename() === '..')
			{
				continue;
			}

			if ( $file->isDir() ){
				rmdir($file->getRealPath());
			}
			else {
				unlink($file->getRealPath());
			}
		}

		rmdir($path);
	}
}

function find_file( $folder , $extension ) {
	$files = scandir( $folder );

	foreach($files as $file)
	{
		if(strpos(strtolower($file), $extension )  !== false && $file[0] != '.')
		{
			return $file;
		}
	}

	return false;
}


/**
 * Function to return type of target for links
 */
if ( !function_exists( 'zn_get_target' ) )
{

	function zn_get_target( $target = '_self' )
	{

		$link_target = '';

		if ( $target == '_blank' || $target == '_self' )
		{
			$link_target = 'target="' . $target . '"';
		}

		return apply_filters('zn_default_link_target_html', $link_target, $target);
	}
}


/**
 * Display a list of link targets
 */
if ( !function_exists( 'zn_get_link_targets' ) )
{
	function zn_get_link_targets( $exclude = array() )
	{

		$targets = apply_filters('zn_default_link_target_type', array(
			'_self' => __( "Same window", 'zn_framework' ),
			'_blank' => __( "New window", 'zn_framework' ),
		) );

		if ( !empty( $exclude ) )
		{
			foreach ( $exclude as $v )
			{
				if ( array_key_exists( $v, $targets ) )
				{
					unset( $targets[ $v ] );
				}
			}
		}

		return $targets;
	}
}

/*--------------------------------------------------------------------------------------------------
	zn_extract_link - This function will return the option
	@accepts : An link option
	@returns : array containing a link start and link end HTML
--------------------------------------------------------------------------------------------------*/
function zn_extract_link( $link_array , $class = false , $attributes = false, $def_start = '', $def_end = '', $def_url = false ){

	if($def_url && empty($link_array['url'])){
		$link_array['url'] = trim($def_url);
	}

	if ( !is_array( $link_array ) || empty( $link_array['url'] ) ) {
		$link['start'] = $def_start ? $def_start : '';
		$link['end'] = $def_end ? $def_end : '';
	}
	else{

		$title 	= ! empty( $link_array['title'] ) ? 'title="'.$link_array['title'].'"' : '';
		$target = ! empty( $link_array['target'] ) ? zn_get_target( esc_attr( $link_array['target'] ) ) : '';
		$link 	= array( 'start' => '<a href="'.esc_url( $link_array['url'] ).'" '.$attributes.' class="'.$class.'" '.$title.' '.$target.' '.zn_schema_markup('url').'>' , 'end' => '</a>' );
	}

	return $link;

}

/*--------------------------------------------------------------------------------------------------
	zn_extract_link_title - This function will return the title string from link array
	@accepts : An link option
	@returns : string
--------------------------------------------------------------------------------------------------*/
function zn_extract_link_title( $link_array, $esc = false ){

	return is_array( $link_array ) && !empty( $link_array['title'] ) ? ( $esc ? esc_attr( $link_array['title'] ) : $link_array['title'] )  : '';

}

/*--------------------------------------------------------------------------------------------------
	Minimifyes CSS code
--------------------------------------------------------------------------------------------------*/
function zn_minimify( $css_code ){

	// Minimiy CSS
	$css_code = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css_code); // Remove comments
	$css_code = str_replace(': ', ':', $css_code); // Remove space after colons
	$css_code = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css_code); // Remove whitespace

	return $css_code;
}


/*--------------------------------------------------------------------------------------------------
	Preety print
--------------------------------------------------------------------------------------------------*/
function print_z($string, $hidden = false) {
	echo '<pre '. ( $hidden ? 'style="display:none"':'' ) .'>';
		print_r($string);
	echo '</pre>';
}