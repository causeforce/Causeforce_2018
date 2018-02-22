<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',    // Scripts and stylesheets
  'lib/extras.php',    // Custom functions
  'lib/setup.php',     // Theme setup
  'lib/titles.php',    // Page titles
  'lib/wrapper.php',   // Theme wrapper class
  'lib/customizer.php' // Theme customizer
];

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);
/**
 * Remove empty paragraphs created by wpautop()
 * @author Ryan Hamilton
 * @link https://gist.github.com/Fantikerz/5557617
 */
function remove_empty_p( $content ) {
    $content = force_balance_tags( $content );
    $content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
    $content = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content );
    return $content;
}
add_filter('the_content', 'remove_empty_p', 20, 1);

function content_experiments_head_script() { 

      // google experiments
      if(is_page('home')) { ?>
        <script>function utmx_section(){}function utmx(){}(function(){var
        k='164711692-0',d=document,l=d.location,c=d.cookie;
        if(l.search.indexOf('utm_expid='+k)>0)return;
        function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
        indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
        length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
        '<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
        '://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
        '&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
        valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
        '" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
        </script><script>utmx('url','A/B');</script>
      <?php } 
  }
  add_action( 'wp_head', 'content_experiments_head_script' );
  