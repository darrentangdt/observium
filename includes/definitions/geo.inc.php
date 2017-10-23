<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage definitions
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

// Remote APIs options and keys

// Maps and GEO coding apis
$api = 'google';
$config['remote_api']['maps'][$api]['enable']       = TRUE;
$config['remote_api']['maps'][$api]['name']         = "Google";
$config['remote_api']['maps'][$api]['key']          = "";
$config['remote_api']['maps'][$api]['key_require']  = TRUE;
$config['remote_api']['maps'][$api]['key_url']      = "https://developers.google.com/maps/documentation/geocoding/get-api-key";

$api = 'openstreetmap';
$config['remote_api']['maps'][$api]['enable']       = TRUE;
$config['remote_api']['maps'][$api]['name']         = "OpenStreetMap";
$config['remote_api']['maps'][$api]['key']          = "";
$config['remote_api']['maps'][$api]['key_require']  = FALSE;
$config['remote_api']['maps'][$api]['key_url']      = "";

$api = 'mapquest';
$config['remote_api']['maps'][$api]['enable']       = TRUE;
$config['remote_api']['maps'][$api]['name']         = "MapQuest";
$config['remote_api']['maps'][$api]['key']          = "";
$config['remote_api']['maps'][$api]['key_require']  = TRUE;
$config['remote_api']['maps'][$api]['key_url']      = "https://developer.mapquest.com/user/register";

$api = 'yahoo';
$config['remote_api']['maps'][$api]['enable']       = TRUE;
$config['remote_api']['maps'][$api]['name']         = "Yahoo";
$config['remote_api']['maps'][$api]['key']          = "";
$config['remote_api']['maps'][$api]['key_require']  = FALSE;
$config['remote_api']['maps'][$api]['key_url']      = "";

$api = 'yandex';
$config['remote_api']['maps'][$api]['enable']       = TRUE;
$config['remote_api']['maps'][$api]['name']         = "Yandex";
$config['remote_api']['maps'][$api]['key']          = "";
$config['remote_api']['maps'][$api]['key_require']  = FALSE;
$config['remote_api']['maps'][$api]['key_url']      = "https://tech.yandex.ru/maps/keys";

$api = 'carto'; // https://carto.com/docs/carto-engine/maps-api/static-maps-api/
$config['remote_api']['maps'][$api]['enable']       = TRUE;
$config['remote_api']['maps'][$api]['name']         = "Carto";
$config['remote_api']['maps'][$api]['key']          = "";
$config['remote_api']['maps'][$api]['key_require']  = FALSE;
$config['remote_api']['maps'][$api]['key_url']      = "https://carto.com/signup";

$api = 'mapbox';
$config['remote_api']['maps'][$api]['enable']       = FALSE;
$config['remote_api']['maps'][$api]['name']         = "Mapbox";
$config['remote_api']['maps'][$api]['key']          = "";
$config['remote_api']['maps'][$api]['key_require']  = TRUE;
$config['remote_api']['maps'][$api]['key_url']      = "https://www.mapbox.com/studio/signup/";

// GEO coding request definitions

// See the usage limits here: http://wiki.openstreetmap.org/wiki/Nominatim_usage_policy
$api = 'openstreetmap';
$config['geo_api'][$api]['method']         = 'GET';
$config['geo_api'][$api]['ratelimit']      = 150; // Requests rate limit per day (I not remember where get this number, but OSM is has very strict limits)
$config['geo_api'][$api]['direct_url']     = "http://nominatim.openstreetmap.org/search";
$config['geo_api'][$api]['reverse_url']    = "http://nominatim.openstreetmap.org/reverse";
$config['geo_api'][$api]['params']         = array(
                                              'format' => 'json',
                                              'accept-language' => 'en',
                                             );
$config['geo_api'][$api]['direct_params']  = array(
                                              'addressdetails' => '1',
                                              'limit' => '1',
                                             );
$config['geo_api'][$api]['request_params'] = array(
                                              'address' => 'q',
                                              // Lat/Lon as separate params
                                              'lat'     => 'lat',
                                              'lon'     => 'lon',
                                             );

/* Not complete, seems as very bad GLOBAL world search (only US)
// Direct example:
// https://api.mapbox.com/geocoding/v5/mapbox.places/1600+pennsylvania+ave+nw.json&access_token=your-access-token
// Reverse example:
// https://api.mapbox.com/geocoding/v5/mapbox.places/-73.989,40.733.json?access_token=your-access-token
$api = 'mapbox';
$config['geo_api'][$api]['method']         = 'GET';
$config['geo_api'][$api]['ratelimit']      = 864000; // Requests rate limit per day (https://www.mapbox.com/api-documentation/#rate-limits)
$config['geo_api'][$api]['direct_url']     = "https://api.mapbox.com/geocoding/v5/mapbox.places/";
$config['geo_api'][$api]['reverse_url']    = "https://api.mapbox.com/geocoding/v5/mapbox.places/";
$config['geo_api'][$api]['params']         = array(
                                              '.' => 'json',
                                              'accept-language' => 'en',
                                             );
$config['geo_api'][$api]['direct_params']  = array(
                                              'addressdetails' => '1',
                                              'limit' => '1',
                                             );
$config['geo_api'][$api]['request_params'] = array(
                                              'address' => '/',
                                              // Lat/Lon as separate params
                                              'lat'     => 'lat',
                                              'lon'     => 'lon',
                                              // KEY param
                                              'key'     => 'access_token',
                                             );
*/

