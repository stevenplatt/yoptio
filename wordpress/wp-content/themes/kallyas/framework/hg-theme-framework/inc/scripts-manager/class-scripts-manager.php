<?php

class ZnHgTFw_ScriptsManager{


	function __construct(){
		add_filter( 'zn_dynamic_css', array( $this, 'add_custom_css' ), 100 );
		add_action( 'wp', array( $this, 'zn_fw_custom_js' ) );

		add_filter( 'zn_options_to_save', array( $this, 'saveCustomCode' ) );



	}

	/**
	 * Adds custom css to dynamic css file
	 * @param string $css The current css code
	 */
	function add_custom_css( $css ){

		$saved_css = get_option( 'zn_'.ZNHGTFW()->getThemeId().'_custom_css', '' );
		$new_css = $css  . $saved_css;

		return $new_css;
	}


	/**
	 * Adds the user added javascript code
	 * @return void
	 */
	function zn_fw_custom_js(){

		$custom_js = get_option( 'zn_'.ZNHGTFW()->getThemeId().'_custom_js' );

		if( ! empty( $custom_js ) ){
			$custom_js = array( 'theme_custom_js' => $custom_js );
			ZNHGFW()->getComponent('scripts-manager')->add_inline_js( $custom_js );
		}

	}


	// Change the advanced tab to advanced_options. This is needed for the custom css save
	// TODO : Remove this and change the 'advanced_options' to 'advanced'
	function saveCustomCode( $saved_options ){

		// Save the Custom CSS in custom field
		if ( isset( $saved_options['advanced_options']['custom_css'] ) ) {

			$custom_css = $saved_options['advanced_options']['custom_css'];
			update_option( 'zn_'.ZNHGTFW()->getThemeId().'_custom_css', $custom_css, false );

			// Remove custom css from the main options field
			unset( $saved_options['advanced_options']['custom_css'] );
		}

		// Save custom JS in a custom field
		if ( isset( $saved_options['advanced_options']['custom_js'] ) ) {
			$custom_js = $saved_options['advanced_options']['custom_js'];
			update_option( 'zn_'.ZNHGTFW()->getThemeId().'_custom_js', $custom_js, false );

			// Remove custom js from the main options field
			unset( $saved_options['advanced_options']['custom_js'] );
		}

		return $saved_options;
	}

}

return new ZnHgTFw_ScriptsManager();
