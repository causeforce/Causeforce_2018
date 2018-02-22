<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
* Sanitize and validate inputs.
* Accepts an array, return a sanitized array.
*/

//general options
function livicons_evolution_validate_general_options( $input ) {
	// getting previously saved options
	$stored = get_option( 'livicons_evolution_general_options' );

	//sanitazing additional class name
	if ( isset( $input['additional_class'] ) ) {
		$input['additional_class'] = sanitize_html_class( $input['additional_class'] );
	} else {
		$input['additional_class'] = $stored['additional_class']; // Resets option to stored value
	};

	// validate input for "in_widgets" option (true or false)
	if ( isset( $input['in_widgets'] ) ) {
		if ( $input['in_widgets'] === 'true' ) {
			$input['in_widgets'] = 'true';
		} else {
			$input['in_widgets'] = 'false';
		}
	} else {
		$input['in_widgets'] = 'false';
	};

	// validate input for "in_comments" option (true or false)
	if ( isset( $input['in_comments'] ) ) {
		if ( $input['in_comments'] === 'true' ) {
			$input['in_comments'] = 'true';
		} else {
			$input['in_comments'] = 'false';
		}
	} else {
		$input['in_comments'] = 'false';
	};

	// validate input for "in_excerpts" option (true or false)
	if ( isset( $input['in_excerpts'] ) ) {
		if ( $input['in_excerpts'] === 'true' ) {
			$input['in_excerpts'] = 'true';
		} else {
			$input['in_excerpts'] = 'false';
		}
	} else {
		$input['in_excerpts'] = 'false';
	};

	// validate input for "disable_anim_in_dialog" option (true or false)
	if ( isset( $input['disable_anim_in_dialog'] ) ) {
		if ( $input['disable_anim_in_dialog'] === 'true' ) {
			$input['disable_anim_in_dialog'] = 'true';
		} else {
			$input['disable_anim_in_dialog'] = 'false';
		}
	} else {
		$input['disable_anim_in_dialog'] = 'false';
	};

	// validate input for "use_placeholder" option (true or false)
	if ( isset( $input['use_placeholder'] ) ) {
		if ( $input['use_placeholder'] === 'true' ) {
			$input['use_placeholder'] = 'true';
		} else {
			$input['use_placeholder'] = 'false';
		}
	} else {
		$input['use_placeholder'] = 'false';
	};

	// validate input for "check_default_options_db" option (true or false)
	if ( isset( $input['check_default_options_db'] ) ) {
		if ( $input['check_default_options_db'] === 'true' ) {
			$input['check_default_options_db'] = 'true';
		} else {
			$input['check_default_options_db'] = 'false';
		}
	} else {
		$input['check_default_options_db'] = 'false';
	};

	//write the result JavaScript file into the "wp-content/plugins/livicons-evolution/assets/js/" with validated options
	$add1 = get_option( 'livicons_evolution_visualization_options' );
	$add2 = get_option( 'livicons_evolution_animation_options' );
	$res = array_merge( $input, $add1, $add2 );
	livicons_evolution_save_result_file( $res );

	//return validated and sanitized array
	return $input;
}

