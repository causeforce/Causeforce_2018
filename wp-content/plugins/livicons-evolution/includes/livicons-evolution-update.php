<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

require_once(LIVICONS_EVOLUTION_PATH . '/includes/livicons-evolution-update-class.php');

function livicons_evolution_update() {
	$opt = get_option( 'livicons_evolution_activation', array( 'purchase_code'=>'', 'is_activated' => 'false' ) );
	new LiviconsEvolutionUpdate ( LIVICONS_EVOLUTION_VERSION, LIVICONS_EVOLUTION_UPDATE_URL, LIVICONS_EVOLUTION_PLUGIN_SLUG, $opt['purchase_code'], $opt['is_activated'] );
}
add_action('init', 'livicons_evolution_update');
