<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

class LiviconsEvolutionUpdate
{
	/**
	 * The plugin current version
	 * @var string
	 */
	private $current_version;
	
	/**
	 * The plugin remote update path
	 * @var string
	 */
	private $update_url;
	
	/**
	 * Plugin Slug (plugin_directory/plugin_file.php)
	 * @var string
	 */
	private $plugin_slug;
	
	/**
	 * Plugin name (plugin_file)
	 * @var string
	 */
	private $slug;
	
	/**
	 * Purchase code
	 * @var string
	 */
	private $purchase_code;

	/**
	 * Is activated
	 * @var string
	 */
	private $is_activated;	
	
	/**
	 * Initialize a new instance of the WordPress Auto-Update class
	 * @param string $current_version
	 * @param string $update_url
	 * @param string $plugin_slug
	 */
	public function __construct( $current_version, $update_url, $plugin_slug, $purchase_code )
	{
		// Set the class public variables
		$this->current_version = $current_version;
		$this->update_url = $update_url;
		// Set the License
		$this->purchase_code = $purchase_code;
		// Set the Plugin Slug	
		$this->plugin_slug = $plugin_slug;
		list ($t1, $t2) = explode( '/', $plugin_slug );
		$this->slug = str_replace( '.php', '', $t2 );		
		// define the alternative API for updating checking
		add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'check_update' ), 11 );
		// define the alternative response for information checking
		add_filter( 'plugins_api', array( &$this, 'check_info' ), 10, 3 );
		// add message when is not activated
		add_action( 'in_plugin_update_message-' . $this->plugin_slug, array( &$this, 'show_message' ) );
	}
	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 *
	 * @param $transient
	 * @return object $ transient
	 */
	public function check_update( $transient )
	{
		if ( empty( $transient->checked ) ) {
			return $transient;
		}
		// Get the remote version
		$result = $this->get_info('version');
		// If a newer version is available, add the update
		if ( is_object($result) && version_compare( $this->current_version, $result->new_version, '<' ) ) {
			$transient->response[$this->plugin_slug] = $result;
		}
		return $transient;
	}
	/**
	 * Add our self-hosted description to the filter
	 *
	 * @param boolean $false
	 * @param array $action
	 * @param object $arg
	 * @return bool|object
	 */
	public function check_info($false, $action, $arg)
	{
		$result = false;
		if (isset($arg->slug) && $arg->slug === $this->slug) {
			$info = $this->get_info('info');
			if ( is_object($info) && empty($info->error) ){
				$result = $info;
			}
		}
		return $result;
	}
	/**
	 * Return the remote version
	 * 
	 * @return string $request
	 */
	public function get_info($action)
	{
		$params = array(
			'body' => array(
				'action'       => $action,
				'purchase_code'  => $this->purchase_code,
				'slug'  => $this->slug,
				'version' => $this->current_version
			),
		);
		
		$result = false;
		
		// Make the POST request
		$request = wp_remote_post( $this->update_url, $params );
		
		// Check if response is valid
		if ( !is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			if ( $response_body = unserialize( html_entity_decode( wp_remote_retrieve_body( $request ) ) ) ) {
				$result = $response_body;
			}
		}
		
		return $result;
	}

	/**
	 * Shows message on WP plugins page
	 */
	public function show_message() {
		if ( ! $this->is_activated || ! $this->purchase_code ) {
			$url = esc_url( 'admin.php?page=lievo-activation' );
			$link = sprintf( '<a href="%s">%s</a>', $url, 'Product Activation' );
			echo sprintf( ' To receive automatic updates a license activation is required. Please visit %s to activate your copy of LivIcons Evolution.', $link );
		}
	}
}