// See documentation here: https://developers.google.com/maps/documentation/geocoding/
// Use of the Google Geocoding API is subject to a query limit of 2,500 geolocation requests per day (without key).
// KEY: https://developers.google.com/maps/documentation/geocoding/get-api-key
$api = 'google';
$config['geo_api'][$api]['method']         = 'GET';
$config['geo_api'][$api]['ratelimit']      = 2500;
$config['geo_api'][$api]['direct_url']     = "https://maps.googleapis.com/maps/api/geocode/json";
$config['geo_api'][$api]['reverse_url']    = "https://maps.googleapis.com/maps/api/geocode/json";
$config['geo_api'][$api]['params']         = array(
                                              'sensor' => 'false',
                                              'language' => 'en',
                                             );
$config['geo_api'][$api]['request_params'] = array(
                                              'address' => 'address',
                                              // Lat/Lon as single param with comma
                                              'latlon'  => 'latlng',
                                              // KEY param
                                              'key'     => 'key',
                                             );

// See documentation here: https://developer.yahoo.com/boss/geo/docs/pfrequests.html
$api = 'yahoo';
$config['geo_api'][$api]['method']         = 'GET';
$config['geo_api'][$api]['ratelimit']      = 2000;
$config['geo_api'][$api]['direct_url']     = "http://gws2.maps.yahoo.com/findlocation";
$config['geo_api'][$api]['reverse_url']    = "http://gws2.maps.yahoo.com/findlocation";
$config['geo_api'][$api]['params']         = array(
                                              'format' => 'json',
                                              'locale' => 'en_US',
                                              'flags'  => 'J',
                                              'pf'     => 1,
                                              'count'  => 1,
                                             );
$config['geo_api'][$api]['reverse_params'] = array(
                                              'gflags' => 'R',
                                             );
$config['geo_api'][$api]['request_params'] = array(
                                              'address' => 'location',
                                              // Lat/Lon as single param with comma
                                              'latlon'  => 'location',
                                              'id'      => 'appid', // application identificator
                                              // KEY param
                                              //'key'     => 'key',
                                             );

// Documentation: https://tech.yandex.ru/maps/doc/geocoder/desc/concepts/About-docpage/
// KEY: https://tech.yandex.ru/maps/keys/
// Note, If the key parameter is not passed, then the search is only available for the following countries:
// Russia, Ukraine, Belarus, Kazakhstan, Georgia, Abkhazia, South Ossetia, Armenia, Azerbaijan, Moldova,
// Turkmenistan, Tajikistan, Uzbekistan, Kyrgyzstan and Turkey.
$api = 'yandex';
$config['geo_api'][$api]['method']         = 'GET';
$config['geo_api'][$api]['direct_url']     = "http://geocode-maps.yandex.ru/1.x/";
$config['geo_api'][$api]['reverse_url']    = "http://geocode-maps.yandex.ru/1.x/";
$config['geo_api'][$api]['params']         = array(
                                              'format' => 'json',
                                              'lang' => 'en_US',
                                              'results' => '1',
                                             );
$config['geo_api'][$api]['reverse_params'] = array(
                                              'sco' => 'latlong',
                                             );
$config['geo_api'][$api]['request_params'] = array(
                                              'address' => 'geocode',
                                              // Lat/Lon as single param with comma
                                              'latlon'  => 'geocode',
                                              // KEY param
                                              'key'     => 'key',
                                             );

// See documentation here: http://www.mapquestapi.com/geocoding/
// MapQuest required KEY: https://developer.mapquest.com/user/register
$api = 'mapquest';
$config['geo_api'][$api]['method']         = 'GET';
$config['geo_api'][$api]['direct_url']     = "http://open.mapquestapi.com/nominatim/v1/search.php";
$config['geo_api'][$api]['reverse_url']    = "http://open.mapquestapi.com/nominatim/v1/reverse.php";
$config['geo_api'][$api]['params']         = array(
                                              'format' => 'json',
                                              'accept-language' => 'en',
                                             );
$config['geo_api'][$api]['direct_params']  = array(
                                              'addressdetails' => '1',
                                              'limit' => '1',
                                             );
$config['geo_api'][$api]['request_params'] = array(
                                              'address' => 'q',
                                              // Lat/Lon as separate params
                                              'lat'     => 'lat',
                                              'lon'     => 'lon',
                                              // KEY param
                                              'key'     => 'key',
                                             );

// EOF
