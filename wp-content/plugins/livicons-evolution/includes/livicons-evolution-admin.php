<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;


// Registering settings and validation of the plugin's options
function livicons_evolution_admin_init(){
	register_setting(
		'livicons_evolution_general_settings',
		'livicons_evolution_general_options',
		'livicons_evolution_validate_general_options'
	);
	register_setting(
		'livicons_evolution_visualization_settings',
		'livicons_evolution_visualization_options',
		'livicons_evolution_validate_visualization_options'
	);
	register_setting(
		'livicons_evolution_animation_settings',
		'livicons_evolution_animation_options',
		'livicons_evolution_validate_animation_options'
	);
	register_setting(
		'livicons_evolution_activation_data',
		'livicons_evolution_activation',
		'livicons_evolution_validate_activation'
	);
}
add_action( 'admin_init', 'livicons_evolution_admin_init' );


// Creating a plugin's settings menu
function livicons_evolution_create_menu() {
	global $livicons_evolution_setting_pages;
	$livicons_evolution_setting_pages = array();
	$livicons_evolution_setting_pages[0] = add_menu_page(
		'Livicons Evolution Settings',
		'LivIcons Evo',
		'manage_options',
		LIVICONS_EVOLUTION_SLUG,
		'livicons_evolution_display_settings',
		LIVICONS_EVOLUTION_URL . 'assets/img/lievo-menu-icon.png',
		99
	);
	$livicons_evolution_setting_pages[1] = add_submenu_page(
		LIVICONS_EVOLUTION_SLUG,
		'General Settings',
		'General Settings',
		'manage_options',
		'lievo-general',
		'livicons_evolution_display_settings'
	);
	$livicons_evolution_setting_pages[2] = add_submenu_page(
		LIVICONS_EVOLUTION_SLUG,
		'Visualization Options',
		'Visualization Options',
		'manage_options',
		'lievo-visualization',
		create_function( null, 'livicons_evolution_display_settings( "lievo_visual" );' )
	);
	$livicons_evolution_setting_pages[3] = add_submenu_page(
		LIVICONS_EVOLUTION_SLUG,
		'Animation Options',
		'Animation Options',
		'manage_options',
		'lievo-animation',
		create_function( null, 'livicons_evolution_display_settings( "lievo_anim" );' )
	);
	$livicons_evolution_setting_pages[4] = add_submenu_page(
		LIVICONS_EVOLUTION_SLUG,
		'CSS & JavaScript',
		'CSS & JavaScript',
		'manage_options',
		'lievo-css-javascript',
		create_function( null, 'livicons_evolution_display_settings( "lievo_css_javasript" );' )
	);
	$livicons_evolution_setting_pages[5] = add_submenu_page(
		LIVICONS_EVOLUTION_SLUG,
		'Product Activation',
		'Product Activation',
		'manage_options',
		'lievo-activation',
		create_function( null, 'livicons_evolution_display_settings( "lievo_activate" );' )
	);
	$livicons_evolution_setting_pages[6] = add_submenu_page(
		LIVICONS_EVOLUTION_SLUG,
		'About',
		'About',
		'manage_options',
		'lievo-about',
		create_function( null, 'livicons_evolution_display_settings( "lievo_about" );' )
	);
	remove_submenu_page(LIVICONS_EVOLUTION_SLUG, LIVICONS_EVOLUTION_SLUG);
}
add_action('admin_menu', 'livicons_evolution_create_menu');


// Display a "Settings" link on the main Plugins page
function livicons_evolution_add_plugin_setting_link( $links ) {
	return array_merge(
		array('settings' => '<a href="' . esc_url(admin_url('admin.php?page=lievo-general')) . '">Settings</a>'),
		$links
	);
}
add_filter('plugin_action_links_' . LIVICONS_EVOLUTION_PLUGIN_SLUG, 'livicons_evolution_add_plugin_setting_link');