//visualization options
function livicons_evolution_validate_visualization_options( $input ) {
	// getting previously saved options
	$stored = get_option( 'livicons_evolution_visualization_options' );

	//validate icon "name" option (is an icon's name in the global list)
	if ( isset( $input['name'] ) ) {
		
		//getting the full icons list
		$all_icons = livicons_evolution_icons_list();
		
		if ( ! _livicons_evolution_in_icons_list( $input['name'], $all_icons ) ) {
			$input['name'] = $stored['name']; // Resets option to stored value
			_livicons_evolution_settings_error( 'name', 'The icon Name is not in the LivIcons Evolution set. Please do not change elements on the page.' );
		}
	} else {
		$input['name'] = $stored['name']; // Resets option to stored value
	};

	//validate input for icon "style" option
	if ( isset( $input['style'] ) ) {
		//the array with allowed values
		$allowed = array('original', 'solid', 'filled', 'lines', 'lines-alt', 'linesAlt');
		if ( ! in_array( $input['style'], $allowed, true ) ) {
			$input['style'] = $stored['style']; // Resets option to stored value
			_livicons_evolution_settings_error( 'style', 'The value of icons Style did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['style'] = $stored['style']; // Resets option to stored value
	};

	// validate numeric icon "size" option
	if ( isset( $input['size'] ) ) {
		$opt = $input['size'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt > 0 ) {
				$input['size'] = $opt;
			} else {
				$input['size'] = $stored['size']; // Resets option to stored value
				_livicons_evolution_settings_error( 'size', 'The value of icons Size did not appear to be a valid. Please enter a valid numeric value.' );
			}
		} else {
			$input['size'] = $stored['size']; // Resets option to stored value
			_livicons_evolution_settings_error( 'size', 'The value of icons Size did not appear to be a valid. Please enter a valid numeric value.' );
		};
		unset( $opt );
	} else {
		$input['size'] = $stored['size']; // Resets option to stored value
	};

	//validate input for "sizeUnits" option
	if ( isset( $input['sizeUnits'] ) ) {
		//the array with allowed values
		$allowed = array('px', '%');
		if ( ! in_array( $input['sizeUnits'], $allowed, true ) ) {
			$input['sizeUnits'] = $stored['sizeUnits']; // Resets option to stored value
			_livicons_evolution_settings_error( 'sizeUnits', 'The chosen size\'s units did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['sizeUnits'] = $stored['sizeUnits']; // Resets option to stored value
	};

	//validate input for "strokeStyle" option
	if ( isset( $input['strokeStyle'] ) ) {
		//the array with allowed values
		$allowed = array('original', 'round', 'square');
		if ( ! in_array( $input['strokeStyle'], $allowed, true ) ) {
			$input['strokeStyle'] = $stored['strokeStyle']; // Resets option to stored value
			_livicons_evolution_settings_error( 'strokeStyle', 'The chosen Stroke Style did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['strokeStyle'] = $stored['strokeStyle']; // Resets option to stored value
	};

	//validate input for "strokeWidth" option
	if ( isset( $input['strokeWidth'] ) ) {
		//the array with allowed values
		$allowed = array('original', 'custom');
		if ( ! in_array( $input['strokeWidth'], $allowed, true ) ) {
			$input['strokeWidth'] = $stored['strokeWidth']; // Resets option to stored value
			_livicons_evolution_settings_error( 'strokeWidth', 'The chosen Stroke Width did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['strokeWidth'] = $stored['strokeWidth']; // Resets option to stored value
	};

	// validate custom input value for "customStrokeWidth" option
	if ( isset( $input['customStrokeWidth'] ) ) {
		$opt = $input['customStrokeWidth'];
		if ( is_numeric( $opt ) ) {
			$opt = intval( $opt );
			if ( $opt >= 1 ) {
				$input['customStrokeWidth'] = $opt;
			} else {
				$input['customStrokeWidth'] = $stored['customStrokeWidth']; // Resets option to stored value
				_livicons_evolution_settings_error( 'customStrokeWidth', 'The custom value of icons Stroke Width did not appear to be a valid. Please enter a valid integer numeric value.' );
			}
		} else {
			$input['customStrokeWidth'] = $stored['customStrokeWidth']; // Resets option to stored value
			_livicons_evolution_settings_error( 'customStrokeWidth', 'The custom value of icons Stroke Width did not appear to be a valid. Please enter a valid integer numeric value.' );
		};
		unset( $opt );
	} else {
		$input['customStrokeWidth'] = $stored['customStrokeWidth']; // Resets option to stored value
	};

	// validate input for "tryToSharpen" option (true or false)
	if ( isset( $input['tryToSharpen'] ) ) {
		if ( $input['tryToSharpen'] === 'true' ) {
			$input['tryToSharpen'] = 'true';
		} else {
			$input['tryToSharpen'] = 'false';
		}
	} else {
		$input['tryToSharpen'] = $stored['tryToSharpen'];
	};

	//validate input for "rotate" option
	if ( isset( $input['rotate'] ) ) {
		//the array with allowed values
		$allowed = array('none', 'custom');
		if ( ! in_array( $input['rotate'], $allowed, true ) ) {
			$input['rotate'] = $stored['rotate']; // Resets option to stored value
			_livicons_evolution_settings_error( 'rotate', 'The chosen Rotate option did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['rotate'] = $stored['rotate']; // Resets option to stored value
	};

	// validate custom input value for "customRotate" option
	if ( isset( $input['customRotate'] ) ) {
		$opt = $input['customRotate'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 && $opt <= 360 ) {
				$input['customRotate'] = $opt;
			} else {
				$input['customRotate'] = $stored['customRotate']; // Resets option to stored value
				_livicons_evolution_settings_error( 'customRotate', 'The custom value of icons rotation did not appear to be a valid. Please enter a valid numeric value.' );
			}
		} else {
			$input['customRotate'] = $stored['customRotate']; // Resets option to stored value
			_livicons_evolution_settings_error( 'customRotate', 'The custom value of icons rotation did not appear to be a valid. Please enter a valid numeric value.' );
		};
		unset( $opt );
	} else {
		$input['customRotate'] = $stored['customRotate']; // Resets option to stored value
	};

	// validate input for "flipHorizontal" option (true or false)
	if ( isset( $input['flipHorizontal'] ) ) {
		if ( $input['flipHorizontal'] === 'true' ) {
			$input['flipHorizontal'] = 'true';
		} else {
			$input['flipHorizontal'] = 'false';
		}
	} else {
		$input['flipHorizontal'] = $stored['flipHorizontal'];
	};

	// validate input for "flipVertical" option (true or false)
	if ( isset( $input['flipVertical'] ) ) {
		if ( $input['flipVertical'] === 'true' ) {
			$input['flipVertical'] = 'true';
		} else {
			$input['flipVertical'] = 'false';
		}
	} else {
		$input['flipVertical'] = $stored['flipVertical'];
	};

	// validate input value for "strokeColor" option
	if ( isset( $input['strokeColor'] ) ) {
		$opt = $input['strokeColor'];
		if ( ! _livicons_evolution_validate_hex_color( $opt ) ) {
			$input['strokeColor'] = $stored['strokeColor']; // Resets option to stored value
			_livicons_evolution_settings_error( 'strokeColor', 'The color HEX code for Stroke Color option did not appear to be a valid. Please enter a valid HEX value with leading "#".' );
		};
		unset( $opt );
	} else {
		$input['strokeColor'] = $stored['strokeColor']; // Resets option to stored value
	};
	// validate input value for "strokeColorAction" option
	if ( isset( $input['strokeColorAction'] ) ) {
		$opt = $input['strokeColorAction'];
		if ( ! _livicons_evolution_validate_hex_color( $opt ) ) {
			$input['strokeColorAction'] = $stored['strokeColorAction']; // Resets option to stored value
			_livicons_evolution_settings_error( 'strokeColorAction', 'The color HEX code for Stroke Color Action option did not appear to be a valid. Please enter a valid HEX value with leading "#".' );
		};
		unset( $opt );
	} else {
		$input['strokeColorAction'] = $stored['strokeColorAction']; // Resets option to stored value
	};

	// validate input value for "strokeColorAlt" option
	if ( isset( $input['strokeColorAlt'] ) ) {
		$opt = $input['strokeColorAlt'];
		if ( ! _livicons_evolution_validate_hex_color( $opt ) ) {
			$input['strokeColorAlt'] = $stored['strokeColorAlt']; // Resets option to stored value
			_livicons_evolution_settings_error( 'strokeColorAlt', 'The color HEX code for Stroke Color Alt option did not appear to be a valid. Please enter a valid HEX value with leading "#".' );
		};
		unset( $opt );
	} else {
		$input['strokeColorAlt'] = $stored['strokeColorAlt']; // Resets option to stored value
	};
	// validate input value for "strokeColorAltAction" option
	if ( isset( $input['strokeColorAltAction'] ) ) {
		$opt = $input['strokeColorAltAction'];
		if ( ! _livicons_evolution_validate_hex_color( $opt ) ) {
			$input['strokeColorAltAction'] = $stored['strokeColorAltAction']; // Resets option to stored value
			_livicons_evolution_settings_error( 'strokeColorAltAction', 'The color HEX code for Stroke Color Alt Action option did not appear to be a valid. Please enter a valid HEX value with leading "#".' );
		};
		unset( $opt );
	} else {
		$input['strokeColorAltAction'] = $stored['strokeColorAltAction']; // Resets option to stored value
	};

	// validate input value for "fillColor" option
	if ( isset( $input['fillColor'] ) ) {
		$opt = $input['fillColor'];
		if ( ! _livicons_evolution_validate_hex_color( $opt ) ) {
			$input['fillColor'] = $stored['fillColor']; // Resets option to stored value
			_livicons_evolution_settings_error( 'fillColor', 'The color HEX code for Fill Color option did not appear to be a valid. Please enter a valid HEX value with leading "#".' );
		};
		unset( $opt );
	} else {
		$input['fillColor'] = $stored['fillColor']; // Resets option to stored value
	};
	// validate input value for "fillColorAction" option
	if ( isset( $input['fillColorAction'] ) ) {
		$opt = $input['fillColorAction'];
		if ( ! _livicons_evolution_validate_hex_color( $opt ) ) {
			$input['fillColorAction'] = $stored['fillColorAction']; // Resets option to stored value
			_livicons_evolution_settings_error( 'fillColorAction', 'The color HEX code for Fill Color Action option did not appear to be a valid. Please enter a valid HEX value with leading "#".' );
		};
		unset( $opt );
	} else {
		$input['fillColorAction'] = $stored['fillColorAction']; // Resets option to stored value
	};

	// validate input value for "solidColor" option
	if ( isset( $input['solidColor'] ) ) {
		$opt = $input['solidColor'];
		if ( ! _livicons_evolution_validate_hex_color( $opt ) ) {
			$input['solidColor'] = $stored['solidColor']; // Resets option to stored value
			_livicons_evolution_settings_error( 'solidColor', 'The color HEX code for Solid Color option did not appear to be a valid. Please enter a valid HEX value with leading "#".' );
		};
		unset( $opt );
	} else {
		$input['solidColor'] = $stored['solidColor']; // Resets option to stored value
	};
	// validate input value for "solidColorAction" option
	if ( isset( $input['solidColorAction'] ) ) {
		$opt = $input['solidColorAction'];
		if ( ! _livicons_evolution_validate_hex_color( $opt ) ) {
			$input['solidColorAction'] = $stored['solidColorAction']; // Resets option to stored value
			_livicons_evolution_settings_error( 'solidColorAction', 'The color HEX code for Solid Color Action option did not appear to be a valid. Please enter a valid HEX value with leading "#".' );
		};
		unset( $opt );
	} else {
		$input['solidColorAction'] = $stored['solidColorAction']; // Resets option to stored value
	};

	// validate input value for "solidColorBg" option
	if ( isset( $input['solidColorBg'] ) ) {
		$opt = $input['solidColorBg'];
		if ( ! _livicons_evolution_validate_hex_color( $opt ) ) {
			$input['solidColorBg'] = $stored['solidColorBg']; // Resets option to stored value
			_livicons_evolution_settings_error( 'solidColorBg', 'The color HEX code for Background Solid Color option did not appear to be a valid. Please enter a valid HEX value with leading "#".' );
		};
		unset( $opt );
	} else {
		$input['solidColorBg'] = $stored['solidColorBg']; // Resets option to stored value
	};
	// validate input value for "solidColorBgAction" option
	if ( isset( $input['solidColorBgAction'] ) ) {
		$opt = $input['solidColorBgAction'];
		if ( ! _livicons_evolution_validate_hex_color( $opt ) ) {
			$input['solidColorBgAction'] = $stored['solidColorBgAction']; // Resets option to stored value
			_livicons_evolution_settings_error( 'solidColorBgAction', 'The color HEX code for Background Solid Color Action option did not appear to be a valid. Please enter a valid HEX value with leading "#".' );
		};
		unset( $opt );
	} else {
		$input['solidColorBgAction'] = $stored['solidColorBgAction']; // Resets option to stored value
	};

	//validate input for "colorsOnHover" option
	if ( isset( $input['colorsOnHover'] ) ) {
		//the array with allowed values
		$allowed = array( 'none', 'lighter', 'darker', 'custom', 'hue' );
		if ( ! in_array( $input['colorsOnHover'], $allowed, true ) ) {
			$input['colorsOnHover'] = $stored['colorsOnHover']; // Resets option to stored value
			_livicons_evolution_settings_error( 'colorsOnHover', 'The chosen Colors on Hover option did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['colorsOnHover'] = $stored['colorsOnHover']; // Resets option to stored value
	};

	// validate custom input value for "colorsOnHoverHue" option
	if ( isset( $input['colorsOnHoverHue'] ) ) {
		$opt = $input['colorsOnHoverHue'];
		if ( is_numeric( $opt ) ) {
			$opt = intval( $opt );
			if ( $opt >= 0 && $opt <= 360 ) {
				$input['colorsOnHoverHue'] = $opt;
			} else {
				$input['colorsOnHoverHue'] = $stored['colorsOnHoverHue']; // Resets option to stored value
				_livicons_evolution_settings_error( 'colorsOnHoverHue', 'The custom value of a color HUE rotation for Colors on Hover option did not appear to be a valid. Please enter a valid numeric integer value.' );
			}
		} else {
			$input['colorsOnHoverHue'] = $stored['colorsOnHoverHue']; // Resets option to stored value
			_livicons_evolution_settings_error( 'colorsOnHoverHue', 'The custom value of a color HUE rotation for Colors on Hover option did not appear to be a valid. Please enter a valid numeric integer value.' );
		};
		unset( $opt );
	} else {
		$input['colorsOnHoverHue'] = $stored['colorsOnHoverHue']; // Resets option to stored value
	};

	// validate input for numeric "colorsHoverTime" option
	if ( isset( $input['colorsHoverTime'] ) ) {
		$opt = $input['colorsHoverTime'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 ) {
				$input['colorsHoverTime'] = $opt;
			} else {
				$input['colorsHoverTime'] = $stored['colorsHoverTime']; // Resets option to stored value
				_livicons_evolution_settings_error( 'colorsHoverTime', 'The value of Colors Hover Time option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
			}
		} else {
			$input['colorsHoverTime'] = $stored['colorsHoverTime']; // Resets option to stored value
			_livicons_evolution_settings_error( 'colorsHoverTime', 'The value of Colors Hover Time option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
		};
		unset( $opt );
	} else {
		$input['colorsHoverTime'] = $stored['colorsHoverTime']; // Resets option to stored value
	};

	//validate input for "colorsWhenMorph" option
	if ( isset( $input['colorsWhenMorph'] ) ) {
		//the array with allowed values
		$allowed = array( 'none', 'lighter', 'darker', 'custom', 'hue' );
		if ( ! in_array( $input['colorsWhenMorph'], $allowed, true ) ) {
			$input['colorsWhenMorph'] = $stored['colorsWhenMorph']; // Resets option to stored value
			_livicons_evolution_settings_error( 'colorsWhenMorph', 'The chosen Colors when Morph option did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['colorsWhenMorph'] = $stored['colorsWhenMorph']; // Resets option to stored value
	};

	// validate custom input value for "colorsWhenMorphHue" option
	if ( isset( $input['colorsWhenMorphHue'] ) ) {
		$opt = $input['colorsWhenMorphHue'];
		if ( is_numeric( $opt ) ) {
			$opt = intval( $opt );
			if ( $opt >= 0 && $opt <= 360 ) {
				$input['colorsWhenMorphHue'] = $opt;
			} else {
				$input['colorsWhenMorphHue'] = $stored['colorsWhenMorphHue']; // Resets option to stored value
				_livicons_evolution_settings_error( 'colorsWhenMorphHue', 'The custom value of a color HUE rotation for Colors when Morph option did not appear to be a valid. Please enter a valid numeric integer value.' );
			}
		} else {
			$input['colorsWhenMorphHue'] = $stored['colorsWhenMorphHue']; // Resets option to stored value
			_livicons_evolution_settings_error( 'colorsWhenMorphHue', 'The custom value of a color HUE rotation for Colors when Morph option did not appear to be a valid. Please enter a valid numeric integer value.' );
		};
		unset( $opt );
	} else {
		$input['colorsWhenMorphHue'] = $stored['colorsWhenMorphHue']; // Resets option to stored value
	};

	// validate custom input value for "brightness" option
	if ( isset( $input['brightness'] ) ) {
		$opt = $input['brightness'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 && $opt <= 1 ) {
				$input['brightness'] = $opt;
			} else {
				$input['brightness'] = $stored['brightness']; // Resets option to stored value
				_livicons_evolution_settings_error( 'brightness', 'The Brightness option did not appear to be a valid. Please enter a valid numeric value from range [0 ... 1]' );
			}
		} else {
			$input['brightness'] = $stored['brightness']; // Resets option to stored value
			_livicons_evolution_settings_error( 'brightness', 'The Brightness option did not appear to be a valid. Please enter a valid numeric value from range [0 ... 1]' );
		};
		unset( $opt );
	} else {
		$input['brightness'] = $stored['brightness']; // Resets option to stored value
	};

	// validate custom input value for "saturation" option
	if ( isset( $input['saturation'] ) ) {
		$opt = $input['saturation'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 && $opt <= 1 ) {
				$input['saturation'] = $opt;
			} else {
				$input['saturation'] = $stored['saturation']; // Resets option to stored value
				_livicons_evolution_settings_error( 'saturation', 'The Saturation option did not appear to be a valid. Please enter a valid numeric value from range [0 ... 1]' );
			}
		} else {
			$input['saturation'] = $stored['saturation']; // Resets option to stored value
			_livicons_evolution_settings_error( 'saturation', 'The Saturation option did not appear to be a valid. Please enter a valid numeric value from range [0 ... 1]' );
		};
		unset( $opt );
	} else {
		$input['saturation'] = $stored['saturation']; // Resets option to stored value
	};

	//validate input for "morphState" option
	if ( isset( $input['morphState'] ) ) {
		//the array with allowed values
		$allowed = array('start', 'end');
		if ( ! in_array( $input['morphState'], $allowed, true ) ) {
			$input['morphState'] = $stored['morphState']; // Resets option to stored value
			_livicons_evolution_settings_error( 'morphState', 'The chosen Morph State option did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['morphState'] = $stored['morphState']; // Resets option to stored value
	};

	//validate input for "morphImage" option
	if ( isset( $input['morphImage'] ) ) {
		//the array with allowed values
		$allowed = array('none', 'url');
		if ( ! in_array( $input['morphImage'], $allowed, true ) ) {
			$input['morphImage'] = $stored['morphImage']; // Resets option to stored value
			_livicons_evolution_settings_error( 'morphImage', 'The chosen Morph Image option did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['morphImage'] = $stored['morphImage']; // Resets option to stored value
	};

	//validate input for "morphImageUrl" option
	if ( isset( $input['morphImageUrl'] ) ) {
		//escaping for writing in a database
		$input['morphImageUrl'] = esc_url_raw($input['morphImageUrl']);
	} else {
		$input['morphImageUrl'] = $stored['morphImageUrl']; // Resets option to stored value
	};

	// validate input for "allowMorphImageTransform" option (true or false)
	if ( isset( $input['allowMorphImageTransform'] ) ) {
		if ( $input['allowMorphImageTransform'] === 'true' ) {
			$input['allowMorphImageTransform'] = 'true';
		} else {
			$input['allowMorphImageTransform'] = 'false';
		}
	} else {
		$input['allowMorphImageTransform'] = $stored['allowMorphImageTransform'];
	};

	//validate input for "strokeWidthFactorOnHover" option
	if ( isset( $input['strokeWidthFactorOnHover'] ) ) {
		//the array with allowed values
		$allowed = array( 'none', 'custom' );
		if ( ! in_array( $input['strokeWidthFactorOnHover'], $allowed, true ) ) {
			$input['strokeWidthFactorOnHover'] = $stored['strokeWidthFactorOnHover']; // Resets option to stored value
			_livicons_evolution_settings_error( 'strokeWidthFactorOnHover', 'The chosen Stroke Width Factor On Hover option did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['strokeWidthFactorOnHover'] = $stored['strokeWidthFactorOnHover']; // Resets option to stored value
	};

	// validate custom input value for "strokeWidthFactorOnHoverValue" option
	if ( isset( $input['strokeWidthFactorOnHoverValue'] ) ) {
		$opt = $input['strokeWidthFactorOnHoverValue'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 ) {
				$input['strokeWidthFactorOnHoverValue'] = $opt;
			} else {
				$input['strokeWidthFactorOnHoverValue'] = $stored['strokeWidthFactorOnHoverValue']; // Resets option to stored value
				_livicons_evolution_settings_error( 'strokeWidthFactorOnHoverValue', 'The custom value of Stroke Width Factor On Hover option did not appear to be a valid. Please enter a valid numeric value.' );
			}
		} else {
			$input['strokeWidthFactorOnHoverValue'] = $stored['strokeWidthFactorOnHoverValue']; // Resets option to stored value
			_livicons_evolution_settings_error( 'strokeWidthFactorOnHoverValue', 'The custom value of Stroke Width Factor On Hover option did not appear to be a valid. Please enter a valid numeric value.' );
		};
		unset( $opt );
	} else {
		$input['strokeWidthFactorOnHoverValue'] = $stored['strokeWidthFactorOnHoverValue']; // Resets option to stored value
	};

	// validate input for numeric "strokeWidthOnHoverTime" option
	if ( isset( $input['strokeWidthOnHoverTime'] ) ) {
		$opt = $input['strokeWidthOnHoverTime'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 ) {
				$input['strokeWidthOnHoverTime'] = $opt;
			} else {
				$input['strokeWidthOnHoverTime'] = $stored['strokeWidthOnHoverTime']; // Resets option to stored value
				_livicons_evolution_settings_error( 'strokeWidthOnHoverTime', 'The value of Stroke Width On Hover Time option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
			}
		} else {
			$input['strokeWidthOnHoverTime'] = $stored['strokeWidthOnHoverTime']; // Resets option to stored value
			_livicons_evolution_settings_error( 'strokeWidthOnHoverTime', 'The value of Stroke Width On Hover Time option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
		};
		unset( $opt );
	} else {
		$input['strokeWidthOnHoverTime'] = $stored['strokeWidthOnHoverTime']; // Resets option to stored value
	};

	// validate input for "keepStrokeWidthOnResize" option (true or false)
	if ( isset( $input['keepStrokeWidthOnResize'] ) ) {
		if ( $input['keepStrokeWidthOnResize'] === 'true' ) {
			$input['keepStrokeWidthOnResize'] = 'true';
		} else {
			$input['keepStrokeWidthOnResize'] = 'false';
		}
	} else {
		$input['keepStrokeWidthOnResize'] = $stored['keepStrokeWidthOnResize'];
	};

	//write the result JavaScript file into the "wp-content/plugins/livicons-evolution/assets/js/" with validated options
	$add1 = get_option( 'livicons_evolution_general_options' );
	$add2 = get_option( 'livicons_evolution_animation_options' );
	$res = array_merge( $input, $add1, $add2 );
	livicons_evolution_save_result_file( $res );

	//return validated and sanitized array
	return $input;
}

//animation options
function livicons_evolution_validate_animation_options( $input ) {
	// getting previously saved options
	$stored = get_option( 'livicons_evolution_animation_options' );

	// validate input for "animated" option (true or false)
	if ( isset( $input['animated'] ) ) {
		if ( $input['animated'] === 'true' ) {
			$input['animated'] = 'true';
		} else {
			$input['animated'] = 'false';
		}
	} else {
		$input['animated'] = $stored['animated'];
	};

	//validate input for icon "eventType" option
	if ( isset( $input['eventType'] ) ) {
		//the array with allowed values
		$allowed = array( 'none', 'hover', 'click' );
		if ( ! in_array( $input['eventType'], $allowed, true ) ) {
			$input['eventType'] = $stored['eventType']; // Resets option to stored value
			_livicons_evolution_settings_error( 'eventType', 'The value of Event Type option did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['eventType'] = $stored['eventType']; // Resets option to stored value
	};

	//validate input for icon "eventOn" option
	if ( isset( $input['eventOn'] ) ) {
		//the array with allowed values
		$allowed = array( 'self', 'parent', 'grandparent', 'custom' );
		if ( ! in_array( $input['eventOn'], $allowed, true ) ) {
			$input['eventOn'] = $stored['eventOn']; // Resets option to stored value
			_livicons_evolution_settings_error( 'eventOn', 'The value of Event On option did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['eventOn'] = $stored['eventOn']; // Resets option to stored value
	};

	// 2 steps validation of a string with comma separated CSS selectors
	if ( isset( $input['eventOnElem'] ) ) {
		$input['eventOnElem'] = _livicons_evolution_sanitize_css_selectors( sanitize_text_field( $input['eventOnElem'] ) );
	} else {
		$input['eventOnElem'] = $stored['eventOnElem']; // Resets option to stored value
	};

	// validate input for "autoPlay" option (true or false)
	if ( isset( $input['autoPlay'] ) ) {
		if ( $input['autoPlay'] === 'true' ) {
			$input['autoPlay'] = 'true';
		} else {
			$input['autoPlay'] = 'false';
		}
	} else {
		$input['autoPlay'] = $stored['autoPlay'];
	};

	// validate input for numeric "delay" option
	if ( isset( $input['delay'] ) ) {
		$opt = $input['delay'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 ) {
				$input['delay'] = $opt;
			} else {
				$input['delay'] = $stored['delay']; // Resets option to stored value
				_livicons_evolution_settings_error( 'delay', 'The value of Delay option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
			}
		} else {
			$input['delay'] = $stored['delay']; // Resets option to stored value
			_livicons_evolution_settings_error( 'delay', 'The value of Delay option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
		};
		unset( $opt );
	} else {
		$input['delay'] = $stored['delay']; // Resets option to stored value
	};

	// validate input for "drawOnViewport" option (true or false)
	if ( isset( $input['drawOnViewport'] ) ) {
		if ( $input['drawOnViewport'] === 'true' ) {
			$input['drawOnViewport'] = 'true';
		} else {
			$input['drawOnViewport'] = 'false';
		}
	} else {
		$input['drawOnViewport'] = $stored['drawOnViewport'];
	};

	//validate input for "viewportShift" option
	if ( isset( $input['viewportShift'] ) ) {
		//the array with allowed values
		$allowed = array( 'none', 'oneHalf', 'oneThird', 'full' );
		if ( ! in_array( $input['viewportShift'], $allowed, true ) ) {
			$input['viewportShift'] = $stored['viewportShift']; // Resets option to stored value
			_livicons_evolution_settings_error( 'viewportShift', 'The chosen Viewport Shift option did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['viewportShift'] = $stored['viewportShift']; // Resets option to stored value
	};

	// validate input for numeric "drawDelay" option
	if ( isset( $input['drawDelay'] ) ) {
		$opt = $input['drawDelay'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 ) {
				$input['drawDelay'] = $opt;
			} else {
				$input['drawDelay'] = $stored['drawDelay']; // Resets option to stored value
				_livicons_evolution_settings_error( 'drawDelay', 'The value of Draw Delay option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
			}
		} else {
			$input['drawDelay'] = $stored['drawDelay']; // Resets option to stored value
			_livicons_evolution_settings_error( 'drawDelay', 'The value of Draw Delay option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
		};
		unset( $opt );
	} else {
		$input['drawDelay'] = $stored['drawDelay']; // Resets option to stored value
	};

	// validate input for numeric "drawTime" option
	if ( isset( $input['drawTime'] ) ) {
		$opt = $input['drawTime'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 ) {
				$input['drawTime'] = $opt;
			} else {
				$input['drawTime'] = $stored['drawTime']; // Resets option to stored value
				_livicons_evolution_settings_error( 'drawTime', 'The value of Draw Time option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
			}
		} else {
			$input['drawTime'] = $stored['drawTime']; // Resets option to stored value
			_livicons_evolution_settings_error( 'drawTime', 'The value of Draw Time option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
		};
		unset( $opt );
	} else {
		$input['drawTime'] = $stored['drawTime']; // Resets option to stored value
	};

	// validate input for numeric "drawStagger" option
	if ( isset( $input['drawStagger'] ) ) {
		$opt = $input['drawStagger'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 ) {
				$input['drawStagger'] = $opt;
			} else {
				$input['drawStagger'] = $stored['drawStagger']; // Resets option to stored value
				_livicons_evolution_settings_error( 'drawStagger', 'The value of Draw Stagger option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
			}
		} else {
			$input['drawStagger'] = $stored['drawStagger']; // Resets option to stored value
			_livicons_evolution_settings_error( 'drawStagger', 'The value of Draw Stagger option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
		};
		unset( $opt );
	} else {
		$input['drawStagger'] = $stored['drawStagger']; // Resets option to stored value
	};

	//validate input for "drawStartPoint" option
	if ( isset( $input['drawStartPoint'] ) ) {
		//the array with allowed values
		$allowed = array( 'start', 'middle', 'end' );
		if ( ! in_array( $input['drawStartPoint'], $allowed, true ) ) {
			$input['drawStartPoint'] = $stored['drawStartPoint']; // Resets option to stored value
			_livicons_evolution_settings_error( 'drawStartPoint', 'The chosen Draw Start Point option did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['drawStartPoint'] = $stored['drawStartPoint']; // Resets option to stored value
	};

	//validate input for "drawColor" option
	if ( isset( $input['drawColor'] ) ) {
		//the array with allowed values
		$allowed = array( 'same', 'custom' );
		if ( ! in_array( $input['drawColor'], $allowed, true ) ) {
			$input['drawColor'] = $stored['drawColor']; // Resets option to stored value
			_livicons_evolution_settings_error( 'drawColor', 'The chosen Draw Color option did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['drawColor'] = $stored['drawColor']; // Resets option to stored value
	};

	// validate input value for "customDrawColor" option
	if ( isset( $input['customDrawColor'] ) ) {
		$opt = $input['customDrawColor'];
		if ( ! _livicons_evolution_validate_hex_color( $opt ) ) {
			$input['customDrawColor'] = $stored['customDrawColor']; // Resets option to stored value
			_livicons_evolution_settings_error( 'customDrawColor', 'The custom color HEX code for Draw Color option did not appear to be a valid. Please enter a valid HEX value with leading "#".' );
		};
		unset( $opt );
	} else {
		$input['customDrawColor'] = $stored['customDrawColor']; // Resets option to stored value
	};

	// validate input for numeric "drawColorTime" option
	if ( isset( $input['drawColorTime'] ) ) {
		$opt = $input['drawColorTime'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 ) {
				$input['drawColorTime'] = $opt;
			} else {
				$input['drawColorTime'] = $stored['drawColorTime']; // Resets option to stored value
				_livicons_evolution_settings_error( 'drawColorTime', 'The value of Draw Color Time option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
			}
		} else {
			$input['drawColorTime'] = $stored['drawColorTime']; // Resets option to stored value
			_livicons_evolution_settings_error( 'drawColorTime', 'The value of Draw Color Time option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
		};
		unset( $opt );
	} else {
		$input['drawColorTime'] = $stored['drawColorTime']; // Resets option to stored value
	};

	// validate input for "drawReversed" option (true or false)
	if ( isset( $input['drawReversed'] ) ) {
		if ( $input['drawReversed'] === 'true' ) {
			$input['drawReversed'] = 'true';
		} else {
			$input['drawReversed'] = 'false';
		}
	} else {
		$input['drawReversed'] = $stored['drawReversed'];
	};

	// Sanitize input for "drawEase" option
	if ( isset( $input['drawEase'] ) ) {
		$input['drawEase'] = sanitize_text_field( $input['drawEase'] );
	} else {
		$input['drawEase'] = $stored['drawEase'];
	};

	// validate input for numeric "eraseDelay" option
	if ( isset( $input['eraseDelay'] ) ) {
		$opt = $input['eraseDelay'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 ) {
				$input['eraseDelay'] = $opt;
			} else {
				$input['eraseDelay'] = $stored['eraseDelay']; // Resets option to stored value
				_livicons_evolution_settings_error( 'eraseDelay', 'The value of Erase Delay option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
			}
		} else {
			$input['eraseDelay'] = $stored['eraseDelay']; // Resets option to stored value
			_livicons_evolution_settings_error( 'eraseDelay', 'The value of Erase Delay option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
		};
		unset( $opt );
	} else {
		$input['eraseDelay'] = $stored['eraseDelay']; // Resets option to stored value
	};

	// validate input for numeric "eraseTime" option
	if ( isset( $input['eraseTime'] ) ) {
		$opt = $input['eraseTime'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 ) {
				$input['eraseTime'] = $opt;
			} else {
				$input['eraseTime'] = $stored['eraseTime']; // Resets option to stored value
				_livicons_evolution_settings_error( 'eraseTime', 'The value of Erase Time option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
			}
		} else {
			$input['eraseTime'] = $stored['eraseTime']; // Resets option to stored value
			_livicons_evolution_settings_error( 'eraseTime', 'The value of Erase Time option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
		};
		unset( $opt );
	} else {
		$input['eraseTime'] = $stored['eraseTime']; // Resets option to stored value
	};

	// validate input for numeric "eraseStagger" option
	if ( isset( $input['eraseStagger'] ) ) {
		$opt = $input['eraseStagger'];
		if ( is_numeric( $opt ) ) {
			$opt = floatval( $opt );
			if ( $opt >= 0 ) {
				$input['eraseStagger'] = $opt;
			} else {
				$input['eraseStagger'] = $stored['eraseStagger']; // Resets option to stored value
				_livicons_evolution_settings_error( 'eraseStagger', 'The value of Erase Stagger option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
			}
		} else {
			$input['eraseStagger'] = $stored['eraseStagger']; // Resets option to stored value
			_livicons_evolution_settings_error( 'eraseStagger', 'The value of Erase Stagger option did not appear to be a valid. Please enter a valid numeric value for seconds.' );
		};
		unset( $opt );
	} else {
		$input['eraseStagger'] = $stored['eraseStagger']; // Resets option to stored value
	};

	//validate input for "eraseStartPoint" option
	if ( isset( $input['eraseStartPoint'] ) ) {
		//the array with allowed values
		$allowed = array( 'start', 'middle', 'end' );
		if ( ! in_array( $input['eraseStartPoint'], $allowed, true ) ) {
			$input['eraseStartPoint'] = $stored['eraseStartPoint']; // Resets option to stored value
			_livicons_evolution_settings_error( 'eraseStartPoint', 'The chosen Erase Start Point option did not appear to be a valid. Please do not change elements on the page.' );
		};
		unset( $allowed );
	} else {
		$input['eraseStartPoint'] = $stored['eraseStartPoint']; // Resets option to stored value
	};

	// validate input for "eraseReversed" option (true or false)
	if ( isset( $input['eraseReversed'] ) ) {
		if ( $input['eraseReversed'] === 'true' ) {
			$input['eraseReversed'] = 'true';
		} else {
			$input['eraseReversed'] = 'false';
		}
	} else {
		$input['eraseReversed'] = $stored['eraseReversed'];
	};

	// Sanitize input for "eraseEase" option
	if ( isset( $input['eraseEase'] ) ) {
		$input['eraseEase'] = sanitize_text_field( $input['eraseEase'] );
	} else {
		$input['eraseEase'] = $stored['eraseEase'];
	};

	// validate input for "touchEvents" option (true or false)
	if ( isset( $input['touchEvents'] ) ) {
		if ( $input['touchEvents'] === 'true' ) {
			$input['touchEvents'] = 'true';
		} else {
			$input['touchEvents'] = 'false';
		}
	} else {
		$input['touchEvents'] = $stored['touchEvents'];
	};

	//write the result JavaScript file into the "wp-content/plugins/livicons-evolution/assets/js/" with validated options
	$add1 = get_option( 'livicons_evolution_visualization_options' );
	$add2 = get_option( 'livicons_evolution_general_options' );
	$res = array_merge( $input, $add1, $add2 );
	livicons_evolution_save_result_file( $res );

	//return validated and sanitized array
	return $input;
}

//activation validation and checking
function livicons_evolution_validate_activation( $input ) {
	// getting previously saved options
	$stored = get_option( 'livicons_evolution_activation' );

	if ( isset( $input['is_activated'] ) ) {
		if ( $input['is_activated'] !== 'true' ) {
			$input['is_activated'] = 'false';
		}
	} else {
		$input['is_activated'] = 'false';
	};

	if ( $input['is_activated'] !== 'true') { //activation
		if ( isset( $input['purchase_code'] ) ) {
			$input['purchase_code'] = sanitize_key( $input['purchase_code'] );
			$params = array(
				'timeout' => 30,
				'body' => array(
					'action' => 'activate_code',
					'purchase_code'  => $input['purchase_code']
				),
			);
			$request = wp_remote_post( LIVICONS_EVOLUTION_UPDATE_URL, $params );
			
			// Check if code is valid
			if ( ! is_wp_error( $request ) && wp_remote_retrieve_response_code( $request ) === 200 ) {
				$response_body = wp_remote_retrieve_body( $request );
				if ( $response_body === 'valid' ) {
					$input['is_activated'] = 'true';
					if ( function_exists( 'add_settings_error' ) ) {
						add_settings_error( 'purchase_code', 'valid_purchase_code', 'Your code is valid. Thank you for the activation.', 'updated' );
					};
					//reset site transient for update_plugins to refresh update info
					set_site_transient( 'update_plugins', null );
				} elseif ($response_body === 'no_envato_response') {
					_livicons_evolution_settings_error( 'purchase_code', 'There is no response from the Envato API site: https://api.envato.com | Please try later.' );
					$input['purchase_code'] = $stored['purchase_code']; // Resets option to stored value
				} else {
					_livicons_evolution_settings_error( 'purchase_code', 'Entered purchase code is not valid. Please be sure that you have entered exactly a LivIcons Evolution product\'s code and haven\'t made any typo.' );
					$input['purchase_code'] = $stored['purchase_code']; // Resets option to stored value
				}
			} elseif ( is_wp_error( $request ) ) {
				_livicons_evolution_settings_error( 'purchase_code', 'An unexpected error has occurred. The error message: "' . $request->get_error_message() . '"' );
				$input['purchase_code'] = $stored['purchase_code']; // Resets option to stored value
			} elseif ( wp_remote_retrieve_response_code( $request ) !== 200 ) {
				_livicons_evolution_settings_error( 'purchase_code', 'No response from the https://livicons.com | Please try later.' );
				$input['purchase_code'] = $stored['purchase_code']; // Resets option to stored value
			} else {
				_livicons_evolution_settings_error( 'purchase_code', 'An unknown error has occurred.  Automatic updates are unavailable for this plugin. Please try later or use a manual update.' );
				$input['purchase_code'] = $stored['purchase_code']; // Resets option to stored value
			}
		} else {
			$input['purchase_code'] = $stored['purchase_code']; // Resets option to stored value
		};
	} else { //deactivation
		$input['purchase_code'] = '';
		$input['is_activated'] = 'false';
		if ( function_exists( 'add_settings_error' ) ) {
			add_settings_error( 'purchase_code', 'deacivate_purchase_code', 'The product has been deactivated. By now you may use your purchase code on another WordPress Installation.', 'updated' );
		};
	}
	
	return $input;
}

//Sanitizes a comma separated CSS selectors with class and ID names to ensure it only contains valid characters.
//Complex selectors (for ex. [name*="value"]) are not allowed.
//Allowed characters: A-Z, a-z, 0-9, _, -, .(dot), >,  (space), #, ,(comma)
function _livicons_evolution_sanitize_css_selectors( $selectors ) {
	$selectors = htmlspecialchars_decode( $selectors );
	//Strip out any % encoded octets
	$sanitized = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $selectors );
	//Limit to A-Z, a-z, 0-9, _, -, .(dot), >,  (space), #, ,(comma)
	$sanitized = preg_replace( '/[^A-Za-z0-9 _,.#>-]/', '', $sanitized );
	//convert the ">" (greater-than) sign to &gt; for storing in a database
	$sanitized = htmlspecialchars( $sanitized );
	return apply_filters( '_livicons_evolution_sanitize_css_selectors', $sanitized );
}

//checks for item in array
function _livicons_evolution_in_icons_list( $item , $array ){
	$str = json_encode( $array );
	$str = str_replace( 'NEW_', '', $str );
    return preg_match( '/"'. $item .'"/i' , $str );
}

//checks hex color format
function _livicons_evolution_validate_hex_color( $color ) {
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
		return true;
	} else {
		return false;
	}
}

//adding settings error for admin notice
function _livicons_evolution_settings_error ( $setting, $msg ) {
	if ( function_exists( 'add_settings_error' ) ) {
		add_settings_error( $setting, 'invalid_' . $setting, $msg );
	};
}
