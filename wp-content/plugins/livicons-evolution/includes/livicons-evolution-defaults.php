<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

//factory defaults
function livicons_evolution_factory_defaults() {
	$factory_defaults = array(

		//// general settins ////
		'general' => array(
			//additional class to add to ALL LivIcons Evo
			'additional_class' => '',

			//allow LivIcons Evo in widgets
			'in_widgets' => 'true',

			//allow LivIcons Evo in comments
			'in_comments' => 'false',

			//allow LivIcons Evo in excerpts
			'in_excerpts' => 'false',

			//disable LivIcons animation in dialog box
			'disable_anim_in_dialog' => 'false',

			//use icon placeholder in dialog box
			'use_placeholder' => 'false',

			//reset options to all these defaults of this array
			'check_default_options_db' => 'false'
		),

		//// visualization options ////
		'visual' => array(
			//the default icon name
			'name' => 'bell',

			//'original', 'solid', 'filled', 'lines' or ('lines-alt' / 'linesAlt')
			'style' => 'original',

			//default size
			'size' => '60',
			'sizeUnits' => 'px', //'px' or '%'

			//'original', 'round' or 'square'
			'strokeStyle' => 'original',

			//'original' or any value in pixels
			'strokeWidth' => 'original',
			'customStrokeWidth' => '2',

			//apply or not micro shifts of SVG shapes to try to make them more sharp (crisp)
			'tryToSharpen' => 'true',

			//'none' or any value in deg [0 ... 360]
			'rotate' => 'none',
			'customRotate' => '0',

			'flipHorizontal' => 'false',

			'flipVertical' => 'false',

			//when style opt is 'filled' or 'lines' or ('lines-alt' / 'linesAlt')
			'strokeColor' => '#22A7F0',
			'strokeColorAction' => '#b3421b',

			//when style opt is ('lines-alt' / 'linesAlt')
			'strokeColorAlt' => '#F9B32F',
			'strokeColorAltAction' => '#ab69c6',

			//when style opt is 'filled'
			'fillColor' => '#91e9ff',
			'fillColorAction' => '#ff926b',

			//when style opt is 'solid'
			'solidColor' => '#6C7A89',
			'solidColorAction' => '#4C5A69',

			//when style opt is 'solid'
			'solidColorBg' => '#ffffff',
			'solidColorBgAction' => '#ffffff',

			//'none', 'lighter', 'darker', 'custom' or 'hue0' ... 'hue360'
			'colorsOnHover' => 'none',
			'colorsOnHoverHue' => '0',

			'colorsHoverTime' => 0.3, //seconds

			//'none', 'lighter', 'darker', 'custom' or 'hue0' ... 'hue360'
			'colorsWhenMorph' => 'none',
			'colorsWhenMorphHue' => '0',

			// brightness change when 'lighter' or 'darker' (10% by default)
			'brightness' => 0.10,

			// saturation change when 'lighter' or 'darker' (7% by default)
			'saturation' => 0.07,

			//'start' or 'end' (initial state for animation's position of morph icons)
			'morphState' => 'start',

			//'none' or a URL to an image (better to use an image with equal width and height)
			'morphImage' => 'none',
			'morphImageUrl' => '',

			//if true the inside image will rotate and/or flip with a morph icon together
			'allowMorphImageTransform' => 'false',

			//'none' or numeric value. For ex., for increase twice set the option to 2
			'strokeWidthFactorOnHover' => 'none',
			'strokeWidthFactorOnHoverValue' => 2,

			'strokeWidthOnHoverTime' => 0.3, //seconds

			//keep or not a stroke width when icon's size is changed via media queries for example
			'keepStrokeWidthOnResize' => 'false'
		),

		//// animation options ////
		'anim' => array(
			//if false, an icon is static
			'animated' => 'true',

			//'click', 'hover' or 'none'
			'eventType' => 'hover',

			//'self', 'parent', 'grandparent' or any ID (#some_id) or class (.some_class)
			'eventOn' => 'self',
			'eventOnElem' => '',

			//be careful with true value
			'autoPlay' => 'false',

			'delay' => 0, //seconds

			//'default' or value in seconds
			'duration' => 'default',
			'customDuration' => 0,

			//'default', 'loop' or integer number of repeats
			'repeat' => 'default',
			'customRepeat' => 0,

			//'default' or value in seconds
			'repeatDelay' => 'default',
			'customRepeatDelay' => 0,

			'drawOnViewport' => 'false',

			//'none', ('one-half' / 'oneHalf'), ('one-third' / 'oneThird') or 'full'
			'viewportShift' => 'oneHalf',

			'drawDelay' => 0, //seconds

			'drawTime' => 1, //seconds

			'drawStagger' => 0.1, //seconds

			//'start', 'middle' or 'end'
			'drawStartPoint' => 'middle',

			//'same' or any desired color value (HEX)
			'drawColor' => 'same',
			'customDrawColor' => '#000000',

			'drawColorTime' => 1, //seconds

			//true will reverse the order of shapes drawing
			'drawReversed' => 'false',

			//default ease for drawing
			'drawEase' => 'Power1.easeOut',

			'eraseDelay' => 0, //seconds

			'eraseTime' => 1, //seconds

			'eraseStagger' => 0.1, //seconds

			//'start', 'middle' or 'end'
			'eraseStartPoint' => 'middle',

			//true will reverse the order of shapes erasing
			'eraseReversed' => 'true',

			//default ease for erasing
			'eraseEase' => 'Power1.easeOut',

			//apply or not special events handlers for touch devices
			//the option is highly experimental and requires a carefully testing
			'touchEvents' => 'false'
		),

		//// activation data ////
		'activation' => array(
			'purchase_code' => '',
			'is_activated' => 'false',
		)
	);
	return $factory_defaults;
}
