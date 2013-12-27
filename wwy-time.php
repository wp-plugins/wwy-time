<?php
/*
Plugin Name: WWYTime - Timeline Map.
Plugin URI: http://wwytimetimeline.com
Description: WWYTime - is a plugin to support "Where were you" features
Version: 0.1
Author: Where were you, http://wwytimetimeline.com
Author URI: http://wwytimetimeline.com
*/

/*  Copyright 2014  wwytime - Where were YOU?  (email : info@wwytimetimeline.com)
   
*/
?><?php

// some definition we will use
define( 'WWY_PUGIN_NAME', 'WWYTime - Where were YOU?');
define( 'WWY_PUGIN_DIRECTORY', 'wwy-time');
define( 'WWY_CURRENT_VERSION', '0.1' );
define( 'WWY_CURRENT_BUILD', '3' );
// i18n plugin domain for language files
define( 'EMU2_I18N_DOMAIN', 'wwy-time' );


// load language files
function wwytime_set_lang_file() {
	# set the language file
	$currentLocale = get_locale();
	if(!empty($currentLocale)) {
		$moFile = dirname(__FILE__) . "/lang/" . $currentLocale . ".mo";
		if (@file_exists($moFile) && is_readable($moFile)) {
			load_textdomain(EMU2_I18N_DOMAIN, $moFile);
		}

	}
}
wwytime_set_lang_file();

// Require files within the plugin




//call register settings function


register_activation_hook(__FILE__, 'wwytime_activate');
register_deactivation_hook(__FILE__, 'wwytime_deactivate');
register_uninstall_hook(__FILE__, 'wwytime_uninstall');




//call register settings function
add_action( 'admin_init', 'wwytime_register_settings' );
function wwytime_register_settings() {
	//register settings
	register_setting( 'wwy-settings-group', 'wwytime_api' );		
}

register_activation_hook(__FILE__, 'wwytime_activate');
register_deactivation_hook(__FILE__, 'wwytime_deactivate');
register_uninstall_hook(__FILE__, 'wwytime_uninstall');

// activating the default values
function wwytime_activate() {
	add_option('wwytime_api', 2);
}

// deactivating
function wwytime_deactivate() {
	// needed for proper deletion of every option
	delete_option('wwytime_api');

}

// uninstalling
function wwytime_uninstall() {
	# delete all data stored
	delete_option('wwytime_api');		
}

// create custom plugin settings menu
add_action( 'admin_menu', 'wwytime_create_menu' );
function wwytime_create_menu() {

	// create new top-level menu
	add_menu_page( 
	__('WWYTime', EMU2_I18N_DOMAIN),
	__('WWYTime', EMU2_I18N_DOMAIN),
	0,
	WWY_PUGIN_DIRECTORY.'/admin_side/wwy_settings_page.php',
	'',
	plugins_url('icon.png', __FILE__));
	
	
	add_submenu_page( 
	WWY_PUGIN_DIRECTORY.'/admin_side/wwy_settings_page.php',
	__("Configuration", EMU2_I18N_DOMAIN),
	__("Configuration", EMU2_I18N_DOMAIN),
	0,
	WWY_PUGIN_DIRECTORY.'/admin_side/wwy_settings_page.php'
	);	
			
	
}

function wwytime_content() {
	echo "<div class='wwytime'></div>";
	echo "<iframe style='width:100%;height:600px;border:none;overflow:none;' scrolling='no' src='http://wwytimeline.com/wwy-published/?pub_id=" . get_option('wwytime_api') . "'></iframe>";
	echo "<style>.wwytime {
    background: none repeat scroll 0 0 #FFFFFF;
    height: 48px;
    margin-bottom: -48px;
    position: relative;   
    }</style>";
}
add_shortcode('wwytime','wwytime_content')
?>