// Display a more plugins link in meta
function livicons_evolution_more_deethemes_plugins( $links, $file ) {
	if ( $file == LIVICONS_EVOLUTION_PLUGIN_SLUG ) {
		return array_merge(
			$links,
			array( '<a href="http://codecanyon.net/collections/5913152-wordpress-plugins-by-deethemes?ref=DeeThemes" target="_blank">More plugins by DeeThemes</a>' )
		);
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'livicons_evolution_more_deethemes_plugins', 10, 2 );


// Define styles and scripts for setting pages
function livicons_evolution_assets_on_setting_pages( $hook ) {
	global $livicons_evolution_setting_pages;
	if( ! in_array( $hook, $livicons_evolution_setting_pages ) ) {
		return;
	};
	if( $hook === $livicons_evolution_setting_pages[4] ) { // 'CSS & JavaScript' page
		wp_enqueue_style( 'livicons_evolution_codemirror_styles', LIVICONS_EVOLUTION_URL . 'assets/css/LivIconsEvo.codemirror.css', false, LIVICONS_EVOLUTION_VERSION );
		wp_enqueue_script('livicons_evolution_codemirror_script', LIVICONS_EVOLUTION_URL . 'assets/js/LivIconsEvo.codemirror.js', false, LIVICONS_EVOLUTION_VERSION, true );
	};
	
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_style( 'livicons_evolution_styles' );
	wp_enqueue_style( 'livicons_evolution_settings_styles', LIVICONS_EVOLUTION_URL . 'assets/css/LivIconsEvo.settings.css', false, LIVICONS_EVOLUTION_VERSION );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_script('livicons_evolution_settings_script', LIVICONS_EVOLUTION_URL . 'assets/js/LivIconsEvo.settings.js', array('wp-color-picker'), LIVICONS_EVOLUTION_VERSION, true );
	wp_enqueue_script( 'livicons_evolution_tools' );
	wp_enqueue_script( 'livicons_evolution_defaults' );
	wp_enqueue_script( 'livicons_evolution_core' );
}
add_action( 'admin_enqueue_scripts', 'livicons_evolution_assets_on_setting_pages' );


// Saving the resulting JavaScript file (LivIconsEvo.WP.defaults.js)
function livicons_evolution_save_result_file( $options ) {
	if( ! current_user_can( 'manage_options' ) ) {
		wp_die( "You can't manage options." );
	}
	$filename = LIVICONS_EVOLUTION_PATH . 'assets/js/LivIconsEvo.WP.defaults.js';
	if ( ! file_exists( $filename ) || ! is_writable( $filename ) ) {
		wp_die( '<div class="error"><h3>"LivIconsEvo.WP.defaults.js" could not be created</h3>The file <strong>'.$filename.'</strong> cannot be saved.<br>Please check that the file exists and/or you need to make this file <strong>writable</strong> before you can use the plugin. See <a href="http://codex.wordpress.org/Changing_File_Permissions">the Codex</a> for more information.</div>' );
	};
	
	//saving a random value to use in declarations for preventing files cache
	update_option( 'livicons_evolution_dynamic_string', substr( md5(uniqid(rand(), true)), 0, 12) );

	if ( ! empty( $options ) ) {
		//preparing options for resulting JavaScript file
		if ( trim($options['additional_class']) !== '' ) {
			$a_class = ";jQuery(document).ready(function(){ jQuery('.livicon-evo').addClass('" . sanitize_html_class( $options['additional_class'] ) . "') });";
		} else {
			$a_class = '';
		};
		if ( $options['strokeWidth'] === 'original' ) {
			$stw = $options['strokeWidth'];
		} else if ( $options['strokeWidth'] === 'custom' ) {
			$stw = $options['customStrokeWidth'];
		};
		if ( $options['rotate'] === 'none' ) {
			$rot = $options['rotate'];
		} else if ( $options['rotate'] === 'custom' ) {
			$rot = $options['customRotate'];
		};
		if ( $options['colorsOnHover'] === 'hue' ) {
			$coh = 'hue' . $options['colorsOnHoverHue'];
		} else {
			$coh = $options['colorsOnHover'];
		};
		if ( $options['colorsWhenMorph'] === 'hue' ) {
			$cwm = 'hue' . $options['colorsWhenMorphHue'];
		} else {
			$cwm = $options['colorsWhenMorph'];
		};
		if ( $options['morphImage'] === 'none' ) {
			$mi = $options['morphImage'];
		} else if ( $options['morphImage'] === 'url' ) {
			$mi = $options['morphImageUrl'];
		};
		if ( $options['strokeWidthFactorOnHover'] === 'none' ) {
			$swfoh = $options['strokeWidthFactorOnHover'];
		} else if ( $options['strokeWidthFactorOnHover'] === 'custom' ) {
			$swfoh = $options['strokeWidthFactorOnHoverValue'];
		};
		if ( $options['eventOn'] === 'custom' ) {
			$en = htmlspecialchars_decode( $options['eventOnElem'] );
		} else {
			$en = $options['eventOn'];
		};
		if ( $options['drawColor'] === 'same' ) {
			$dc = $options['drawColor'];
		} else if ( $options['drawColor'] === 'custom' ) {
			$dc = $options['customDrawColor'];
		};
		
		if ($_SERVER['HTTP_HOST']) {
			$path_from_root = str_replace( str_replace( '\\', '/', $_SERVER['HTTP_HOST']), '', str_replace( '\\', '/', LIVICONS_EVOLUTION_URL));
			$path_from_root = str_replace( 'https://', '', $path_from_root);
			$path_from_root = str_replace( 'http://', '', $path_from_root);
		} else {
			$path_from_root = LIVICONS_EVOLUTION_URL;
		};
		
		$code = "/**" . PHP_EOL
			. " * PLEASE DO NOT EDIT THIS FILE!" . PHP_EOL
			. " * Make any changes in your WP dashboard on the 'Livicons Evolution Settings' page." . PHP_EOL
			. " */" . PHP_EOL
			. $a_class . PHP_EOL
			. "function LivIconsEvoDefaults(){ var default_options = {" . PHP_EOL
			. "  pathToFolder: '" . $path_from_root . 'assets/svg/' . "'," . PHP_EOL			
			. "  name: '" . $options['name'] . "'," . PHP_EOL
			. "  style: '" . $options['style'] . "'," . PHP_EOL
			. "  size: '" . $options['size'] . $options['sizeUnits'] . "'," . PHP_EOL
			. "  strokeStyle: '" . $options['strokeStyle'] . "'," . PHP_EOL
			. "  strokeWidth: '" . $stw . "'," . PHP_EOL
			. "  tryToSharpen: " . $options['tryToSharpen'] . "," . PHP_EOL
			. "  rotate: '" . $rot . "'," . PHP_EOL
			. "  flipHorizontal: " . $options['flipHorizontal'] . "," . PHP_EOL
			. "  flipVertical: " . $options['flipVertical'] . "," . PHP_EOL
			. "  strokeColor: '" . $options['strokeColor'] . "'," . PHP_EOL
			. "  strokeColorAction: '" . $options['strokeColorAction'] . "'," . PHP_EOL
			. "  strokeColorAlt: '" . $options['strokeColorAlt'] . "'," . PHP_EOL
			. "  strokeColorAltAction: '" . $options['strokeColorAltAction'] . "'," . PHP_EOL
			. "  fillColor: '" . $options['fillColor'] . "'," . PHP_EOL
			. "  fillColorAction: '" . $options['fillColorAction'] . "'," . PHP_EOL
			. "  solidColor: '" . $options['solidColor'] . "'," . PHP_EOL
			. "  solidColorAction: '" . $options['solidColorAction'] . "'," . PHP_EOL
			. "  solidColorBg: '" . $options['solidColorBg'] . "'," . PHP_EOL
			. "  solidColorBgAction: '" . $options['solidColorBgAction'] . "'," . PHP_EOL
			. "  colorsOnHover: '" . $coh . "'," . PHP_EOL
			. "  colorsHoverTime: " . $options['colorsHoverTime'] . "," . PHP_EOL
			. "  colorsWhenMorph: '" . $cwm . "'," . PHP_EOL
			. "  brightness: " . $options['brightness'] . "," . PHP_EOL
			. "  saturation: " . $options['saturation'] . "," . PHP_EOL
			. "  morphState: '" . $options['morphState'] . "'," . PHP_EOL
			. "  morphImage: '" . $mi . "'," . PHP_EOL
			. "  allowMorphImageTransform: " . $options['allowMorphImageTransform'] . "," . PHP_EOL
			. "  strokeWidthFactorOnHover: '" . $swfoh . "'," . PHP_EOL
			. "  strokeWidthOnHoverTime: " . $options['strokeWidthOnHoverTime'] . "," . PHP_EOL
			. "  keepStrokeWidthOnResize: " . $options['keepStrokeWidthOnResize'] . "," . PHP_EOL
			. "  animated: " . $options['animated'] . "," . PHP_EOL
			. "  eventType: '" . $options['eventType'] . "'," . PHP_EOL
			. "  eventOn: '" . $en . "'," . PHP_EOL
			. "  autoPlay: " . $options['autoPlay'] . "," . PHP_EOL
			. "  delay: " . $options['delay'] . "," . PHP_EOL
			. "  duration: 'default'," . PHP_EOL
			. "  repeat: 'default'," . PHP_EOL
			. "  repeatDelay: 'default'," . PHP_EOL
			. "  drawOnViewport: " . $options['drawOnViewport'] . "," . PHP_EOL
			. "  viewportShift: '" . $options['viewportShift'] . "'," . PHP_EOL
			. "  drawDelay: " . $options['drawDelay'] . "," . PHP_EOL
			. "  drawTime: " . $options['drawTime'] . "," . PHP_EOL
			. "  drawStagger: " . $options['drawStagger'] . "," . PHP_EOL
			. "  drawStartPoint: '" . $options['drawStartPoint'] . "'," . PHP_EOL
			. "  drawColor: '" . $dc . "'," . PHP_EOL
			. "  drawColorTime: " . $options['drawColorTime'] . "," . PHP_EOL
			. "  drawReversed: " . $options['drawReversed'] . "," . PHP_EOL
			. "  drawEase: '" . $options['drawEase'] . "'," . PHP_EOL
			. "  eraseDelay: " . $options['eraseDelay'] . "," . PHP_EOL
			. "  eraseTime: " . $options['eraseTime'] . "," . PHP_EOL
			. "  eraseStagger: " . $options['eraseStagger'] . "," . PHP_EOL
			. "  eraseStartPoint: '" . $options['eraseStartPoint'] . "'," . PHP_EOL
			. "  eraseReversed: " . $options['eraseReversed'] . "," . PHP_EOL
			. "  eraseEase: '" . $options['eraseEase'] . "'," . PHP_EOL
			. "  touchEvents: " . $options['touchEvents'] . "," . PHP_EOL
			. "  beforeAdd: false," . PHP_EOL
			. "  afterAdd: false," . PHP_EOL
			. "  beforeUpdate: false," . PHP_EOL
			. "  afterUpdate: false," . PHP_EOL
			. "  beforeRemove: false," . PHP_EOL
			. "  afterRemove: false," . PHP_EOL
			. "  beforeDraw: false," . PHP_EOL
			. "  afterDraw: false," . PHP_EOL
			. "  duringDraw: false," . PHP_EOL
			. "  beforeErase: false," . PHP_EOL
			. "  afterErase: false," . PHP_EOL
			. "  duringErase: false," . PHP_EOL
			. "  beforeAnim: false," . PHP_EOL
			. "  afterAnim: false," . PHP_EOL
			. "  duringAnim: false" . PHP_EOL
			. "  };" . PHP_EOL
			. "return default_options};";

		//saving result file to a disk
		$res = file_put_contents ( $filename, $code, LOCK_EX );
		if ( $res === false ) {
			if ( function_exists( 'add_settings_error' ) ) {
				add_settings_error( 'livicons_evolution_file_save_error', esc_attr( 'settings_updated' ), 'An error occurred! The resulting JavaScript file is not saved. Please try again.', 'error' );
			};
		};
	};//end check $options empty
}


// The content of LivIcons Evolution Settings page
function livicons_evolution_display_settings( $active_tab = '' ) {
	if( ! current_user_can( 'manage_options' ) ) {
		wp_die( "You can't manage options." );
	}
	?>
	
	<div class="wrap lievo-admin-settings">
		<h1>Livicons Evolution Settings</h1>
		
		<?php settings_errors();
		
		if( isset( $_GET[ 'tab' ] ) ) {
			$active_tab = $_GET[ 'tab' ];
		} else if( $active_tab == 'lievo_anim' ) {
			$active_tab = 'lievo_anim';
		} else if( $active_tab == 'lievo_visual' ) {
			$active_tab = 'lievo_visual';
		} else if( $active_tab == 'lievo_activate' ) {
			$active_tab = 'lievo_activate';
		} else if( $active_tab == 'lievo_css_javasript' ) {
			$active_tab = 'lievo_css_javasript';
		} else if( $active_tab == 'lievo_about' ) {
			$active_tab = 'lievo_about';
		} else {
			$active_tab = 'lievo_general';
		} // end if/else
		?>
		
		<h2 class="nav-tab-wrapper">
			<a href="?page=lievo-general" class="nav-tab <?php echo $active_tab == 'lievo_general' ? 'nav-tab-active' : ''; ?>">General settings</a>
			<a href="?page=lievo-visualization" class="nav-tab <?php echo $active_tab == 'lievo_visual' ? 'nav-tab-active' : ''; ?>">Visualization Options</a>
			<a href="?page=lievo-animation" class="nav-tab <?php echo $active_tab == 'lievo_anim' ? 'nav-tab-active' : ''; ?>">Animation Options</a>
			<a href="?page=lievo-css-javascript" class="nav-tab <?php echo $active_tab == 'lievo_css_javasript' ? 'nav-tab-active' : ''; ?>">Custom CSS &amp; JavaScript</a>
			<a href="?page=lievo-activation" class="nav-tab <?php echo $active_tab == 'lievo_activate' ? 'nav-tab-active' : ''; ?>">Product Activation</a>
			<a href="?page=lievo-about" class="nav-tab <?php echo $active_tab == 'lievo_about' ? 'nav-tab-active' : ''; ?>">About</a>
		</h2>

		<?php if( $active_tab == 'lievo_general' ) { ?>
			<form id="lievo_admin_form" method="post" action="options.php">
				<?php
				livicons_evolution_show_general_options();
				submit_button();
				?>
			</form>
		<?php } else if( $active_tab == 'lievo_visual' ) { ?>
			<form id="lievo_admin_form" method="post" action="options.php">
				<?php
				livicons_evolution_show_visualization_options();
				submit_button();
				?>
			</form>
		<?php } else if( $active_tab == 'lievo_anim' ) { ?>
			<form id="lievo_admin_form" method="post" action="options.php">
				<?php
				livicons_evolution_show_animation_options();
				submit_button();
				?>
			</form>
		<?php } else if( $active_tab == 'lievo_css_javasript' ) { ?>
			<form id="lievo_admin_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?page=lievo-css-javascript'; ?>">
				<?php
				livicons_evolution_show_css_javascript();
				?>
			</form>
		<?php } else if( $active_tab == 'lievo_activate' ) { ?>
			<form id="lievo_admin_form" method="post" action="options.php">
				<?php
				livicons_evolution_show_activation();
				?>
			</form>
		<?php } else { ?>
			<form>
				<?php
				livicons_evolution_show_about();
				?>
			</form>
		<?php } // end if/else 
		?>
	</div>

	<?php
} 


// Show general settings
function livicons_evolution_show_general_options() {
	settings_fields( 'livicons_evolution_general_settings' );
	$options = get_option( 'livicons_evolution_general_options' );
	?>
	<div id="lievo_general" class="lievo-tab-content">
		<h2>Default general settings</h2>
		<hr>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="additional_class">Additional Class</label>
					</th>
					<td>
						<input type="text" id="additional_class" class="text" name="livicons_evolution_general_options[additional_class]" value="<?php echo  sanitize_html_class( $options['additional_class'] ) ; ?>" placeholder="no class added" spellcheck="false" autocomplete="off">
						<p class="description">The class name <b>without</b> leading dot <code>.</code>, like <code>some_class_name</code> for adding to ALL the Livicons Evo.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Allow Shortcodes</th>
					<td>
						<fieldset>
							<label for="in_widgets">
								<input type="checkbox" id="in_widgets" name="livicons_evolution_general_options[in_widgets]" value="true" <?php checked( 'true', $options['in_widgets'] ); ?>> in widgets
							</label>
							<br>
							<label for="in_comments">
								<input type="checkbox" id="in_comments" name="livicons_evolution_general_options[in_comments]" value="true" <?php checked( 'true', $options['in_comments'] ); ?>> in comments
							</label>
							<br>
							<label for="in_excerpts">
								<input type="checkbox" id="in_excerpts" name="livicons_evolution_general_options[in_excerpts]" value="true" <?php checked( 'true', $options['in_excerpts'] ); ?>> in excerpts
							</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row">"Add LivIcon" Dialog Box</th>
					<td>
						<p>
							<label for="disable_anim_in_dialog">
								<input type="checkbox" id="disable_anim_in_dialog" name="livicons_evolution_general_options[disable_anim_in_dialog]" value="true" <?php checked( 'true', $options['disable_anim_in_dialog'] ); ?>> disable animations in list 
							</label>
						</p>
						<p class="description">If checked, all the LivIcons in a full list in the dialog box will <b>not</b> be animated. This is useful for admin back-end performance and does not affect the icons' presentation in a front-end of the site.</p>
						<br>
						<p>
							<label for="use_placeholder">
								<input type="checkbox" id="use_placeholder" name="livicons_evolution_general_options[use_placeholder]" value="true" <?php checked( 'true', $options['use_placeholder'] ); ?>> use icon placeholder 
							</label>
						</p>
						<p class="description">If checked, all the LivIcons in a full list in the dialog box will be replaced with a simple image placeholder and only their names will be unique. This is useful for admin back-end performance and does not affect the icons' presentation in a front-end of the site.</p>
					</td>
				</tr>
			</tbody>
		</table>
		<hr>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						Factory Defaults</th>
					<td>
						<fieldset>
							<label for="check_default_options_db">
								<input type="checkbox" id="check_default_options_db" name="livicons_evolution_general_options[check_default_options_db]" value="true" <?php checked( 'true', $options['check_default_options_db'] ); ?>> Restore factory defaults upon the plugin <b>deactivation + activation</b>
							</label>
						</fieldset>
						<p class="description"><b>Important!</b> Only check this option if you want to reset all the options upon this Plugin reactivation</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}


// Show visualization settings
function livicons_evolution_show_visualization_options() {
	settings_fields( 'livicons_evolution_visualization_settings' );
	$options = get_option( 'livicons_evolution_visualization_options' );
	?>
	<div id="lievo_visual" class="lievo-tab-content">
		<h2>Default visualization options</h2>
		<hr>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="name">Name</label>
					</th>
					<td>
						<select id="name" name="livicons_evolution_visualization_options[name]" aria-describedby="name-description">
							<?php
								$list = livicons_evolution_icons_list();
								foreach ($list as $icons_category => $value) {
									echo '<optgroup label="' . $icons_category . '">';
									foreach ($value as $icon) {
										$icon = str_replace('NEW_', '', $icon);
										echo '<option value="' . $icon . '" ' . selected( $options['name'], $icon ) . '>' . $icon . '</option>';
									};
									echo '</optgroup>';
								};
							?>
						</select>
						<p class="description" id="name-description">The default icon name, which will appear if the <b>name</b> option in a shortcode is omitted.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="style">Style</label>
					</th>
					<td>
						<select name="livicons_evolution_visualization_options[style]" id="style">
							<option value="original" <?php selected( $options['style'], 'original' ); ?>>original</option>
							<option value="lines" <?php selected( $options['style'], 'lines' ); ?>>lines</option>
							<option value="solid" <?php selected( $options['style'], 'solid' ); ?>>solid</option>
							<option value="linesAlt" <?php selected( $options['style'], 'linesAlt' ); ?>>linesAlt</option>
							<option value="filled" <?php selected( $options['style'], 'filled' ); ?>>filled</option>
						</select>
						<p class="description">One of five available styles.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="size">Size</label>
					</th>
					<td>
						<label>
							<input id="size" class="small-text" type="number" step="0.1" min="0.1" name="livicons_evolution_visualization_options[size]" value="<?php echo esc_attr( $options['size'] ); ?>">
						</label>
							<select name="livicons_evolution_visualization_options[sizeUnits]" id="sizeUnits">
								<option value="px" <?php selected( $options['sizeUnits'], 'px' ); ?>>px</option>
								<option value="%" <?php selected( $options['sizeUnits'], '%' ); ?>>%</option>
							</select>
						<p class="description">The default icons size in pixels or percents. Please set an integer value for pixel units.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="strokeStyle">Stroke Style</label>
					</th>
					<td>
						<select name="livicons_evolution_visualization_options[strokeStyle]" id="strokeStyle">
							<option value="original" <?php selected( $options['strokeStyle'], 'original' ); ?>>original</option>
							<option value="round" <?php selected( $options['strokeStyle'], 'round' ); ?>>round</option>
							<option value="square" <?php selected( $options['strokeStyle'], 'square' ); ?>>square</option>
						</select>
						<p class="description">Controls how stroke ends will look like.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Stroke Width</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[strokeWidth]" type="radio" value="original" <?php checked( 'original', $options['strokeWidth'] ); ?>> original
						</label>
						<label>
							<input id="strokeWidth" name="livicons_evolution_visualization_options[strokeWidth]" type="radio" value="custom" <?php checked( 'custom', $options['strokeWidth'] ); ?>> custom:
							<input id="customStrokeWidth" class="small-text" type="number" step="1" min="1" name="livicons_evolution_visualization_options[customStrokeWidth]" value="<?php echo esc_attr( $options['customStrokeWidth'] ); ?>">px
							<p class="description">The stroke width of SVG shapes. Leave it <code>original</code> or set any numeric value (pixels).</p>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">Try to Sharpen</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[tryToSharpen]" type="radio" value="true" <?php checked( 'true', $options['tryToSharpen'] ); ?>> true
						</label>
						<label>
							<input name="livicons_evolution_visualization_options[tryToSharpen]" type="radio" value="false" <?php checked( 'false', $options['tryToSharpen'] ); ?>> false
						</label>
						<p class="description">Apply or not a micro shift for SVG shapes to try to make them more sharp (crisp).</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Rotate</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[rotate]" type="radio" value="none" <?php checked( 'none', $options['rotate'] ); ?>> none
						</label>
						<label>
							<input id="rotate" name="livicons_evolution_visualization_options[rotate]" type="radio" value="custom" <?php checked( 'custom', $options['rotate'] ); ?>> custom:
							<input id="customRotate" class="small-text" type="number" step="0.5" min="0" max="360" name="livicons_evolution_visualization_options[customRotate]" value="<?php echo esc_attr( $options['customRotate'] ); ?>">deg
							<p class="description">The <code>none</code> or any desired value in deg from range <code>0 ... 360</code>.</p>
						</label>
					</td>
				</tr>
				<tr>
					<th scope="row">Flip Horizontal</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[flipHorizontal]" type="radio" value="true" <?php checked( 'true', $options['flipHorizontal'] ); ?>> true
						</label>
						<label>
							<input name="livicons_evolution_visualization_options[flipHorizontal]" type="radio" value="false" <?php checked( 'false', $options['flipHorizontal'] ); ?>> false
						</label>
						<p class="description"><code>true</code> will flip icons horizontally.</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Flip Vertical</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[flipVertical]" type="radio" value="true" <?php checked( 'true', $options['flipVertical'] ); ?>> true
						</label>
						<label>
							<input name="livicons_evolution_visualization_options[flipVertical]" type="radio" value="false" <?php checked( 'false', $options['flipVertical'] ); ?>> false
						</label>
						<p class="description"><code>true</code> will flip icons vertically.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="strokeColor">Stroke Color</label>
					</th>
					<td>
						<label>
							<input class="lievo-color-picker" type="text" id="strokeColor" name="livicons_evolution_visualization_options[strokeColor]" value="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColor'] ); ?>" data-savedcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColor'] ); ?>">
						</label>
						<p class="description">The stroke color of SVG shapes. Takes effect when the <b>style</b> option is set to either <code>filled</code> or <code>lines</code> or <code>linesAlt</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="strokeColorAction">Stroke Color Action</label>
					</th>
					<td>
						<label>
							<input class="lievo-color-picker" type="text" id="strokeColorAction" name="livicons_evolution_visualization_options[strokeColorAction]" value="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColorAction'] ); ?>" data-savedcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColorAction'] ); ?>">
						</label>
						<p class="description">Takes effect when the <b>colorsOnHover</b> or <b>colorsWhenMorph</b> options are set to <code>custom</code> and the <b>style</b> option is either <code>original</code> or <code>filled</code> or <code>lines</code> or <code>linesAlt</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="strokeColorAlt">Stroke Color Alt</label>
					</th>
					<td>
						<label>
							<input class="lievo-color-picker" type="text" id="strokeColorAlt" name="livicons_evolution_visualization_options[strokeColorAlt]" value="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColorAlt'] ); ?>" data-savedcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColorAlt'] ); ?>">
						</label>
						<p class="description">The alternative stroke color of SVG shapes. Takes effect when the <b>style</b> option is set to <code>linesAlt</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="strokeColorAltAction">Stroke Color Alt Action</label>
					</th>
					<td>
						<label>
							<input class="lievo-color-picker" type="text" id="strokeColorAltAction" name="livicons_evolution_visualization_options[strokeColorAltAction]" value="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColorAltAction'] ); ?>" data-savedcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColorAltAction'] ); ?>">
						</label>
						<p class="description">Takes effect when the <b>colorsOnHover</b> or <b>colorsWhenMorph</b> options are set to <code>custom</code> and the <b>style</b> option is <code>linesAlt</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="fillColor">Fill Color</label>
					</th>
					<td>
						<label>
							<input class="lievo-color-picker" type="text" id="fillColor" name="livicons_evolution_visualization_options[fillColor]" value="<?php echo livicons_evolution_sanitize_hex_color( $options['fillColor'] ); ?>" data-savedcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['fillColor'] ); ?>">
						</label>
						<p class="description">The fill color of SVG shapes. Takes effect when the <b>style</b> option is set to <code>filled</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="fillColorAction">Fill Color Action</label>
					</th>
					<td>
						<label>
							<input class="lievo-color-picker" type="text" id="fillColorAction" name="livicons_evolution_visualization_options[fillColorAction]" value="<?php echo livicons_evolution_sanitize_hex_color( $options['fillColorAction'] ); ?>" data-savedcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['fillColorAction'] ); ?>">
						</label>
						<p class="description">Takes effect when the <b>colorsOnHover</b> or <b>colorsWhenMorph</b> options are set to <code>custom</code> and the <b>style</b> option is either <code>original</code> or <code>filled</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="solidColor">Solid Color</label>
					</th>
					<td>
						<label>
							<input class="lievo-color-picker" type="text" id="solidColor" name="livicons_evolution_visualization_options[solidColor]" value="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColor'] ); ?>" data-savedcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColor'] ); ?>">
						</label>
						<p class="description">The main color of SVG shapes when the <b>style</b> option is set to <code>solid</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="solidColorAction">Solid Color Action</label>
					</th>
					<td>
						<label>
							<input class="lievo-color-picker" type="text" id="solidColorAction" name="livicons_evolution_visualization_options[solidColorAction]" value="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColorAction'] ); ?>" data-savedcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColorAction'] ); ?>">
						</label>
						<p class="description">Takes effect when the <b>colorsOnHover</b> or <b>colorsWhenMorph</b> options are set to <code>custom</code> and the <b>style</b> option is <code>solid</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="solidColorBg">Background Solid Color</label>
					</th>
					<td>
						<label>
							<input class="lievo-color-picker" type="text" id="solidColorBg" name="livicons_evolution_visualization_options[solidColorBg]" value="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColorBg'] ); ?>" data-savedcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColorBg'] ); ?>">
						</label>
						<p class="description">The color of a background element on your page, on which an icon will appear. Takes effect when the <b>style</b> option is set to <code>solid</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="solidColorBgAction">Background Solid Color Action</label>
					</th>
					<td>
						<label>
							<input class="lievo-color-picker" type="text" id="solidColorBgAction" name="livicons_evolution_visualization_options[solidColorBgAction]" value="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColorBgAction'] ); ?>" data-savedcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColorBgAction'] ); ?>">
						</label>
						<p class="description">Takes effect when the <b>style</b> option is <code>solid</code>. This option is useful when a background element (on which a LivIcon lays) changes its color on hover event too.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Colors On Hover</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[colorsOnHover]" type="radio" value="none" <?php checked( 'none', $options['colorsOnHover'] ); ?>> none
						</label>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[colorsOnHover]" type="radio" value="lighter" <?php checked( 'lighter', $options['colorsOnHover'] ); ?>> lighter
						</label>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[colorsOnHover]" type="radio" value="darker" <?php checked( 'darker', $options['colorsOnHover'] ); ?>> darker
						</label>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[colorsOnHover]" type="radio" value="custom" <?php checked( 'custom', $options['colorsOnHover'] ); ?>> custom
						</label>
						<label>
							<input id="colorsOnHover" name="livicons_evolution_visualization_options[colorsOnHover]" type="radio" value="hue" <?php checked( 'hue', $options['colorsOnHover'] ); ?>> hue
							<input id="colorsOnHoverHue" class="small-text" type="number" step="1" min="0" max="360" name="livicons_evolution_visualization_options[colorsOnHoverHue]" value="<?php echo esc_attr( $options['colorsOnHoverHue'] ); ?>">
						</label>
						<p class="description">The one of the five possible effects. For example, <code>hue180</code> will change an icon's colors around 180 deg of a color wheel.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="colorsHoverTime">Colors Hover Time</label>
					</th>
					<td>
						<input id="colorsHoverTime" class="small-text" type="number" step="0.05" min="0" name="livicons_evolution_visualization_options[colorsHoverTime]" value="<?php echo esc_attr( $options['colorsHoverTime'] ); ?>"> seconds
						<p class="description">The duration of changing colors, when the <b>colorsOnHover</b> option is <b>not</b> set to <code>none</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Colors When Morph</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[colorsWhenMorph]" type="radio" value="none" <?php checked( 'none', $options['colorsWhenMorph'] ); ?>> none
						</label>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[colorsWhenMorph]" type="radio" value="lighter" <?php checked( 'lighter', $options['colorsWhenMorph'] ); ?>> lighter
						</label>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[colorsWhenMorph]" type="radio" value="darker" <?php checked( 'darker', $options['colorsWhenMorph'] ); ?>> darker
						</label>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[colorsWhenMorph]" type="radio" value="custom" <?php checked( 'custom', $options['colorsWhenMorph'] ); ?>> custom
						</label>
						<label>
							<input id="colorsWhenMorph" name="livicons_evolution_visualization_options[colorsWhenMorph]" type="radio" value="hue" <?php checked( 'hue', $options['colorsWhenMorph'] ); ?>> hue
							<input id="colorsWhenMorphHue" class="small-text" type="number" step="1" min="0" max="360" name="livicons_evolution_visualization_options[colorsWhenMorphHue]" value="<?php echo esc_attr( $options['colorsWhenMorphHue'] ); ?>">
						</label>
						<p class="description"><b>For morph icons only.</b> The one of the five possible effects. For example, <code>hue180</code> will change a morph icon's colors around 180 deg of a color wheel.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="brightness">Brightness</label>
					</th>
					<td>
						<input id="brightness" class="small-text" type="number" step="0.01" min="0" max="1" name="livicons_evolution_visualization_options[brightness]" value="<?php echo esc_attr( $options['brightness'] ); ?>">
						<p class="description">The factor (multiplier) of changing colors' brightness, when the <b>colorsOnHover</b> or <b>colorsWhenMorph</b> options are set to <code>lighter</code> or <code>darker</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="saturation">Saturation</label>
					</th>
					<td>
						<input id="saturation" class="small-text" type="number" step="0.01" min="0" max="1" name="livicons_evolution_visualization_options[saturation]" value="<?php echo esc_attr( $options['saturation'] ); ?>">
						<p class="description">The factor (multiplier) of changing colors' saturation, when the <b>colorsOnHover</b> or <b>colorsWhenMorph</b> options are set to <code>lighter</code> or <code>darker</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Morph State</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[morphState]" type="radio" value="start" <?php checked( 'start', $options['morphState'] ); ?>> start
						</label>
						<label>
							<input name="livicons_evolution_visualization_options[morphState]" type="radio" value="end" <?php checked( 'end', $options['morphState'] ); ?>> end
						</label>
						<p class="description"><b>For morph icons only.</b> The initial state of morph icons.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Morph Image</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[morphImage]" type="radio" value="none" <?php checked( 'none', $options['morphImage'] ); ?>> none
						</label>
						<label>
							<input id="morphImage" name="livicons_evolution_visualization_options[morphImage]" type="radio" value="url" <?php checked( 'url', $options['morphImage'] ); ?>> URL:
							<input id="morphImageUrl" class="regular-text" type="text" name="livicons_evolution_visualization_options[morphImageUrl]" value="<?php echo esc_url( $options['morphImageUrl'] ); ?>" placeholder="http(s)://www.site.com/path/to/image.jpg" spellcheck="false" autocomplete="off">
						</label>
						<p class="description"><b>For morph icons only.</b> "Background" morph icons can have an image (JPG, PNG, GIF, SVG) inside them. For example, avatars or photos of your users can be placed inside <code>morph-square-sticker.svg</code> icon. The value can look like <code>http://www.site.com/path/to/user_avatar.jpg</code> or <code>https://www.site.com/path/to/user_avatar.jpg</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Allow Morph Image Transform</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[allowMorphImageTransform]" type="radio" value="true" <?php checked( 'true', $options['allowMorphImageTransform'] ); ?>> true
						</label>
						<label>
							<input name="livicons_evolution_visualization_options[allowMorphImageTransform]" type="radio" value="false" <?php checked( 'false', $options['allowMorphImageTransform'] ); ?>> false
						</label>
						<p class="description"><b>For morph icons only.</b> If <code>true</code> the inside image will be rotated and/or flipped with a morph icon together.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Stroke Width Factor On Hover</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[strokeWidthFactorOnHover]" type="radio" value="none" <?php checked( 'none', $options['strokeWidthFactorOnHover'] ); ?>> none
						</label>
						<label>
							<input id="strokeWidthFactorOnHover" name="livicons_evolution_visualization_options[strokeWidthFactorOnHover]" type="radio" value="custom" <?php checked( 'custom', $options['strokeWidthFactorOnHover'] ); ?>> custom:
							<input id="strokeWidthFactorOnHoverValue" class="small-text" type="number" step="0.1" min="0" name="livicons_evolution_visualization_options[strokeWidthFactorOnHoverValue]" value="<?php echo esc_attr( $options['strokeWidthFactorOnHoverValue'] ); ?>">
						</label>
						<p class="description">Takes effect on mouse hover event. For example, to increase stroke width twice set the option to <code>2</code>. And the opposite, to decrease twice set it to <code>0.5</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="strokeWidthOnHoverTime">Stroke Width On Hover Time</label>
					</th>
					<td>
						<input id="strokeWidthOnHoverTime" class="small-text" type="number" step="0.05" min="0" name="livicons_evolution_visualization_options[strokeWidthOnHoverTime]" value="<?php echo esc_attr( $options['strokeWidthOnHoverTime'] ); ?>"> seconds
						<p class="description">The duration of changing stroke width when the <b>strokeWidthFactorOnHover</b> option is <b>not</b> set to <code>none</code>.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Keep Stroke Width On Resize</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_visualization_options[keepStrokeWidthOnResize]" type="radio" value="true" <?php checked( 'true', $options['keepStrokeWidthOnResize'] ); ?>> true
						</label>
						<label>
							<input name="livicons_evolution_visualization_options[keepStrokeWidthOnResize]" type="radio" value="false" <?php checked( 'false', $options['keepStrokeWidthOnResize'] ); ?>> false
						</label>
						<p class="description"><code>true</code> will keep the stroke width of SVG shapes when the <b>strokeWidth</b> option is <b>not</b> set to <code>original</code>. Takes effect when and if an icon's size is changed for different screen sizes via media queries, for example.</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}


