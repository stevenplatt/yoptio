<?php

class ZnHgFw_ScriptsManager{

	var $inline_js = array();
	var $inline_css = '';

	function __construct(){
		add_action( 'wp_footer', array( $this, 'output_inline_js' ), 25 );
		add_action( 'wp_head', array( $this, 'output_inline_css' ), 25 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_dynamic_style' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}


	/**
	 * Frontend: Load theme's dynamic CSS
	 * @hooked to wp_enqueue_scripts.
	 */
	function enqueue_dynamic_style() {
		// Generated css file - The options needs to be saved in order to generate new file
		$uploads = wp_upload_dir();
		// Get save date microtime so that the cache gets invalidated
		$saved_date = get_option( 'znhgfw_dynamic_css_time', ZNHGFW()->getVersion() );

		$dynamic_css_file_url = trailingslashit( $uploads[ 'baseurl' ] ) . 'zn_dynamic.css';
		$dynamic_css_file_url = zn_fix_insecure_content( $dynamic_css_file_url );
		wp_enqueue_style( 'th-theme-options-styles', $dynamic_css_file_url, array(), $saved_date );

	}


	function enqueue_scripts(){
		// Register Isotope script
		// It can be used by themes or plugins
		wp_register_script( 'isotope', ZNHGFW()->getFwUrl('assets/dist/js/jquery.isotope.min.js'), 'jquery', '', true );
	}


	/**
	 * Generates the dynamic css
	 * @return void
	 */
	function generateDynamicCss(){
		$uploads = wp_upload_dir();
		$zn_uploads_dir = trailingslashit( $uploads['basedir'] );

		$css = apply_filters('zn_dynamic_css', '');
		$css = zn_minimify( $css );

		/** Write to zn_dynamic.css file **/
		file_put_contents( $zn_uploads_dir . 'zn_dynamic.css', $css );

	}

	/**
	 * @param string $code The code that you want to add to inline js
	 * @param bool|false $echo should we echo or return the code ?
	 */
	public function add_inline_js( $code, $echo = false ) {

		if ( $echo ) {

			$code = $code[ key( $code ) ];

			echo '<!-- Generated inline javascript -->';
			echo '<script type="text/javascript">';
				echo '(function($){';
					echo $code;
				echo '})(jQuery);';
			echo '</script>';

			return;
		}

		$this->inline_js[ key( $code ) ] = "\n" . $code[ key( $code ) ] . "\n";
	}


	/**
	 * @param string $code
	 * @param bool|false $echo
	 */
	public function add_inline_css( $code, $echo = false ) {

		if ( $echo ) {

			echo '<!-- Generated inline styles -->';
			echo '<style type="text/css">';
				echo $code;
			echo '</style>';

			return;
		}

		$this->inline_css .= $code;

	}

	/**
	 * Output the inline js
	 */
	public function output_inline_js() {

		if ( ! empty( $this->inline_js ) && is_array( $this->inline_js ) ) {

			echo '<!-- Zn Framework inline JavaScript-->';
			echo '<script type="text/javascript">';
				echo 'jQuery(document).ready(function($) {';
				foreach ( $this->inline_js as $key => $code ) {
					echo $code;
				}
				echo '});';
			echo '</script>';

		}
	}

	/**
	 * Output the inline css
	 */
	public function output_inline_css() {
		if ( $this->inline_css ) {
			echo '<!-- Generated inline styles -->';
			echo "<style type='text/css' id='zn-inline-styles'>";
				echo $this->inline_css;
			echo '</style>';
		}
	}
}

return new ZnHgFw_ScriptsManager();
