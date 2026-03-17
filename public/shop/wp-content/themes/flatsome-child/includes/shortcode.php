<?php

// [openstreetmap]
function custom_shortcode_map( $atts, $content = null ) {
  extract( $atts = shortcode_atts( array(
    '_id'        => 'map-' . rand(),
    'lat'        => '50.4221',
    'long'       => '30.5285',
    'class'      => '',
    'height'      => '400px',
  ), $atts ) );

  $classes = array( 'openstreetmap-element' );

  if ( $class ) {
    $classes[] = $class;
  }

  $classes = implode( ' ', $classes );

  ob_start();
  ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <div id="<?php echo esc_attr( $_id ); ?>" class="<?php echo esc_attr( $classes ); ?>" style="height: <?php echo $height; ?>">
    </div>
    <script>
      var coord = [<?php echo $lat; ?>, <?php echo $long; ?>];
      var map = L.map("<?php echo esc_attr( $_id ); ?>", {
          center: coord,
          zoom: 17,
          // zoomControl: false,
      });
      var tileLoad = L.tileLayer("https://a.tile.openstreetmap.org/{z}/{x}/{y}.png", {
          maxZoom: 19,
          attribution: `&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>`,
      }).addTo(map);

      tileLoad.on("tileload", function(e) {
          e.tile.setAttribute("alt", "Карта");
      });

      var marker = L.marker(coord).addTo(map);
    </script>
  <?php

  return ob_get_clean();
}
add_shortcode( 'openstreetmap', 'custom_shortcode_map' );

// [languagelist]
function custom_shortcode_lang_list( $atts, $content = null ) {
  extract( $atts = shortcode_atts( array(
    '_id'        => 'lang-' . rand(),
    'class'      => '',
    'type'      => '',
  ), $atts ) );

  $classes = array( 'languagelist' );

  if ( $class ) {
    $classes[] = $class;
  }

  $classes = implode( ' ', $classes );

  ob_start();
  ?>
    <ul id="<?php echo esc_attr( $_id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
    	<?php if(function_exists('pll_the_languages')) {
        pll_the_languages(array('display_names_as' => 'name', 'hide_current' => 1, 'dropdown' => 0 ));
     	} ?>
    </ul>
  <?php

  return ob_get_clean();
}
add_shortcode( 'languagelist', 'custom_shortcode_lang_list' );
?>