// Show animation settings
function livicons_evolution_show_animation_options() {
	settings_fields( 'livicons_evolution_animation_settings' );
	$options = get_option( 'livicons_evolution_animation_options' );
	?>
	<div id="lievo_anim" class="lievo-tab-content">
		<h2>Default animation options</h2>
		<hr>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">Animated</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_animation_options[animated]" type="radio" value="true" <?php checked( 'true', $options['animated'] ); ?>> true
						</label>
						<label>
							<input name="livicons_evolution_animation_options[animated]" type="radio" value="false" <?php checked( 'false', $options['animated'] ); ?>> false
						</label>
						<p class="description">If <code>false</code> icons are static.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="eventType">Event Type</label>
					</th>
					<td>
						<select name="livicons_evolution_animation_options[eventType]" id="eventType">
							<option value="none" <?php selected( $options['eventType'], 'none' ); ?>>none</option>
							<option value="hover" <?php selected( $options['eventType'], 'hover' ); ?>>hover</option>
							<option value="click" <?php selected( $options['eventType'], 'click' ); ?>>click</option>
						</select>
						<p class="description">If is set to <code>none</code> and <b>animated</b> option is set to <code>true</code>, icons can still be animated via programmatic API with JavaScript method <code>.playLiviconEvo();</code></p>
					</td>
				</tr>
				<tr>
					<th scope="row">Event On</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_animation_options[eventOn]" type="radio" value="self" <?php checked( 'self', $options['eventOn'] ); ?>> self
						</label>
						<label class="margin-right-20">
							<input name="livicons_evolution_animation_options[eventOn]" type="radio" value="parent" <?php checked( 'parent', $options['eventOn'] ); ?>> parent
						</label>
						<label class="margin-right-20">
							<input name="livicons_evolution_animation_options[eventOn]" type="radio" value="grandparent" <?php checked( 'grandparent', $options['eventOn'] ); ?>> grandparent
						</label>
						<label>
							<input id="eventOn" name="livicons_evolution_animation_options[eventOn]" type="radio" value="custom" <?php checked( 'custom', $options['eventOn'] ); ?>> CSS selector:
							<input id="eventOnElem" class="text" type="text" name="livicons_evolution_animation_options[eventOnElem]" value="<?php echo esc_attr( $options['eventOnElem'] ); ?>" placeholder="no custom selectors" spellcheck="false" autocomplete="off">
						</label>
						<p class="description">Hover and click events can be bind not only on an icon itself (<code>self</code> value), but on its <code>parent</code>, <code>grandparent</code> or any other element with <code>#custom_id</code> or <code>.custom_class</code> on your page.<br>
						Due to security reasons only these characters are allowed for CSS selector: <code>A-Za-z</code> <code>0-9</code> <code>_</code> <code>-</code> <code>.</code> <code>&gt;</code> <code>&nbsp;</code>(space) <code>#</code> <code>,</code></p>
					</td>
				</tr>
				<tr>
					<th scope="row">Auto Play</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_animation_options[autoPlay]" type="radio" value="true" <?php checked( 'true', $options['autoPlay'] ); ?>> true
						</label>
						<label>
							<input name="livicons_evolution_animation_options[autoPlay]" type="radio" value="false" <?php checked( 'false', $options['autoPlay'] ); ?>> false
						</label>
						<p class="description">If <code>true</code> animations will start automatically. Please be very careful with this option, especially with "looped" animations.<br><b>It is NOT recommended to set this option globally to <code>true</code> by default!</b></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="delay">Delay</label>
					</th>
					<td>
						<input id="delay" class="small-text" type="number" step="0.05" min="0" name="livicons_evolution_animation_options[delay]" value="<?php echo esc_attr( $options['delay'] ); ?>"> seconds
						<p class="description">The delay in seconds before an animation starts.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Draw On Viewport</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_animation_options[drawOnViewport]" type="radio" value="true" <?php checked( 'true', $options['drawOnViewport'] ); ?>> true
						</label>
						<label>
							<input name="livicons_evolution_animation_options[drawOnViewport]" type="radio" value="false" <?php checked( 'false', $options['drawOnViewport'] ); ?>> false
						</label>
						<p class="description">If <code>true</code>, icons will be "drawn" when they appear first time in a user's browser viewport.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="viewportShift">Viewport Shift</label>
					</th>
					<td>
						<select name="livicons_evolution_animation_options[viewportShift]" id="viewportShift">
							<option value="none" <?php selected( $options['viewportShift'], 'none' ); ?>>none</option>
							<option value="oneHalf" <?php selected( $options['viewportShift'], 'oneHalf' ); ?>>oneHalf</option>
							<option value="oneThird" <?php selected( $options['viewportShift'], 'oneThird' ); ?>>oneThird</option>
							<option value="full" <?php selected( $options['viewportShift'], 'full' ); ?>>full</option>
						</select>
						<p class="description">Takes effect when the <b>drawOnViewport</b> option is set to <code>true</code>. It means that an animation starts only if SVG is in a users browser's viewport at least the chosen value calculated from an icon's height.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="drawDelay">Draw Delay</label>
					</th>
					<td>
						<input id="drawDelay" class="small-text" type="number" step="0.05" min="0" name="livicons_evolution_animation_options[drawDelay]" value="<?php echo esc_attr( $options['drawDelay'] ); ?>"> seconds
						<p class="description">The delay in seconds before a "drawing" animation starts.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="drawTime">Draw Time</label>
					</th>
					<td>
						<input id="drawTime" class="small-text" type="number" step="0.05" min="0" name="livicons_evolution_animation_options[drawTime]" value="<?php echo esc_attr( $options['drawTime'] ); ?>"> seconds
						<p class="description">The duration in seconds for a "drawing" animation of each icon's shape.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="drawStagger">Draw Stagger</label>
					</th>
					<td>
						<input id="drawStagger" class="small-text" type="number" step="0.05" min="0" name="livicons_evolution_animation_options[drawStagger]" value="<?php echo esc_attr( $options['drawStagger'] ); ?>"> seconds
						<p class="description">The delay in seconds during a "drawing" animation for each icon's shape.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="drawStartPoint">Draw Start Point</label>
					</th>
					<td>
						<select name="livicons_evolution_animation_options[drawStartPoint]" id="drawStartPoint">
							<option value="start" <?php selected( $options['drawStartPoint'], 'start' ); ?>>start</option>
							<option value="middle" <?php selected( $options['drawStartPoint'], 'middle' ); ?>>middle</option>
							<option value="end" <?php selected( $options['drawStartPoint'], 'end' ); ?>>end</option>
						</select>
						<p class="description">The starting point from where a "drawing" animation starts for each icon's shape.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Draw Color</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_animation_options[drawColor]" type="radio" value="same" <?php checked( 'same', $options['drawColor'] ); ?>> same
						</label>
						<label>
							<input id="customValueDrawColor" name="livicons_evolution_animation_options[drawColor]" type="radio" value="custom" <?php checked( 'custom', $options['drawColor'] ); ?>> custom:
						</label>
						<span id="customDrawColor">
							<input class="lievo-color-picker" type="text" name="livicons_evolution_animation_options[customDrawColor]" value="<?php echo livicons_evolution_sanitize_hex_color( $options['customDrawColor'] ); ?>" data-savedcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['customDrawColor'] ); ?>">
						</span>
						<p class="description">The color of "drawing" lines. The same as each SVG shape has or custom value for all of them.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="drawColorTime">Draw Color Time</label>
					</th>
					<td>
						<input id="drawColorTime" class="small-text" type="number" step="0.05" min="0" name="livicons_evolution_animation_options[drawColorTime]" value="<?php echo esc_attr( $options['drawColorTime'] ); ?>"> seconds
						<p class="description">The duration in seconds for a changing colors when a "drawing" animation ends.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Draw Reversed</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_animation_options[drawReversed]" type="radio" value="true" <?php checked( 'true', $options['drawReversed'] ); ?>> true
						</label>
						<label>
							<input name="livicons_evolution_animation_options[drawReversed]" type="radio" value="false" <?php checked( 'false', $options['drawReversed'] ); ?>> false
						</label>
						<p class="description"><code>true</code> will reverse the order of shapes drawing.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="drawEase">Draw Ease</label>
					</th>
					<td>
						<input class="text" type="text" id="drawEase" name="livicons_evolution_animation_options[drawEase]" value="<?php echo esc_attr( $options['drawEase'] ); ?>" spellcheck="false" autocomplete="off">
						<p class="description">The string value of easing function according to <a href='http://greensock.com/docs/#/HTML5/Easing/Power1/' target='_blank'>GreenSock Ease Visualizer</a></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="eraseDelay">Erase Delay</label>
					</th>
					<td>
						<input id="eraseDelay" class="small-text" type="number" step="0.05" min="0" name="livicons_evolution_animation_options[eraseDelay]" value="<?php echo esc_attr( $options['eraseDelay'] ); ?>"> seconds
						<p class="description">The delay in seconds before an "erasing" animation starts.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="eraseTime">Erase Time</label>
					</th>
					<td>
						<input id="eraseTime" class="small-text" type="number" step="0.05" min="0" name="livicons_evolution_animation_options[eraseTime]" value="<?php echo esc_attr( $options['eraseTime'] ); ?>"> seconds
						<p class="description">The duration in seconds for an "erasing" animation of each icon's shape.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="eraseStagger">Erase Stagger</label>
					</th>
					<td>
						<input id="eraseStagger" class="small-text" type="number" step="0.05" min="0" name="livicons_evolution_animation_options[eraseStagger]" value="<?php echo esc_attr( $options['eraseStagger'] ); ?>"> seconds
						<p class="description">The delay in seconds during an "erasing" animation for each icon's shape.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="eraseStartPoint">Erase Start Point</label>
					</th>
					<td>
						<select name="livicons_evolution_animation_options[eraseStartPoint]" id="eraseStartPoint">
							<option value="start" <?php selected( $options['eraseStartPoint'], 'start' ); ?>>start</option>
							<option value="middle" <?php selected( $options['eraseStartPoint'], 'middle' ); ?>>middle</option>
							<option value="end" <?php selected( $options['eraseStartPoint'], 'end' ); ?>>end</option>
						</select>
						<p class="description">The starting point from where an "erasing" animation starts for each icon's shape.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">Erase Reversed</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_animation_options[eraseReversed]" type="radio" value="true" <?php checked( 'true', $options['eraseReversed'] ); ?>> true
						</label>
						<label>
							<input name="livicons_evolution_animation_options[eraseReversed]" type="radio" value="false" <?php checked( 'false', $options['eraseReversed'] ); ?>> false
						</label>
						<p class="description"><code>true</code> will reverse the order of shapes erasing.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="eraseEase">Erase Ease</label>
					</th>
					<td>
						<input class="text" type="text" id="eraseEase" name="livicons_evolution_animation_options[eraseEase]" value="<?php echo esc_attr( $options['eraseEase'] ); ?>" spellcheck="false" autocomplete="off">
						<p class="description">The string value of easing function according to <a href='http://greensock.com/docs/#/HTML5/Easing/Power1/' target='_blank'>GreenSock Ease Visualizer</a></p>
					</td>
				</tr>
				<tr>
					<th scope="row">Touch Events</th>
					<td>
						<label class="margin-right-20">
							<input name="livicons_evolution_animation_options[touchEvents]" type="radio" value="true" <?php checked( 'true', $options['touchEvents'] ); ?>> true
						</label>
						<label>
							<input name="livicons_evolution_animation_options[touchEvents]" type="radio" value="false" <?php checked( 'false', $options['touchEvents'] ); ?>> false
						</label>
						<p class="description"><b>This option is highly experimental!</b><br>
						Apply or not special events handlers (<b>touchstart</b> and <b>touchend</b>) for touch devices. It prevents default action of the events. Please carefully test within a draft document with touch supporting devices before using on a production site.</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}


