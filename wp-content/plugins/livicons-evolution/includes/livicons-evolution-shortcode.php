<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

//Shortcode function itself [livicon_evo ...params...]content( if any)[/livicon_evo]
function livicons_evolution_main_shortcode( $atts = null, $content = null ) {
	extract( shortcode_atts(
		array(
			'add_id' => null,
			'add_class' => null,
			'add_css' => null,
			'link' => null,
			'target' => null,
			'name' => null,
			'style' => null,
			'size' => null,
			'stroke_style' => null,
			'stroke_width' => null,
			'try_to_sharpen' => null,
			'rotate' => null,
			'flip_horizontal' => null,
			'flip_vertical' => null,
			'stroke_color' => null,
			'stroke_color_action' => null,
			'stroke_color_alt' => null,
			'stroke_color_alt_action' => null,
			'fill_color' => null,
			'fill_color_action' => null,
			'solid_color' => null,
			'solid_color_action' => null,
			'solid_color_bg' => null,
			'solid_color_bg_action' => null,
			'colors_on_hover' => null,
			'colors_hover_time' => null,
			'colors_when_morph' => null,
			'brightness' => null,
			'saturation' => null,
			'morph_state' => null,
			'morph_image' => null,
			'allow_morph_image_transform' => null,
			'stroke_width_factor_on_hover' => null,
			'stroke_width_on_hover_time' => null,
			'keep_stroke_width_on_resize' => null,
			'animated' => null,
			'event_type' => null,
			'event_on' => null,
			'auto_play' => null,
			'delay' => null,
			'duration' => null,
			'repeat' => null,
			'repeat_delay' => null,
			'draw_on_viewport' => null,
			'viewport_shift' => null,
			'draw_delay' => null,
			'draw_time' => null,
			'draw_stagger' => null,
			'draw_start_point' => null,
			'draw_color' => null,
			'draw_color_time' => null,
			'draw_reversed' => null,
			'draw_ease' => null,
			'erase_delay' => null,
			'erase_time' => null,
			'erase_stagger' => null,
			'erase_start_point' => null,
			'erase_reversed' => null,
			'erase_ease' => null,
			'touch_events' => null
		), $atts )
	);

	//remove all trailing spaces, tabs, returns, etc.
	$content = trim( $content, " \t\n\r\0\x0B\xC2\xA0" );
	
	$res = '<span';
	if ( ! is_null($add_id) ) { $res .= ' id="' . $add_id . '"'; };
	$res .= ' class="livicon-evo';
	if ( ! is_null($add_class) ) { $res .= ' ' . $add_class; };
	if ( ! is_null($content) && $content !=='' ) { $res .= ' livicon-evo-back-in-combined'; };
	$res .= '"';
	if ( ! is_null($add_css) ) { $res .= ' style="' . $add_css . '"'; };
	$res .= ' data-options="';
	if ( ! is_null($name) ) { $res .= 'name:' . $name; };
	if ( ! is_null($style) ) { $res .= '; style:' . $style; };
	if ( ! is_null($size) ) { $res .= '; size:' . $size; };
	if ( ! is_null($stroke_style) ) { $res .= '; strokeStyle:' . $stroke_style; };
	if ( ! is_null($stroke_width) ) { $res .= '; strokeWidth:' . $stroke_width; };
	if ( ! is_null($try_to_sharpen) ) { $res .= '; tryToSharpen:' . $try_to_sharpen; };
	if ( ! is_null($rotate) ) { $res .= '; rotate:' . $rotate; };
	if ( ! is_null($flip_horizontal) ) { $res .= '; flipHorizontal:' . $flip_horizontal; };
	if ( ! is_null($flip_vertical) ) { $res .= '; flipVertical:' . $flip_vertical; };
	if ( ! is_null($stroke_color) ) { $res .= '; strokeColor:' . $stroke_color; };
	if ( ! is_null($stroke_color_action) ) { $res .= '; strokeColorAction:' . $stroke_color_action; };
	if ( ! is_null($stroke_color_alt) ) { $res .= '; strokeColorAlt:' . $stroke_color_alt; };
	if ( ! is_null($stroke_color_alt_action) ) { $res .= '; strokeColorAltAction:' . $stroke_color_alt_action; };
	if ( ! is_null($fill_color) ) { $res .= '; fillColor:' . $fill_color; };
	if ( ! is_null($fill_color_action) ) { $res .= '; fillColorAction:' . $fill_color_action; };
	if ( ! is_null($solid_color) ) { $res .= '; solidColor:' . $solid_color; };
	if ( ! is_null($solid_color_action) ) { $res .= '; solidColorAction:' . $solid_color_action; };
	if ( ! is_null($solid_color_bg) ) { $res .= '; solidColorBg:' . $solid_color_bg; };
	if ( ! is_null($solid_color_bg_action) ) { $res .= '; solidColorBgAction:' . $solid_color_bg_action; };
	if ( ! is_null($colors_on_hover) ) { $res .= '; colorsOnHover:' . $colors_on_hover; };
	if ( ! is_null($colors_hover_time) ) { $res .= '; colorsHoverTime:' . $colors_hover_time; };
	if ( ! is_null($colors_when_morph) ) { $res .= '; colorsWhenMorph:' . $colors_when_morph; };
	if ( ! is_null($brightness) ) { $res .= '; brightness:' . $brightness; };
	if ( ! is_null($saturation) ) { $res .= '; saturation:' . $saturation; };
	if ( ! is_null($morph_state) ) { $res .= '; morphState:' . $morph_state; };
	if ( ! is_null($morph_image) ) { $res .= '; morphImage:' . $morph_image; };
	if ( ! is_null($allow_morph_image_transform) ) { $res .= '; allowMorphImageTransform:' . $allow_morph_image_transform; };
	if ( ! is_null($stroke_width_factor_on_hover) ) { $res .= '; strokeWidthFactorOnHover:' . $stroke_width_factor_on_hover; };
	if ( ! is_null($stroke_width_on_hover_time) ) { $res .= '; strokeWidthOnHoverTime:' . $stroke_width_on_hover_time; };
	if ( ! is_null($keep_stroke_width_on_resize) ) { $res .= '; keepStrokeWidthOnResize:' . $keep_stroke_width_on_resize; };
	if ( ! is_null($animated) ) { $res .= '; animated:' . $animated; };
	if ( ! is_null($event_type) ) { $res .= '; eventType:' . $event_type; };
	if ( ! is_null($event_on) ) { $res .= '; eventOn:' . $event_on; };
	if ( ! is_null($auto_play) ) { $res .= '; autoPlay:' . $auto_play; };
	if ( ! is_null($delay) ) { $res .= '; delay:' . $delay; };
	if ( ! is_null($duration) ) { $res .= '; duration:' . $duration; };
	if ( ! is_null($repeat) ) { $res .= '; repeat:' . $repeat; };
	if ( ! is_null($repeat_delay) ) { $res .= '; repeatDelay:' . $repeat_delay; };
	if ( ! is_null($draw_on_viewport) ) { $res .= '; drawOnViewport:' . $draw_on_viewport; };
	if ( ! is_null($viewport_shift) ) { $res .= '; viewportShift:' . $viewport_shift; };
	if ( ! is_null($draw_delay) ) { $res .= '; drawDelay:' . $draw_delay; };
	if ( ! is_null($draw_time) ) { $res .= '; drawTime:' . $draw_time; };
	if ( ! is_null($draw_stagger) ) { $res .= '; drawStagger:' . $draw_stagger; };
	if ( ! is_null($draw_start_point) ) { $res .= '; drawStartPoint:' . $draw_start_point; };
	if ( ! is_null($draw_color) ) { $res .= '; drawColor:' . $draw_color; };
	if ( ! is_null($draw_color_time) ) { $res .= '; drawColorTime:' . $draw_color_time; };
	if ( ! is_null($draw_reversed) ) { $res .= '; drawReversed:' . $draw_reversed; };
	if ( ! is_null($draw_ease) ) { $res .= '; drawEase:' . $draw_ease; };
	if ( ! is_null($erase_delay) ) { $res .= '; eraseDelay:' . $erase_delay; };
	if ( ! is_null($erase_time) ) { $res .= '; eraseTime:' . $erase_time; };
	if ( ! is_null($erase_stagger) ) { $res .= '; eraseStagger:' . $erase_stagger; };
	if ( ! is_null($erase_start_point) ) { $res .= '; eraseStartPoint:' . $erase_start_point; };
	if ( ! is_null($erase_reversed) ) { $res .= '; eraseReversed:' . $erase_reversed; };
	if ( ! is_null($erase_ease) ) { $res .= '; eraseEase:' . $erase_ease; };
	if ( ! is_null($touch_events) ) { $res .= '; touchEvents:' . $touch_events; };
	$res .= '">';
	$res .= '</span>';

	if ( ! is_null($content) && $content !=='' ) {
		$content = '<span class="livicon-evo-front-in-combined">' . $content . '</span>';
		$res .= do_shortcode( $content );
		$output = '<span class="livicon-evo-combined">' . $res . '</span>';
	} else {
		$output = $res;
	};

	if ( ! is_null($link) ) {
		if ( $target === "blank" ) { 
			$target = ' target="_blank"';
		} else {
			$target = '';
		}
		return '<a class="livicon-evo-link" href="' . $link . '"' . $target . '>' . $output . '</a>';
	} else {
		return $output;
	};
}
add_shortcode( 'livicon_evo', 'livicons_evolution_main_shortcode' );

//for visual composer
function livicons_evolution_shortcode_for_vc( $atts = null, $content = null ) {
	//remove all trailing spaces, tabs, returns, etc.
	$content = trim( $content, " \t\n\r\0\x0B\xC2\xA0" );
	if ( ! is_null($content) && $content !== '' ) {
		return do_shortcode( $content );
	};
	return '';
}
add_shortcode( 'livicon_evo_vc', 'livicons_evolution_shortcode_for_vc' );
