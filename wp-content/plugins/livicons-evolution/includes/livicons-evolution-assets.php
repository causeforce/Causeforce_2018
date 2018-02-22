<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

//registering styles and scripts
function livicons_evolution_register_main_assets() {
	wp_register_style(
		'livicons_evolution_styles',
		LIVICONS_EVOLUTION_URL . 'assets/css/LivIconsEvo.WP.css',
		false,
		LIVICONS_EVOLUTION_VERSION
	);
	wp_register_script(
		'livicons_evolution_tools',
		LIVICONS_EVOLUTION_URL . 'assets/js/LivIconsEvo.WP.tools.js',
		false,
		LIVICONS_EVOLUTION_VERSION,
		true
	);
	wp_register_script(
		'livicons_evolution_defaults',
		LIVICONS_EVOLUTION_URL . 'assets/js/LivIconsEvo.WP.defaults.js',
		array( 'jquery' ),
		get_option( 'livicons_evolution_dynamic_string', '' ),
		true
	);
	wp_register_script(
		'livicons_evolution_core',
		LIVICONS_EVOLUTION_URL . 'assets/js/LivIconsEvo.WP.min.js',
		array( 'jquery', 'livicons_evolution_tools', 'livicons_evolution_defaults' ),
		LIVICONS_EVOLUTION_VERSION,
		true
	);
}
add_action( 'init', 'livicons_evolution_register_main_assets' );

//enqueue styles and scripts for site's dashboard, front-end and check for shortcode is allowed 
function livicons_evolution_general_assets() {
	//Allow or not LivIcons Evo in widgets, comments and excerpts
	$options = get_option('livicons_evolution_general_options');
	if( isset( $options['in_widgets'] ) && $options['in_widgets'] === 'true' ) {
	add_filter( 'widget_text', 'do_shortcode' ); 
	};
	if( isset( $options['in_comments'] ) && $options['in_comments'] === 'true' ) {	
	add_filter( 'comment_text', 'do_shortcode' ); 
	};
	if( isset( $options['in_excerpts'] ) && $options['in_excerpts'] === 'true' ) {
	add_filter( 'the_excerpt', 'do_shortcode' ); 
	};

	global $pagenow;

	$include_on_pages = array( 'index.php', 'post.php', 'edit.php', 'post-new.php', 'edit-tags.php');
	
	if ( in_array( $pagenow, $include_on_pages ) ) {
		$uploads_dir_params = wp_upload_dir();
		$uploads_dir = $uploads_dir_params['basedir'] . '/' . LIVICONS_EVOLUTION_SLUG;
		$uploads_url = $uploads_dir_params['baseurl'] . '/' . LIVICONS_EVOLUTION_SLUG;
		$custom_css_file = $uploads_dir . '/LivIconsEvo.custom.css';
		$custom_js_file = $uploads_dir . '/LivIconsEvo.custom.js';

		wp_enqueue_style( 'livicons_evolution_styles' );
		if ( is_admin() ) {
			wp_enqueue_style(
				'livicons_evolution_dialog_css',
				LIVICONS_EVOLUTION_URL . 'assets/css/LivIconsEvo.dialog.css',
				array( 'livicons_evolution_styles' ),
				LIVICONS_EVOLUTION_VERSION
			);
		};
		if ( is_readable( $custom_css_file ) && filesize( $custom_css_file ) > 0 ) {
			wp_enqueue_style(
				'livicons_evolution_custom_css',
				$uploads_url . '/LivIconsEvo.custom.css',
				array( 'livicons_evolution_styles' ),
				get_option( 'livicons_evolution_dynamic_string', '' )
			);
		};

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'livicons_evolution_tools' );
		wp_enqueue_script( 'livicons_evolution_defaults' );
		wp_enqueue_script( 'livicons_evolution_core' );
		if ( is_admin() ) {
			wp_enqueue_script(
				'livicons_evolution_dialog_js',
				LIVICONS_EVOLUTION_URL . 'assets/js/LivIconsEvo.dialog.js',
				array( 'livicons_evolution_core' ),
				LIVICONS_EVOLUTION_VERSION,
				true
			);
		};
		if ( is_readable( $custom_js_file ) && filesize( $custom_js_file ) > 0 ) {
			wp_enqueue_script(
				'livicons_evolution_custom_js',
				$uploads_url . '/LivIconsEvo.custom.js',
				array( 'livicons_evolution_core' ),
				get_option( 'livicons_evolution_dynamic_string', '' ),
				true
			);
		};
	};
}
add_action( 'init', 'livicons_evolution_general_assets' );
