<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

//Setting default options when plugin is activated and writing the result JS file
function livicons_evolution_plugin_activation() {
	//saving installed version number
	update_option( 'livicons_evolution_version', LIVICONS_EVOLUTION_VERSION );

	//getting factory defaults
	$defaults = livicons_evolution_factory_defaults();
	
	$saved_general = get_option( 'livicons_evolution_general_options' );
	if ( $saved_general === false ) $saved_general = array();
	//to prevent overwriting users values
	$result_general = array_merge( $defaults['general'], $saved_general );

	$saved_visual = get_option( 'livicons_evolution_visualization_options' );
	if ( $saved_visual === false ) $saved_visual = array();
	$result_visual = array_merge( $defaults['visual'], $saved_visual );

	$saved_anim = get_option( 'livicons_evolution_animation_options' );
	if ( $saved_anim === false ) $saved_anim = array();
	$result_anim = array_merge( $defaults['anim'], $saved_anim );

	$saved_activation = get_option( 'livicons_evolution_activation' );
	if ( $saved_activation === false ) $saved_activation = array();
	$result_activation = array_merge( $defaults['activation'], $saved_activation );
	
	//if restoring of factory defaults is checked
	if( ( $result_general['check_default_options_db'] === 'true' ) ) {
		delete_option( 'livicons_evolution_general_options' );
		delete_option( 'livicons_evolution_visualization_options' );
		delete_option( 'livicons_evolution_animation_options' );

		update_option( 'livicons_evolution_general_options', $defaults['general'] );
		update_option( 'livicons_evolution_visualization_options', $defaults['visual'] );
		update_option( 'livicons_evolution_animation_options', $defaults['anim'] );
		
		$tmp = array_merge( $defaults['general'], $defaults['visual'], $defaults['anim'] );
		livicons_evolution_save_result_file( $tmp );
	} else {
		update_option( 'livicons_evolution_general_options', $result_general );
		update_option( 'livicons_evolution_visualization_options', $result_visual );
		update_option( 'livicons_evolution_animation_options', $result_anim );
		update_option( 'livicons_evolution_activation', $result_activation );
		
		$tmp = array_merge( $result_general, $result_visual, $result_anim );
		livicons_evolution_save_result_file( $tmp );
	};
}
register_activation_hook( LIVICONS_EVOLUTION_FILE, 'livicons_evolution_plugin_activation' );

// Checks the version number and activate if necessary
function livicons_evolution_check_version() {
	if ( version_compare( LIVICONS_EVOLUTION_VERSION, get_option( 'livicons_evolution_version' ), '!=' ) ) {
		livicons_evolution_plugin_activation();
	};
}
add_action('plugins_loaded', 'livicons_evolution_check_version');