// Show custom CSS and JavaScript code
function livicons_evolution_show_css_javascript() {
	$uploads_dir_params = wp_upload_dir();
	$uploads_dir = $uploads_dir_params['basedir'] . '/' . LIVICONS_EVOLUTION_SLUG;
	$custom_css_file = $uploads_dir . '/LivIconsEvo.custom.css';
	$custom_js_file = $uploads_dir . '/LivIconsEvo.custom.js';
	if ( is_readable( $custom_css_file ) ) {
		$custom_css = file_get_contents( $custom_css_file );
	} else {
		$custom_css = '';
	};
	if ( is_readable( $custom_js_file ) ) {
		$custom_js = file_get_contents( $custom_js_file );
	} else {
		$custom_js = '';
	};
	?>
	<div id="lievo_css_javascript" class="lievo-tab-content">
		<h2>Custom CSS and JavaScript code</h2>
		<hr>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						Paste Your Custom CSS
					</th>
					<td>
						<textarea name="lievo_custom_css" id="lievo_custom_css" class="large-text code" rows="12"><?php echo htmlspecialchars( $custom_css ) ?></textarea>
						<p class="description">Add custom CSS code to the plugin without modifying existing files.</p>
						<p class="submit">
							<input type="submit" name="save_css" id="save_css" class="button button-primary" value="Save file">
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Paste Your Custom JavaScript
					</th>
					<td>
						<textarea name="lievo_custom_js" id="lievo_custom_js" class="large-text code" rows="12"><?php echo htmlspecialchars( $custom_js ) ?></textarea>
						<p class="description">Add custom JavaScript code to the plugin without modifying existing files.</p>
						<p class="submit">
							<input type="submit" name="save_js" id="save_js" class="button button-primary" value="Save file">
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}
// Action for saving custom CSS and JS files
add_action( 'livicons_evolution_submit_custom_save', 'livicons_evolution_save_custom_css_javascript' );
// Checking if [save_css] or [save_js] buttons are clicked
if( count( $_POST ) > 0 && ( isset( $_POST['save_css'] ) || isset( $_POST['save_js'] ) ) ) {
	do_action( 'livicons_evolution_submit_custom_save' ); 
}
function livicons_evolution_save_custom_css_javascript() {
	if ( isset( $_POST['save_css'] ) ){
		$mime = 'css';
		$code = $_POST['lievo_custom_css'];
	} elseif ( isset( $_POST['save_js'] ) ) {
		$mime = 'js';
		$code = $_POST['lievo_custom_js'];
	} else {
		return;
	};
	$file = null;
	$uploads_dir_params = wp_upload_dir();
	$uploads_dir = $uploads_dir_params['basedir'] . '/' . LIVICONS_EVOLUTION_SLUG;
	switch ( $mime ) {
		case 'css':
			$file = $uploads_dir . '/LivIconsEvo.custom.css';
			break;
		case 'js':
			$file = $uploads_dir . '/LivIconsEvo.custom.js';
			break;
	}
	if ( $file ) {
		if ( ! is_dir( $uploads_dir ) ) {
		  wp_mkdir_p( $uploads_dir );
		};
		global $livicons_evolution_notice_type;
		if ( file_exists( $file ) && ! is_writable( $file ) ) {//file exists, but not writable
			$livicons_evolution_notice_type = 'write_error';
			add_action( 'admin_notices', 'livicons_evolution_custom_admin_notice' );
			return;
		} else {
			$res = file_put_contents( $file, stripslashes( $code ), LOCK_EX );
			if ( $res === false ) {
				$livicons_evolution_notice_type = 'error';
				add_action( 'admin_notices', 'livicons_evolution_custom_admin_notice' );
			} else {
				update_option( 'livicons_evolution_dynamic_string', substr( md5(uniqid(rand(), true)), 0, 12) );
				$livicons_evolution_notice_type = 'success';
				add_action( 'admin_notices', 'livicons_evolution_custom_admin_notice' );
				return;
			};
		};
	};
}
function livicons_evolution_custom_admin_notice() {
	global $livicons_evolution_notice_type;
	if ( $livicons_evolution_notice_type == 'success' ) { ?>
		<div class="notice notice-success is-dismissible">
			<p><strong>File saved.</strong></p>
		</div>
	<?php } elseif ( $livicons_evolution_notice_type == 'error' ) { ?>
		<div class="notice notice-error is-dismissible">
			<p><strong>An error occurred! The file is not saved. Please try again.</strong></p>
		</div>
	<?php } elseif ( $livicons_evolution_notice_type == 'write_error' ) { ?>
		<div class="notice notice-error is-dismissible">
			<p><strong>The file id not <em>writable</em> and cannot be saved.<br>You need to make it <em>writable</em> before you can use the custom code. See <a href="http://codex.wordpress.org/Changing_File_Permissions">the Codex</a> for more information.</strong>
			</p>
		</div>
	<?php }
}


