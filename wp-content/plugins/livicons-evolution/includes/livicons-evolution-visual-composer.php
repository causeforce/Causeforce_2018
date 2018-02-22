<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

function livicons_evolution_visual_composer() {
	$settings = array(
		'name' => 'LivIcon Evo',
		'description' => 'Animated Icons',
		'base' => 'livicon_evo_vc',
		'class' => '',
		'wrapper_class' => 'clearfix',
		'category' => 'Content',
		'icon' => LIVICONS_EVOLUTION_URL . 'assets/img/lievo-vc-icon.png',
		'params' => array(
			array(
				'type' => 'textarea_html',
				'holder' => 'div',
				'class' => '',
				'heading' => 'Click "Add LivIcon" button to choose an animated icon',
				'param_name' => 'content',
				'value' => 'replace_me',
				'description' => 'Please select "replace_me" text and hit "Add LivIcon" button.'
			)
		)
	);
	vc_map( $settings );
}
add_action('vc_before_init', 'livicons_evolution_visual_composer');
