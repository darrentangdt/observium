<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage definitions
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

// FIXME. $config['sensor_types'] >> $config['sensor']['types']

// The order these are entered here defines the order they are shown in the web interface
$config['sensor_types']['temperature'] = array( 'symbol' => 'C',       'text' => 'Celsius',             'icon' => $config['icon']['temperature']);
$config['sensor_types']['humidity']    = array( 'symbol' => '%',       'text' => 'Percent',             'icon' => $config['icon']['humidity']);
$config['sensor_types']['fanspeed']    = array( 'symbol' => 'RPM',     'text' => 'RPM',                 'icon' => $config['icon']['fanspeed']);
$config['sensor_types']['dewpoint']    = array( 'symbol' => 'C',       'text' => 'Celsius',             'icon' => $config['icon']['humidity']);
$config['sensor_types']['airflow']     = array( 'symbol' => 'CFM',     'text' => 'Airflow',             'icon' => $config['icon']['airflow']);
$config['sensor_types']['voltage']     = array( 'symbol' => 'V',       'text' => 'Volts',               'icon' => $config['icon']['voltage'],       'format' => 'si');
$config['sensor_types']['current']     = array( 'symbol' => 'A',       'text' => 'Amperes',             'icon' => $config['icon']['current'],       'format' => 'si');
//$config['sensor_types']['ecurrent']    = array( 'symbol' => 'Ah',      'text' => 'Amperehour',          'icon' => $config['icon']['current'],       'format' => 'si');
$config['sensor_types']['power']       = array( 'symbol' => 'W',       'text' => 'Watts',               'icon' => $config['icon']['power'],         'format' => 'si');
//$config['sensor_types']['energy']      = array( 'symbol' => 'Wh',      'text' => 'Watthour',            'icon' => 'oicon-lightning',          'format' => 'si');
$config['sensor_types']['apower']      = array( 'symbol' => 'VA',      'text' => 'VoltAmpere',          'icon' => $config['icon']['apower'],        'format' => 'si');
$config['sensor_types']['rpower']      = array( 'symbol' => 'VAr',     'text' => 'VoltAmpere Reactive', 'icon' => $config['icon']['rpower'],        'format' => 'si');
$config['sensor_types']['crestfactor'] = array( 'symbol' => '',        'text' => 'Crest Factor',        'icon' => $config['icon']['crestfactor']);
$config['sensor_types']['powerfactor'] = array( 'symbol' => '',        'text' => 'Power Factor',        'icon' => $config['icon']['powerfactor']);
$config['sensor_types']['impedance']   = array( 'symbol' => '&Omega;', 'text' => 'Impedance',           'icon' => $config['icon']['impedance']);
$config['sensor_types']['resistance']  = array( 'symbol' => '&Omega;', 'text' => 'Resistance',          'icon' => $config['icon']['resistance']);
$config['sensor_types']['frequency']   = array( 'symbol' => 'Hz',      'text' => 'Hertz',               'icon' => $config['icon']['frequency'],     'format' => 'si');
$config['sensor_types']['dbm']         = array( 'symbol' => 'dBm',     'text' => 'dBm',                 'icon' => $config['icon']['dbm']);
$config['sensor_types']['snr']         = array( 'symbol' => 'dB',      'text' => 'dB',                  'icon' => $config['icon']['antenna']);
$config['sensor_types']['dust']        = array( 'symbol' => 'mg/M3',   'text' => 'mg/M3',               'icon' => $config['icon']['antenna']);
$config['sensor_types']['sound']       = array( 'symbol' => 'dB',      'text' => 'dB',                  'icon' => $config['icon']['antenna']);
$config['sensor_types']['capacity']    = array( 'symbol' => '%',       'text' => 'Percent',             'icon' => $config['icon']['capacity']);
$config['sensor_types']['load']        = array( 'symbol' => '%',       'text' => 'Percent',             'icon' => $config['icon']['load']);
$config['sensor_types']['runtime']     = array( 'symbol' => 'min',     'text' => 'Minutes',             'icon' => $config['icon']['runtime']);
$config['sensor_types']['volume']      = array( 'symbol' => 'L',       'text' => 'Litres',              'icon' => $config['icon']['volume'],        'format' => 'si');
$config['sensor_types']['waterflow']   = array( 'symbol' => 'L/min',   'text' => 'Flow Rate',           'icon' => $config['icon']['flowrate']); // FIXME: L/s
$config['sensor_types']['pressure']    = array( 'symbol' => 'Pa',      'text' => 'Pressure',            'icon' => $config['icon']['pressure'],      'format' => 'si');
$config['sensor_types']['velocity']    = array( 'symbol' => 'm/s',     'text' => 'Velocity',            'icon' => $config['icon']['velocity']);
$config['sensor_types']['illuminance'] = array( 'symbol' => 'lux',     'text' => 'Illuminance',         'icon' => $config['icon']['illuminance']);
//$config['sensor_types']['lflux']       = array( 'symbol' => 'lm',      'text' => 'Luminous flux',       'icon' => $config['icon']['lflux']);
$config['sensor_types']['counter']     = array( 'symbol' => '',        'text' => 'Count',               'icon' => $config['icon']['counter']);

// IPMI sensor type mappings
$config['ipmi_unit']['Volts']     = 'voltage';
$config['ipmi_unit']['degrees C'] = 'temperature';
$config['ipmi_unit']['RPM']       = 'fanspeed';
$config['ipmi_unit']['Watts']     = 'power';
$config['ipmi_unit']['CFM']       = 'airflow';
$config['ipmi_unit']['percent']   = 'capacity';
$config['ipmi_unit']['discrete']  = '';

// EOF
