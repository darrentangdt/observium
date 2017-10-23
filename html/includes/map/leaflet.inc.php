<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage map
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

  register_html_resource('css', 'leaflet.css');
  register_html_resource('js', 'leaflet.js');
  //register_html_resource('js', '/geo.php');

  // [lat, lng], zoom
  if (is_numeric($config['frontpage']['map']['zoom']) &&
      is_numeric($config['frontpage']['map']['center']['lat']) &&
      is_numeric($config['frontpage']['map']['center']['lng']))
  {
    // Manual zoom & map center
    $leaflet_init   = '[' . $config['frontpage']['map']['center']['lat'] . ', ' .
                            $config['frontpage']['map']['center']['lng'] . '], ' .
                            $config['frontpage']['map']['zoom'];
    $leaflet_bounds = '';
  } else {
    // Auto zoom
    $leaflet_init   = '[0, -0], 2';
    $leaflet_bounds = 'map.fitBounds(markers.getBounds(), { padding: [30, 30] });';
  }
  switch ($config['frontpage']['map']['api'])
  {
    case 'mapbox':
      /* Requires API key. Configurable tile sources in future.
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
      maxZoom: 18,
      id: 'mapbox.streets',
      accessToken: '<?php echo $config['remote_api']['maps']['mapbox']['key']; ?>'
    }).addTo(map);
    */
      $leaflet_variant = ($config['frontpage']['map']['tiles'] == "carto-base-dark" ? "dark_all" : "light_all");
      $leaflet_url   = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}';
      $leaflet_copy  = 'Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' .
                       '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' .
                       'Imagery &copy; <a href="http://mapbox.com">Mapbox</a>';
      break;

    case 'carto':
    default:
      $leaflet_variant = ($config['frontpage']['map']['tiles'] == "carto-base-dark" ? "dark_all" : "light_all");
      $leaflet_url   = is_ssl() ? 'https://cartodb-basemaps-{s}.global.ssl.fastly.net/'.$leaflet_variant.'/{z}/{x}/{y}' : 'http://{s}.basemaps.cartocdn.com/'.$leaflet_variant.'/{z}/{x}/{y}';
      $leaflet_copy  = 'Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' .
                       '&copy; <a href="https://carto.com/attributions">CARTO</a>';
      break;
  }

?>

<script src="geo.php"></script>

<script type="text/javascript">

    var map = L.map('map').setView(<?php echo $leaflet_init; ?>);

    /* disable scroll wheel by default, toggle by click on map */
    map.scrollWheelZoom.disable();
    map.on('click', function() {
      if (map.scrollWheelZoom.enabled()) {
        map.scrollWheelZoom.disable();
      } else {
        map.scrollWheelZoom.enable();
      }
    });

    var icons = {
      ok: L.icon({
        iconUrl: 'images/svg/ok.svg',
        popupAnchor:  [0, 16],
        iconSize: [<?php echo $config['frontpage']['map']['okmarkersize']; ?>, <?php echo $config['frontpage']['map']['okmarkersize']; ?>] // size of the icon
        }),

      alert: L.icon({
        iconUrl: 'images/svg/high_priority.svg',
        popupAnchor:  [0, 12],
        iconSize: [<?php echo $config['frontpage']['map']['alertmarkersize']; ?>, <?php echo $config['frontpage']['map']['alertmarkersize']; ?>] // size of the icon
        }),
    };

      var tileUrl = '<?php echo $leaflet_url; ?>' + (L.Browser.retina? '@2x': '') + '.png';
      var layer = L.tileLayer(tileUrl, {
         detectRetina: true,
         attribution: '<?php echo $leaflet_copy; ?>'
      }).addTo(map);

    var markers = L.geoJson(geojson);

    <?php echo $leaflet_bounds; ?>

    var markers_up = L.geoJson(geojson, {

      //pointToLayer: function (feature, latlng) {
      //  return L.circleMarker(latlng, geojsonMarkerOptions);
      //},

      filter: function(feature, layer) {
        return feature.properties.state == "up";
      },

      onEachFeature: function(feature, layer) {
            if (feature.properties && feature.properties.popupContent) {
                layer.bindPopup(feature.properties.popupContent, {closeButton: false, offset: L.point(0, -20)});
                layer.on('mouseover', function() { layer.openPopup(); });
                layer.on('mouseout', function() { layer.closePopup(); });
            }
            layer.on('click', function() { window.open(feature.properties.url, "_self"); });
        },

       pointToLayer: function (geojson, latlng) {
         return L.marker(latlng, {
           icon: icons['ok']
         });
       },

    }).addTo(map);

    var markers_down = L.geoJson(geojson, {

      //pointToLayer: function (feature, latlng) {
      //  return L.circleMarker(latlng, geojsonMarkerOptions);
      //},

      filter: function(feature, layer) {
        return feature.properties.state != "up";
      },

      onEachFeature: function(feature, layer) {
            if (feature.properties && feature.properties.popupContent) {
                layer.bindPopup(feature.properties.popupContent, {closeButton: false, offset: L.point(0, -20)});
                layer.on('mouseover', function() { layer.openPopup(); });
                layer.on('mouseout', function() { layer.closePopup(); });
            }
            layer.on('click', function() { window.open(feature.properties.url, "_self"); });
        },

       pointToLayer: function (geojson, latlng) {
         return L.marker(latlng, {
           icon: icons['alert'],
           zIndexOffset: 1000
         });
       },

    }).addTo(map);

</script>

<?php

// EOF
