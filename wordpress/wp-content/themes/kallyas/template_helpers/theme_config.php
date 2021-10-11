<?php if(! defined('ABSPATH')){ return; }

$theme_config = array(
		'options_prefix' => 'zn_kallyas_optionsv4', // The DB options field name
		'theme_id' => 'kallyas', // The theme id that will be used for options field
		'name'           => 'Kallyas', // The theme name
		'server_url'	=> 'http://my.hogash.com/hg_api/',
		'supports'       => array(
			'pagebuilder'  	=> true,
			'megamenu'     	=> true,
			'iconmanager'  	=> true,
			'imageresizer' 	=> true,
			'shortcodes' 	=> false,
			'theme_updater'	=> array(
				'author' => 'Hogash',
			),
		),
	);