// Show activation page
function livicons_evolution_show_activation() {
	settings_fields( 'livicons_evolution_activation_data' );
	$activation = get_option( 'livicons_evolution_activation' );
	$code = $activation['purchase_code'];
	$is_act = $activation['is_activated'];
	if ( $is_act === 'true' ) {
		$disabled = 'disabled="disabled"';
	} else {
		$disabled = '';
	}
	?>
	<div id="lievo_activate" class="lievo-tab-content">
		<?php if ( $is_act === 'true' ) { ?>
			<p class="normal">Your copy is activated. Automatic updates are available for this plugin. Thank you for the purchasing!<br>
			If you want to use this purchase code on <b>another</b> WordPress installation, please be sure to <b>deactivate</b> it first.</p>
		<?php } else { ?>
			<p class="normal">By activating LivIcons Evolution license you will get access to <b>direct plugin updates</b>.</p>
		<?php } ?>
		
		<hr>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						<label for="purchase_code">Purchase Code</label>
					</th>
					<td>
						<input type="text" id="purchase_code" class="regular-text" name="livicons_evolution_activation[purchase_code]" value="<?php echo sanitize_key( $code ) ; ?>" placeholder="your purchase code" spellcheck="false" autocomplete="off"<?php echo $disabled; ?>>
						<?php if ( $is_act === 'true') { ?>
							<p class="description">The code is valid.</p>
						<?php } else { ?>
							<p class="description">Please enter your purchase code. <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code" target="_blank">Where is my purchase code?</a></p>
							<p class="description">Don't have a valid license yet? <a href="http://codecanyon.net/item/livicons-evolution-for-wordpress-the-next-generation-of-the-truly-animated-vector-icons/16986405?ref=DeeThemes" target="_blank">Purchase LivIcons Evolution</a></p>
						<?php } ?>
						<input type="hidden" id="is_activated" name="livicons_evolution_activation[is_activated]" value="<?php echo esc_attr( $is_act ) ; ?>">
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
	if ( $is_act === 'true') {
		submit_button('Deactivate', 'lievo-btn-deactivate');
	} else {
		submit_button('Activate LivIcons Evolution');
	}
}


