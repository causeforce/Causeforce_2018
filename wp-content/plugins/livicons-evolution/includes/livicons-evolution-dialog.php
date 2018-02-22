<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) exit;

function livicons_evolution_add_livicon_evo_button( $page = null, $target = null ) {
	echo '<a href="javascript:" id="lievo-add-button" class="button" title="Add LivIcon"><span class="lievo-add-button-icon"></span>Add LivIcon</a>';
}
add_filter( 'media_buttons', 'livicons_evolution_add_livicon_evo_button', 111 );

function livicons_evolution_dialog() {
	$icons_list = livicons_evolution_icons_list();
    $gen_opt = get_option( 'livicons_evolution_general_options' );
    $viz_opt = get_option( 'livicons_evolution_visualization_options' );
    $anim_opt = get_option( 'livicons_evolution_animation_options' );
    $options = array_merge( $viz_opt, $anim_opt );
    $question_icon = '<span class="livicon-evo" data-options="name: question-alt; size: 20px; style: solid; solidColor: #d2f1f4; strokeStyle: round; strokeWidth: 2; colorsOnHover: custom; colorsHoverTime: 0.3; solidColorAction: #00bcd4; repeat:0;  strokeWidthFactorOnHover: none; rotate:0; flipHorizontal:false; flipVertical: false; drawOnViewport: false"></span>';
	?>

	<div id="lievo-dialog-holder">
		<div id="lievo-dialog-overlay" style="display:none;"></div>
		<div id="lievo-dialog-wrap" style="display:none;">
			<div id="lievo-dialog">
    			<div id="lievo-all-icons">
                    <div id="lievo-categories">
                        <h2 class="lievo-header">Categories</h2>
                        <ul id="lievo-category" class="lievo-list-unstyled">
                            <?php
                            foreach ($icons_list as $cat => $value) {
                                if ( $cat === 'miscellaneous' ) {
                                    echo '<li data-key="'. str_replace(' ', '-', $cat) .'" class="lievo-active">'. $cat .'</li>';
                                } else {
                                    echo '<li data-key="'. str_replace(' ', '-', $cat) .'">'. $cat .'</li>';
                                };
                            };
                            ?>
                        </ul>
                    </div>
                    <div id="lievo-icons-list">
                        <h2 class="lievo-header">Icons <small>(click an icon to configure it)</small></h2>
                        <div id="lievo-icons">
                            <?php
                            foreach ($icons_list as $cat => $value) {
                                echo '<div class="lievo-' .str_replace(' ', '-', $cat). '-icons">';
                                foreach ($value as $icon) {
                                    $icon = str_replace('NEW_', '', $icon);
                                    if ( $gen_opt['disable_anim_in_dialog'] === 'true' ) {
                                        $disab = 'false';
                                    } else {
                                        $disab = 'true';
                                    };
                                    if ( $gen_opt['use_placeholder'] === 'true' ) {
                                        echo '<div class="lievo-icon-wrapper" data-name="'. $icon .'"><p><span class="lievo-placeholder"></span></p><p class="lievo-icon-desc">'. $icon .'</p></div>';
                                    } else {
                                        echo '<div class="lievo-icon-wrapper" data-name="'. $icon .'"><p><span class="livicon-evo-in-list" data-options=" name: '. $icon .'; style: original; size: 60px; strokeStyle: original; strokeWidth: original; tryToSharpen: true; rotate: none; flipHorizontal: false; flipVertical: false; colorsOnHover: none; colorsHoverTime: 0.3; colorsWhenMorph: none; brightness: 0.1; saturation: 0.07; morphState: start; morphImage: none; allowMorphImageTransform: false; strokeWidthFactorOnHover: none; strokeWidthOnHoverTime: 0.3; keepStrokeWidthOnResize: false; animated: '. $disab .'; eventType: hover; eventOn: parent; autoPlay: false; delay: 0; duration: default; repeat: default; repeatDelay: default; drawOnViewport: false; touchEvents: false "></span></p><p class="lievo-icon-desc">'. $icon .'</p></div>';
                                    };
                                };
                                echo '</div>';
                            };
                            ?>
                        </div>
                    </div>
                    <div class="lievo-clearfix"></div>
                </div>
                <div id="lievo-curicon-wrapper">
                    <div id="lievo-curicon-panel">
                        <h2 class="lievo-header">LivIcon</h2>
                        <div id="lievo-gradparent">
                            <span class="lievo-grandparent-desc">grandparent</span>
                            <a tabindex="-1" class="lievo-popovers lievo-grandparent-popover" role="button" data-toggle="popover" data-trigger="focus" data-placement="left" data-html="true" data-content="These <b>grandparent</b> and <b>parent</b> elements, <b>color picker</b> for <b>parent</b>'s background and <b>helping contour</b> are for current design and testing purpose only.">
                                <?php echo $question_icon; ?>
                            </a>
                            <div id="lievo-parent">
                                <span class="lievo-parent-desc">parent</span>
                                <div id="lievo-bg-color-wrap">
                                    <input id="lievo-bg-color" class="lievo-color-picker" type="text" value="#ffffff" data-savedcolor="#ffffff">
                                </div>
                                <div id="lievo-curicon"></div>
                                <div id="lievo-helping-border">
                                    <form>
                                        <label>
                                            <input type="checkbox" id="lievo_show_border">show icon's contour
                                        </label>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="button-group lievo-control-btns">
                            <button id="lievo-back" type="button" class="button">Back to list</button>
                            <button id="lievo-reset" type="button" class="button">Reset to defaults</button>
                        </div>
                        <p class="lievo-control-p">Get pure code:</p>
                        <div class="button-group lievo-control-btns">
                            <button id="lievo-get-shortcode" type="button" class="button">Shortcode</button>
                            <button id="lievo-get-html" type="button" class="button">HTML</button>
                            <button id="lievo-get-javascript" type="button" class="button">JavaScript</button>
                        </div>
                        <p class="lievo-control-p">Paste into editor:</p>
                        <p class="lievo-control-btns">
                            <button id="lievo-paste-shortcode" type="button" class="button button-primary">Shortcode</button>
                            <span class="lievo-shortcode-paste-p">
                                <label>
                                    <input type="checkbox" id="compact_shortcode_paste"> Only different from
                                </label>
                                <?php if( current_user_can( 'manage_options' ) ) { ?>
                                    <a href="<?php echo admin_url( 'admin.php?page=lievo-visualization' ); ?>" target="_blank">defaults</a>
                                <?php } else { ?>
                                    <span>defaults</span>
                                <?php } ?>
                                <a tabindex="61" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="If you will change other default values please <b>keep in mind</b> that you may get an unexpected results.">
                                    <?php echo $question_icon; ?>
                                </a>
                            </span>
                        </p>
                    </div>
                    <div id="lievo-options-wrapper">
                        <h2 class="lievo-header">name: <span id="opt-name"></span></h2>
                        <ul class="lievo-tabs-menu" class="lievo-list-unstyled">
                            <li class="lievo-current" data-link="#lievo-visualization">Visualization</li>
                            <li data-link="#lievo-animation">Animation</li>
                            <li data-link="#lievo-general">General</li>
                        </ul>
                        <div class="lievo-clearfix"></div>
                        <div id="lievo-options">
                            <div id="lievo-visualization" class="lievo-tab-content">
                                <div>
                                    <form>
                                        <table class="form-table">
                                            <tbody>
                                                <tr>
                                                    <th>
                                                        style
                                                        <a tabindex="0" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="One of the five (5) possible icon styles.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td class="lievo-label-margin" colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-style" value="original" <?php checked( 'original', $options['style'] ); ?>>
                                                            original
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-style" value="solid" <?php checked( 'solid', $options['style'] ); ?>>
                                                            solid
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-style" value="lines" <?php checked( 'lines', $options['style'] ); ?>>
                                                            lines
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-style" value="linesAlt" <?php checked( 'linesAlt', $options['style'] ); ?>>
                                                            linesAlt
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-style" value="filled" <?php checked( 'filled', $options['style'] ); ?>>
                                                            filled
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        size
                                                        <a tabindex="1" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="A desired value in pixels or %.<br>Also can be controlled by a custom style sheet for different media queries with <code>!important</code> declaration, for example">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        
                                                        <?php
                                                            $value = esc_attr( $options['size'] );
                                                            $units = esc_attr( $options['sizeUnits'] );
                                                            $size = $value . $units;
                                                            if ( $units === 'px' ) {
                                                                $predefined = array( '20px', '30px', '60px', '120px', '180px' );
                                                                if ( ! in_array( $size, $predefined, true ) ) {
                                                                    $size = 'custom_px';
                                                                };
                                                                $custom_px_value = $value;
                                                                $custom_prc_value = '50';
                                                            } else if ( $units === '%' ) {
                                                                $predefined = array( '20%', '33.3%', '50%', '62%', '100%' );
                                                                if ( ! in_array( $size, $predefined, true ) ) {
                                                                    $size = 'custom_prc';
                                                                };
                                                                $custom_prc_value = $value;
                                                                $custom_px_value = '60';
                                                            };
                                                            unset( $predefined );
                                                        ?>

                                                        <label>
                                                            <input type="radio" name="opt-size" value="20px" <?php checked( '20px', $size ); ?>>
                                                            20px
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-size" value="30px" <?php checked( '30px', $size ); ?>>
                                                            30px
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-size" value="60px" <?php checked( '60px', $size ); ?>>
                                                            60px
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-size" value="120px" <?php checked( '120px', $size ); ?>>
                                                            120px
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-size" value="180px" <?php checked( '180px', $size ); ?>>
                                                            180px
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-size" id="opt-size-px" value="custom_px" <?php checked( 'custom_px', $size ); ?>>
                                                            <input class="small-text" id="opt-size-value-px" type="number" min="1" step="1" value="<?php echo $custom_px_value; ?>"> px
                                                        </label>
                                                        <br>
                                                        <label>
                                                            <input type="radio" name="opt-size" value="20%" <?php checked( '20%', $size ); ?>>
                                                            20%
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-size" value="33.3%" <?php checked( '33.3%', $size ); ?>>
                                                            33.3%
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-size" value="50%" <?php checked( '50%', $size ); ?>>
                                                            50%
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-size" value="62%" <?php checked( '62%', $size ); ?>>
                                                            62%
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-size" value="100%" <?php checked( '100%', $size ); ?>>
                                                            100%
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-size" id="opt-size-prc" value="custom_prc" <?php checked( 'custom_prc', $size ); ?>>
                                                            <input class="small-text" id="opt-size-value-prc" type="number" min="1" max="100" step="0.1" value="<?php echo $custom_prc_value; ?>"> %
                                                        </label>

                                                        <?php
                                                            unset( $value );
                                                            unset( $units );
                                                            unset( $size );
                                                            unset( $custom_px_value );
                                                            unset( $custom_prc_value );
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        strokeStyle
                                                        <a tabindex="2" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="Controls how stroke ends will look like.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td class="lievo-label-margin" colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-strokeStyle" value="original" <?php checked( 'original', $options['strokeStyle'] ); ?>>
                                                            original
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-strokeStyle" value="round" <?php checked( 'round', $options['strokeStyle'] ); ?>>
                                                            round
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-strokeStyle" value="square" <?php checked( 'square', $options['strokeStyle'] ); ?>>
                                                            square
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        strokeWidth
                                                        <a tabindex="3" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The stroke width of SVG shapes. Leave it <code>'original'</code> or set any numeric value (pixels).">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td class="lievo-label-margin" colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-strokeWidth" value="original" <?php checked( 'original', $options['strokeWidth'] ); ?>>
                                                            original
                                                        </label>
                                                        <label>
                                                            <input type="radio" id="opt-strokeWidth" name="opt-strokeWidth" value="custom" <?php checked( 'custom', $options['strokeWidth'] ); ?>>
                                                            <input class="small-text" id="opt-strokeWidth-value" type="number" step="1" min="0" value="<?php echo esc_attr( $options['customStrokeWidth'] ); ?>"> <i>px</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        tryToSharpen
                                                        <a tabindex="4" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="Apply or not a micro shift for SVG shapes to try to make them more sharp (crisp).">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-tryToSharpen" value="true" <?php checked( 'true', $options['tryToSharpen'] ); ?>>
                                                            true
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-tryToSharpen" value="false" <?php checked( 'false', $options['tryToSharpen'] ); ?>>
                                                            false
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        rotate
                                                        <a tabindex="5" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The <code>'none'</code> or any desired value in deg from range <code>0 ... 360</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-rotate" value="none" <?php checked( 'none', $options['rotate'] ); ?>>
                                                            none
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-rotate" id="opt-rotate" value="custom" <?php checked( 'custom', $options['rotate'] ); ?>>
                                                            <input class="small-text" id="opt-customRotate" type="number" step="0.5" min="0" max="360" value="<?php echo esc_attr( $options['customRotate'] ); ?>"> <i>deg</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        flipHorizontal
                                                        <a tabindex="6" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="<code>true</code> will flip an icon horizontally.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-flipHorizontal" value="true" <?php checked( 'true', $options['flipHorizontal'] ); ?>>
                                                            true
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-flipHorizontal" value="false" <?php checked( 'false', $options['flipHorizontal'] ); ?>>
                                                            false
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        flipVertical
                                                        <a tabindex="7" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="<code>true</code> will flip an icon vertically.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-flipVertical" value="true" <?php checked( 'true', $options['flipVertical'] ); ?>>
                                                            true
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-flipVertical" value="false" <?php checked( 'false', $options['flipVertical'] ); ?>>
                                                            false
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        strokeColor
                                                        <a tabindex="8" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The stroke color of SVG shapes. Takes effect when the <b>style</b> option is set to either <code>'filled'</code> or <code>'lines'</code> or <code>'linesAlt'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input class="small-text lievo-color" id="opt-strokeColor" type="text" value="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColor'] ); ?>" data-defcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColor'] ); ?>">
                                                    </td>
                                                <!-- </tr>
                                                <tr> -->
                                                    <th>
                                                        strokeColorAction
                                                        <a tabindex="9" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="Takes effect when the <b>colorsOnHover</b> or <b>colorsWhenMorph</b> options are set to <code>'custom'</code> and the <b>style</b> option is either <code>'original'</code> or <code>'filled'</code> or <code>'lines'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input class="small-text lievo-color" id="opt-strokeColorAction" type="text" value="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColorAction'] ); ?>" data-defcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColorAction'] ); ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        strokeColorAlt
                                                        <a tabindex="10" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The alternative stroke color of SVG shapes. Takes effect when the <b>style</b> option is set to <code>'linesAlt'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input class="small-text lievo-color" id="opt-strokeColorAlt" type="text" value="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColorAlt'] ); ?>" data-defcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColorAlt'] ); ?>">
                                                    </td>
                                                <!-- </tr>
                                                <tr> -->
                                                    <th>
                                                        strokeColorAltAction
                                                        <a tabindex="11" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="Takes effect when the <b>colorsOnHover</b> or <b>colorsWhenMorph</b> options are set to <code>'custom'</code> and the <b>style</b> option is <code>'linesAlt'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input class="small-text lievo-color" id="opt-strokeColorAltAction" type="text" value="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColorAltAction'] ); ?>" data-defcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['strokeColorAltAction'] ); ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        fillColor
                                                        <a tabindex="12" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The fill color of SVG shapes. Takes effect when the <b>style</b> option is set to <code>'filled'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input class="small-text lievo-color" id="opt-fillColor" type="text" value="<?php echo livicons_evolution_sanitize_hex_color( $options['fillColor'] ); ?>" data-defcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['fillColor'] ); ?>">
                                                    </td>
                                                <!-- </tr>
                                                <tr> -->
                                                    <th>
                                                        fillColorAction
                                                        <a tabindex="13" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="Takes effect when the <b>colorsOnHover</b> or <b>colorsWhenMorph</b> options are set to <code>'custom'</code> and the <b>style</b> option is either <code>'original'</code> or <code>'filled'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input class="small-text lievo-color" id="opt-fillColorAction" type="text" value="<?php echo livicons_evolution_sanitize_hex_color( $options['fillColorAction'] ); ?>" data-defcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['fillColorAction'] ); ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        solidColor
                                                        <a tabindex="14" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The main color of SVG shapes when the <b>style</b> option is set to <code>'solid'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input class="small-text lievo-color" id="opt-solidColor" type="text" value="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColor'] ); ?>" data-defcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColor'] ); ?>">
                                                    </td>
                                                <!-- </tr>
                                                <tr> -->
                                                    <th>
                                                        solidColorAction
                                                        <a tabindex="15" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="Takes effect when the <b>colorsOnHover</b> or <b>colorsWhenMorph</b> options are set to <code>'custom'</code> and the <b>style</b> option is <code>'solid'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input class="small-text lievo-color" id="opt-solidColorAction" type="text" value="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColorAction'] ); ?>" data-defcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColorAction'] ); ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        solidColorBg
                                                        <a tabindex="16" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The color of a background element on your page, on which an icon will appear. Takes effect when the <b>style</b> option is set to <code>'solid'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input class="small-text lievo-color" id="opt-solidColorBg" type="text" value="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColorBg'] ); ?>" data-defcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColorBg'] ); ?>">
                                                    </td>
                                                <!-- </tr>
                                                <tr> -->
                                                    <th>
                                                        solidColorBgAction
                                                        <a tabindex="17" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="Takes effect when the <b>style</b> option is <code>'solid'</code>.<br>This option is useful when a background element (on which a LivIcon lays) changes its color on hover event too.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input class="small-text lievo-color" id="opt-solidColorBgAction" type="text" value="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColorBgAction'] ); ?>" data-defcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['solidColorBgAction'] ); ?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        colorsOnHover
                                                        <a tabindex="18" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The one of the five (5) possible effects. For example, <code>'hue180'</code> will change an icon's colors around 180 deg of a color wheel.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td class="lievo-label-margin" colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-colorsOnHover" value="none" <?php checked( 'none', $options['colorsOnHover'] ); ?>>
                                                            none
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-colorsOnHover" value="lighter" <?php checked( 'lighter', $options['colorsOnHover'] ); ?>>
                                                            lighter
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-colorsOnHover" value="darker" <?php checked( 'darker', $options['colorsOnHover'] ); ?>>
                                                            darker
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-colorsOnHover" value="custom" <?php checked( 'custom', $options['colorsOnHover'] ); ?>>
                                                            custom
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-colorsOnHover" id="opt-colorsOnHover" value="hue" <?php checked( 'hue', $options['colorsOnHover'] ); ?>>
                                                            hue<input class="small-text" id="opt-colorsOnHover-hue" type="number" step="1" min="0" max="360" value="<?php echo esc_attr( $options['colorsOnHoverHue'] ); ?>"> <i>deg</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        colorsHoverTime
                                                        <a tabindex="19" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The duration of changing colors, when the <b>colorsOnHover</b> option is <b>not</b> set to <code>'none'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        <label>
                                                            <input class="small-text" id="opt-colorsHoverTime" type="number" step="0.05" min="0" value="<?php echo esc_attr( $options['colorsHoverTime'] ); ?>"> <i>seconds</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        colorsWhenMorph
                                                        <a tabindex="20" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="<b>For morph icons only.</b> The one of the five (5) possible effects. For example, <code>'hue180'</code> will change a morph icon's colors around 180 deg of a color wheel.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td class="lievo-label-margin" colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-colorsWhenMorph" value="none" <?php checked( 'none', $options['colorsWhenMorph'] ); ?>>
                                                            none
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-colorsWhenMorph" value="lighter" <?php checked( 'lighter', $options['colorsWhenMorph'] ); ?>>
                                                            lighter
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-colorsWhenMorph" value="darker" <?php checked( 'darker', $options['colorsWhenMorph'] ); ?>>
                                                            darker
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-colorsWhenMorph" value="custom" <?php checked( 'custom', $options['colorsWhenMorph'] ); ?>>
                                                            custom
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-colorsWhenMorph" id="opt-colorsWhenMorph" value="hue" <?php checked( 'hue', $options['colorsWhenMorph'] ); ?>>
                                                            hue<input class="small-text" id="opt-colorsWhenMorph-hue" type="number" step="1" min="0" max="360" value="<?php echo esc_attr( $options['colorsWhenMorphHue'] ); ?>"> <i>deg</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        brightness
                                                        <a tabindex="21" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The factor (multiplier) of changing colors' brightness, when the <b>colorsOnHover</b> or <b>colorsWhenMorph</b> options are set to <code>'lighter'</code> or <code>'darker'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        <input class="small-text" id="opt-brightness" type="number" value="<?php echo esc_attr( $options['brightness'] ); ?>" min="0.01" max="1" step="0.01">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        saturation
                                                        <a tabindex="22" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The factor (multiplier) of changing colors' saturation, when the <b>colorsOnHover</b> or <b>colorsWhenMorph</b> options are set to <code>'lighter'</code> or <code>'darker'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        <input class="small-text" id="opt-saturation" type="number" value="<?php echo esc_attr( $options['saturation'] ); ?>" min="0.01" max="1" step="0.01">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        morphState
                                                        <a tabindex="23" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="<b>For morph icons only.</b> The initial state of morph icon.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-morphState" value="start" <?php checked( 'start', $options['morphState'] ); ?>>
                                                            start
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-morphState" value="end" <?php checked( 'end', $options['morphState'] ); ?>>
                                                            end
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        morphImage
                                                        <a tabindex="24" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="<b>For morph icons only.</b> 'Background' morph icons can have an image (JPG, PNG, GIF, SVG) inside them. For example, avatars or photos of your users can be placed inside 'morph-square-sticker.svg' icon. The value can look like <code>'http://www.your_site.com/path/to/<br>user_avatar.jpg'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-morphImage" value="none" <?php checked( 'none', $options['morphImage'] ); ?>>
                                                            none
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-morphImage" id="opt-morphImage" value="url" <?php checked( 'url', $options['morphImage'] ); ?>>
                                                            <input class="regular-text" id="opt-morphImage-value" type="text" value="<?php echo esc_url( $options['morphImageUrl'] ); ?>" placeholder="http(s)://www.site.com/path/to/image.jpg" spellcheck="false" autocomplete="off">
                                                            <button id="lievo-morph-image-browse" type="button" class="button">Browse</button>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        allowMorphImageTransform
                                                        <a tabindex="25" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="<b>For morph icons only.</b> If <code>true</code> the inside image will be rotated and/or fliped with a morph icon together.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-allowMorphImageTransform" value="true" <?php checked( 'true', $options['allowMorphImageTransform'] ); ?>>
                                                            true
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-allowMorphImageTransform" value="false" <?php checked( 'false', $options['allowMorphImageTransform'] ); ?>>
                                                            false
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        strokeWidthFactorOnHover
                                                        <a tabindex="26" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="<code>'none'</code> or numeric value. Takes effect on mouse hover event. For example, to increase stroke width twice set the option to <code>2</code>">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-strokeWidthFactorOnHover" value="none" <?php checked( 'none', $options['strokeWidthFactorOnHover'] ); ?>>
                                                            none
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-strokeWidthFactorOnHover" id="opt-strokeWidthFactorOnHover" value="custom" <?php checked( 'custom', $options['strokeWidthFactorOnHover'] ); ?>> 
                                                            <input class="small-text" id="opt-strokeWidthFactorOnHover-value" type="number" step="0.1" min="0" value="<?php echo esc_attr( $options['strokeWidthFactorOnHoverValue'] ); ?>">
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        strokeWidthOnHoverTime
                                                        <a tabindex="27" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The duration of changing stroke width when the <b>strokeWidthFactorOnHover</b> option is <b>not</b> set to <code>'none'</code>.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        <label>
                                                            <input class="small-text" id="opt-strokeWidthOnHoverTime" type="number" step="0.05" min="0" value="<?php echo esc_attr( $options['strokeWidthOnHoverTime'] ); ?>"> <i>seconds</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        keepStrokeWidthOnResize
                                                        <a tabindex="28" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="<code>true</code> will keep the stroke width of shapes when the <b>strokeWidth</b> option is not set to <code>'original'</code>. Takes effect when and if an icon's size is changed for different screen sizes via media queries, for example.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td colspan="3">
                                                        <label>
                                                            <input type="radio" name="opt-keepStrokeWidthOnResize" value="true" <?php checked( 'true', $options['keepStrokeWidthOnResize'] ); ?>>
                                                            true
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-keepStrokeWidthOnResize" value="false" <?php checked( 'false', $options['keepStrokeWidthOnResize'] ); ?>>
                                                            false
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <div id="lievo-animation" class="lievo-tab-content">
                                <div>
                                    <form>
                                        <table class="form-table">
                                            <tbody>
                                                <tr>
                                                    <th>
                                                        animated
                                                        <a tabindex="29" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="If <code>false</code>, the icon is static.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="opt-animated" value="true" <?php checked( 'true', $options['animated'] ); ?>>
                                                            true
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-animated" value="false" <?php checked( 'false', $options['animated'] ); ?>>
                                                            false
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        eventType
                                                        <a tabindex="30" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="If it's set to <code>'none'</code> and <b>animated</b> option is set to <code>true</code>, the icon can be still animated with JavaScript method <code>.playLiviconEvo();</code>">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="opt-eventType" value="none" <?php checked( 'none', $options['eventType'] ); ?>>
                                                            none
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-eventType" value="hover" <?php checked( 'hover', $options['eventType'] ); ?>>
                                                            hover
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-eventType" value="click" <?php checked( 'click', $options['eventType'] ); ?>>
                                                            click
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        eventOn
                                                        <a tabindex="31" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="'Hover' and 'click' events can be bind not only on an icon itself (<code>'self'</code> value), but on <code>'parent'</code>, <code>'grandparent'</code> or any other element with <code>'#some_id'</code> or <code>'.some_class'</code> on your page.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="opt-eventOn" value="self" <?php checked( 'self', $options['eventOn'] ); ?>>
                                                            self
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-eventOn" value="parent" <?php checked( 'parent', $options['eventOn'] ); ?>>
                                                            parent
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-eventOn" value="grandparent" <?php checked( 'grandparent', $options['eventOn'] ); ?>>
                                                            grandparent
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-eventOn" id="opt-eventOn" value="custom" <?php checked( 'custom', $options['eventOn'] ); ?>>
                                                            <input id="opt-eventOn-value" type="text" placeholder="#some-id or .some-class" value="<?php echo esc_attr( $options['eventOnElem'] ); ?>" spellcheck="false" autocomplete="off">
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        autoPlay
                                                        <a tabindex="32" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The animation will start automatically.<br>Please be <b>very careful</b> with <code>true</code> value, especially with 'looped' animations.">
                                                            <span class="livicon-evo" data-options="name: warning-alt.svg; size: 20px; style: solid; solidColor: #f7bbc6; strokeStyle: round; strokeWidth: 2; colorsOnHover: custom; colorsHoverTime: 0.3; solidColorAction: #e55973; repeat: 0; strokeWidthFactorOnHover: none; rotate:0; flipHorizontal:false; flipVertical: false; drawOnViewport: false"></span>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="opt-autoPlay" value="true" <?php checked( 'true', $options['autoPlay'] ); ?>>
                                                            true
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-autoPlay" value="false" <?php checked( 'false', $options['autoPlay'] ); ?>>
                                                            false
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        delay
                                                        <a tabindex="33" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="The delay in seconds before an animation starts.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input id="opt-delay" class="small-text" type="number" step="0.05" min="0" value="<?php echo esc_attr( $options['delay'] ); ?>"> <i>seconds</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        duration
                                                        <a tabindex="34" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The total duration of an animation in seconds. The <code>'default'</code> value is different for every icon and is stored in SVG icon files themselves in the <b>data-animoptions</b> attribute.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td class="lievo-label-margin">
                                                        <label>
                                                            <input type="radio" name="opt-duration" value="default" checked="checked">
                                                            default <span id="def-duration"></span>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-duration" id="opt-duration" value="custom">
                                                            <input id="opt-duration-value" type="number" step="0.05" min="0" class="small-text"> <i>seconds</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        repeat
                                                        <a tabindex="35" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The total number of the animation repeats. The <code>'default'</code> value is different for every icon and is stored in SVG icon files themselves in the <b>data-animoptions</b> attribute.<br>This option does <b>not</b> take effect on morph icons.<br>Please be <b>careful</b> with <code>'loop'</code> value.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td class="lievo-label-margin">
                                                        <label>
                                                            <input type="radio" name="opt-repeat" value="default" checked="checked">
                                                            default <span id="def-repeat"></span>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-repeat" id="opt-repeat" value="custom">
                                                            <input id="opt-repeat-value" type="number" step="1" min="0" class="small-text"> <i>time(s)</i>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-repeat" value="loop">
                                                            loop
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        repeatDelay
                                                        <a tabindex="36" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="The delay in seconds between repeats. The <code>'default'</code> value is different for every icon and is stored in SVG icon files themselves in the <b>data-animoptions</b> attribute.<br>This option does <b>not</b> take effect on morph icons.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td class="lievo-label-margin">
                                                        <label>
                                                            <input type="radio" name="opt-repeatDelay" value="default" checked="checked">
                                                            default <span id="def-repeatDelay"></span>
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-repeatDelay" id="opt-repeatDelay" value="custom">
                                                            <input id="opt-repeatDelay-value" type="number" step="0.05" min="0" class="small-text"> <i>seconds</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        drawOnViewport
                                                        <a tabindex="37" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="If <code>true</code>, the icon will be 'drawn' when it appears first time in a browser viewport.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="opt-drawOnViewport" value="true" <?php checked( 'true', $options['drawOnViewport'] ); ?>>
                                                            true
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-drawOnViewport" value="false" <?php checked( 'false', $options['drawOnViewport'] ); ?>>
                                                            false
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        viewportShift
                                                        <a tabindex="38" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="Takes effect when the <b>drawOnViewport</b> option is set to <code>true</code>.<br>It means that an animation starts only if SVG is in a users browser's viewport at least the choosen value calculated from SVG height.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="opt-viewportShift" value="none" <?php checked( 'none', $options['viewportShift'] ); ?>>
                                                            none
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-viewportShift" value="oneHalf" <?php checked( 'oneHalf', $options['viewportShift'] ); ?>>
                                                            oneHalf
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-viewportShift" value="oneThird" <?php checked( 'oneThird', $options['viewportShift'] ); ?>>
                                                            oneThird
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-viewportShift" value="full" <?php checked( 'full', $options['viewportShift'] ); ?>>
                                                            full
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        drawDelay
                                                        <a tabindex="39" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="The delay in seconds before a 'drawing' animation starts.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input id="opt-drawDelay" type="number" step="0.05" min="0" class="small-text" value="<?php echo esc_attr( $options['drawDelay'] ); ?>"> <i>seconds</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        drawTime
                                                        <a tabindex="40" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="The duration in seconds for a 'drawing' animation of each icon's shape.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input id="opt-drawTime" type="number" step="0.05" min="0" class="small-text" value="<?php echo esc_attr( $options['drawTime'] ); ?>"> <i>seconds</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        drawStagger
                                                        <a tabindex="41" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="The delay in seconds for a 'drawing' animation for each icon's shape.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input id="opt-drawStagger" type="number" step="0.05" min="0" class="small-text" value="<?php echo esc_attr( $options['drawStagger'] ); ?>"> <i>seconds</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        drawStartPoint
                                                        <a tabindex="42" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="The starting point from where a 'drawing' animation starts for each icon's shape.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="opt-drawStartPoint" value="start" <?php checked( 'start', $options['drawStartPoint'] ); ?>>
                                                            start
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-drawStartPoint" value="middle" <?php checked( 'middle', $options['drawStartPoint'] ); ?>>
                                                            middle
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-drawStartPoint" value="end" <?php checked( 'end', $options['drawStartPoint'] ); ?>>
                                                            end
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        drawColor
                                                        <a tabindex="43" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="The color for 'drawing' lines.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <fieldset>
                                                            <label>
                                                                <input type="radio" name="opt-drawColor" value="same" <?php checked( 'same', $options['drawColor'] ); ?>>
                                                                same
                                                            </label>
                                                            <input type="radio" name="opt-drawColor" id="opt-drawColor" value="custom" <?php checked( 'custom', $options['drawColor'] ); ?>>
                                                            <input class="small-text lievo-color" id="opt-drawColor-value" type="text" value="<?php echo livicons_evolution_sanitize_hex_color( $options['customDrawColor'] ); ?>" data-defcolor="<?php echo livicons_evolution_sanitize_hex_color( $options['customDrawColor'] ); ?>">
                                                        </fieldset>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        drawColorTime
                                                        <a tabindex="44" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="The duration in seconds for a changing colors when a 'drawing' animation ends.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input id="opt-drawColorTime" type="number" step="0.05" min="0" class="small-text" value="<?php echo esc_attr( $options['drawColorTime'] ); ?>"> <i>seconds</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        drawReversed
                                                        <a tabindex="45" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="<code>true</code> will reverse the order of shapes drawing.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="opt-drawReversed" value="true" <?php checked( 'true', $options['drawReversed'] ); ?>>
                                                            true
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-drawReversed" value="false" <?php checked( 'false', $options['drawReversed'] ); ?>>
                                                            false
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        drawEase
                                                        <a tabindex="46" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-delay="100" data-html="true" data-content="The easing function according to <a href='http://greensock.com/docs/#/HTML5/Easing/Power1/' target='_blank'>GreenSock Ease Visualizer</a>">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input id="opt-drawEase" type="text" placeholder="Power1.easeOut" value="<?php echo esc_attr( $options['drawEase'] ); ?>" spellcheck="false" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        eraseDelay
                                                        <a tabindex="47" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="The delay in seconds before an 'erasing' animation starts.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input id="opt-eraseDelay" type="number" step="0.05" min="0" class="small-text" value="<?php echo esc_attr( $options['eraseDelay'] ); ?>"> <i>seconds</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        eraseTime
                                                        <a tabindex="48" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="The duration in seconds for an 'erasing' animation of each icon's shape.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input id="opt-eraseTime" type="number" step="0.05" min="0" class="small-text" value="<?php echo esc_attr( $options['eraseTime'] ); ?>"> <i>seconds</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        eraseStagger
                                                        <a tabindex="49" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="The delay in seconds for an 'erasing' animation for each icon's shape.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input id="opt-eraseStagger" type="number" step="0.05" min="0" class="small-text" value="<?php echo esc_attr( $options['eraseStagger'] ); ?>"> <i>seconds</i>
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        eraseStartPoint
                                                        <a tabindex="50" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-content="The starting point from where an 'erasing' animation starts for each icon's shape.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="opt-eraseStartPoint" value="start" <?php checked( 'start', $options['eraseStartPoint'] ); ?>>
                                                            start
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-eraseStartPoint" value="middle" <?php checked( 'middle', $options['eraseStartPoint'] ); ?>>
                                                            middle
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-eraseStartPoint" value="end" <?php checked( 'end', $options['eraseStartPoint'] ); ?>>
                                                            end
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        eraseReversed
                                                        <a tabindex="51" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="<code>true</code> will reverse the order of shapes erasing.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="opt-eraseReversed" value="true" <?php checked( 'true', $options['eraseReversed'] ); ?>>
                                                            true
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-eraseReversed" value="false" <?php checked( 'false', $options['eraseReversed'] ); ?>>
                                                            false
                                                        </label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        eraseEase
                                                        <a tabindex="52" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-delay="100" data-html="true" data-content="The easing function according to <a href='http://greensock.com/docs/#/HTML5/Easing/Power1/' target='_blank'>GreenSock Ease Visualizer</a>">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input id="opt-eraseEase" type="text" placeholder="Power1.easeOut" value="<?php echo esc_attr( $options['eraseEase'] ); ?>" spellcheck="false" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        touchEvents
                                                        <a tabindex="53" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="Apply or not special events handlers (<b>touchstart</b> and <b>touchend</b>) for touch devices.<br><b>This option is highly experimental.</b> It prevents default action of the events. Please carefully test on touch devices within a draft document before using on a production site.">
                                                            <span class="livicon-evo" data-options="name: warning-alt.svg; size: 20px; style: solid; solidColor: #f7bbc6; strokeStyle: round; strokeWidth: 2; colorsOnHover: custom; colorsHoverTime: 0.3; solidColorAction: #e55973; repeat: 0; strokeWidthFactorOnHover: none; rotate:0; flipHorizontal:false; flipVertical: false; drawOnViewport: false"></span>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <label>
                                                            <input type="radio" name="opt-touchEvents" value="true" <?php checked( 'true', $options['touchEvents'] ); ?>>
                                                            true
                                                        </label>
                                                        <label>
                                                            <input type="radio" name="opt-touchEvents" value="false" <?php checked( 'false', $options['touchEvents'] ); ?>>
                                                            false
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                            <div id="lievo-general" class="lievo-tab-content">
                                <div>
                                    <form>
                                        <table class="form-table">
                                            <tbody>
                                                <tr>
                                                    <th>
                                                        Add ID
                                                        <a tabindex="54" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="Unique ID value for the current icon. Without leading <code>#</code>">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input id="opt-add-id" class="text" type="text" placeholder="ID" value="" spellcheck="false" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Add class
                                                        <a tabindex="55" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="Additional class name for the current icon. Without leading <code>.</code>">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input id="opt-add-class" class="text" type="text" placeholder="class" value="" spellcheck="false" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Add Inline Styles
                                                        <a tabindex="56" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="Additional styles for the current icon">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input id="opt-add-css" class="regular-text" type="text" placeholder="additional styles" value="" spellcheck="false" autocomplete="off">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Add link
                                                        <a tabindex="57" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="Wrap the current icon with a link.">
                                                            <?php echo $question_icon; ?>
                                                        </a>
                                                    </th>
                                                    <td>
                                                        <input id="opt-add-link" class="regular-text" type="text" placeholder="http://" value="" spellcheck="false" autocomplete="off"><br>
                                                        <label for="opt-link-target">
                                                            <input type="checkbox" id="opt-link-target" name="opt-link-target"> Open link in a new tab
                                                        </label>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
            <div class="lievo-loader">
                <h2 class="lievo-loader-header">Icons are loading...</h2>
                <p class="lievo-loader-element"><span class="lievo-icon-loading"></span></p>
                <p class="lievo-loader-text">Please be patient, there are <?php echo esc_attr( substr(LIVICONS_EVOLUTION_VERSION, -3) )?> LivIcons total</p>
                <p class="">You can make loading faster by using a simple image placeholder,<br>which can be set on a settings page of the plugin.</p>
            </div>
            <div class="lievo-hidden" style="width:0;height:0;display:none;"></div>
            <a href="javascript:" id="lievo-dialog-close-btn">
                <span class="livicon-evo" data-options="name:close; size:20px; animated:false; strokeWidth:2; strokeColor:#444; style:lines"></span>
            </a>
        </div>
        <div id="lievo-modal" style="display:none;">
            <div class="lievo-modal-content">
                <h3 class="lievo-modal-title"></h3>
                <a href="javascript:" id="lievo-modal-close-btn">
                    <span class="livicon-evo" data-options="name:close; size:20px; animated:false; strokeWidth:2; strokeColor:#444; style:lines"></span>
                </a>
                <textarea id="lievo-code" class=""></textarea>
                <label>
                    <input type="checkbox" id="compact_code"> Only different from
                </label>
                <?php if( current_user_can( 'manage_options' ) ) { ?>
                    <a class="lievo-default-link" href="<?php echo admin_url( 'admin.php?page=lievo-visualization' ); ?>" target="_blank">defaults</a>
                <?php } else { ?>
                    <span style="position: relative;top: 2px;">defaults</span>
                <?php } ?>
                    <a tabindex="60" class="lievo-popovers" role="button" data-toggle="popover" data-trigger="focus" data-placement="right" data-html="true" data-content="If you will change other default values please <b>keep in mind</b> that you may get an unexpected results.">
                        <?php echo $question_icon; ?>
                    </a>
                <label for="collapsed_code">
                    <input type="checkbox" id="collapsed_code"> Collapsed code
                </label>
                <button id="lievo-copy-code" class="button button-primary">Copy</button>
                <div class="lievo-clearfix"></div>
                <p class="lievo-modal-note"></p>
            </div>
        </div>
	</div>
	<?php
}
add_action( 'admin_footer', 'livicons_evolution_dialog' );
