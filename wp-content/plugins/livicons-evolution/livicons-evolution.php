<?php
/*
Plugin Name: LivIcons Evolution
Plugin URI: https://livicons.com
Description: WordPress plugin with the next modern generation of animated SVG (vector) icons with individual animation for each one.
Version: 2.4.375
Author: DeeThemes
Author URI: http://codecanyon.net/user/DeeThemes
Copyright © 2016 | DeeThemes | https://livicons.com | http://codecanyon.net/user/DeeThemes
*/


//prefixes used for the plugin: livicons_evolution_, LIVICONS_EVOLUTION_

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

//plugin's constants
define( 'LIVICONS_EVOLUTION_VERSION', '2.4.375' );
define( 'LIVICONS_EVOLUTION_SLUG', 'livicons-evolution' );
define( 'LIVICONS_EVOLUTION_FILE', __FILE__ );
define( 'LIVICONS_EVOLUTION_PATH', plugin_dir_path(__FILE__) );
define( 'LIVICONS_EVOLUTION_URL', plugin_dir_url( __FILE__ ) );
define( 'LIVICONS_EVOLUTION_PLUGIN_SLUG', plugin_basename( __FILE__ ) );
define( 'LIVICONS_EVOLUTION_UPDATE_URL', 'https://livicons.com/update' );

//require minimum version of WordPress (4.3)
function livicons_evolution_check_wordpress_version() {
	global $wp_version;
	$plugin_data = get_plugin_data( LIVICONS_EVOLUTION_FILE, false );
	if ( version_compare( $wp_version, '4.3', '<' ) ) {
		if( is_plugin_active( LIVICONS_EVOLUTION_PLUGIN_SLUG ) ) {
			deactivate_plugins( LIVICONS_EVOLUTION_PLUGIN_SLUG );
			wp_die( "<b>" . $plugin_data['Name'] . " Plugin</b> requires WordPress 4.3 or higher, and has been deactivated! Please upgrade WordPress and try again.<br /><br />Back to <a href='" . admin_url() . "'>WordPress Dashboard</a>." );
		};
	};
}
add_action( 'admin_init', 'livicons_evolution_check_wordpress_version' );

//factory defaults
require_once( LIVICONS_EVOLUTION_PATH . 'includes/livicons-evolution-defaults.php' );

//full icons list
require_once( LIVICONS_EVOLUTION_PATH . 'includes/livicons-evolution-icons-list.php' );

//validation and sanitization
require_once( LIVICONS_EVOLUTION_PATH . 'includes/livicons-evolution-validation.php' );

//styles and scripts
require_once( LIVICONS_EVOLUTION_PATH . 'includes/livicons-evolution-assets.php' );

//admin part of the plugin
require_once( LIVICONS_EVOLUTION_PATH . 'includes/livicons-evolution-admin.php' );

//dialog box for customizing
require_once( LIVICONS_EVOLUTION_PATH . 'includes/livicons-evolution-dialog.php' );

//shortcode
require_once( LIVICONS_EVOLUTION_PATH . 'includes/livicons-evolution-shortcode.php' );

//visual composer add-on
require_once( LIVICONS_EVOLUTION_PATH . 'includes/livicons-evolution-visual-composer.php' );

//automatic updates
require_once( LIVICONS_EVOLUTION_PATH . 'includes/livicons-evolution-update.php' );

//activation of the plugin
require_once( LIVICONS_EVOLUTION_PATH . 'includes/livicons-evolution-activation.php' );

?>