// Show about page
function livicons_evolution_show_about() {
	?>
	<div id="lievo_about" class="lievo-tab-content">
		<div class="wp-badge">Version <?php echo esc_attr(LIVICONS_EVOLUTION_VERSION) ?></div>
		<h2 class="about-header">Thank you for choosing LivIcons Evolution!</h2>
		<p class="normal"><em>The New, Completely Redeveloped, Animated Vector Icons</em></p>
		<p class="normal">LivIcons Evolution is the next modern generation of a classic live icons pack with cross browser vector icons with individual mini animations for each one. They are based on SVG (Scalable Vector Graphic), powered by JavaScript, work in all modern browsers and look perfect at any devices.</p>
		<div class="clear"></div>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row">
						Icons total
					</th>
					<td>
						<p class="description">
							<b><?php echo esc_attr( substr(LIVICONS_EVOLUTION_VERSION, -3) )?></b> (version <?php echo esc_attr(LIVICONS_EVOLUTION_VERSION) ?>)
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						Recourses
					</th>
					<td>
						<p class="description">
							<a href="https://livicons.com" target="_blank">Demo website</a>
							<br>
							<a href="https://livicons.com/documentation-wordpress" target="_blank">Online Documentation</a>
							<br>
							<a href="http://codecanyon.net/item/livicons-evolution-for-wordpress-the-next-generation-of-the-truly-animated-vector-icons/16986405?ref=DeeThemes" target="_blank">Official sales page on CodeCanyon</a>
							<br>
							<a href="http://codecanyon.net/user/deethemes?ref=DeeThemes#contact" target="_blank">Support email form on CodeCanyon</a>
							<br>						
							<a href="http://codecanyon.net/user/deethemes/portfolio?ref=DeeThemes" target="_blank">All plugins and scripts by DeeThemes</a>
							<br>
							<a href="https://twitter.com/deethemes" target="_blank">Twitter account</a>
						</p>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						General Licenses
					</th>
					<td>
						<p class="description">
							<a href="http://codecanyon.net/licenses/terms/regular" target="_blank">CodeCanyon Regular license</a>
							<br>
							<a href="http://codecanyon.net/licenses/terms/extended" target="_blank">CodeCanyon Extended license</a>
						</p>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<?php
}


// Cause sanitize_hex_color() is in the class-wp-customize-manager.php, here is a custom function
function livicons_evolution_sanitize_hex_color( $color ) {
	if ( '' === $color )
		return '#000000'; //black by default
	// 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
		return $color;
	return '#000000'; //black by default
}