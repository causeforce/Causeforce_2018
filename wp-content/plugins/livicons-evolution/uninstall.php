<?php 

if ( ! defined('ABSPATH') && ! defined('WP_UNINSTALL_PLUGIN') ) exit();

// delete plugin's options
delete_option( 'livicons_evolution_version' );
delete_option( 'livicons_evolution_general_options' );
delete_option( 'livicons_evolution_visualization_options' );
delete_option( 'livicons_evolution_animation_options' );
delete_option( 'livicons_evolution_activation' );
delete_option( 'livicons_evolution_dynamic_string' );

?>