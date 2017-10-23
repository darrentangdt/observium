<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

// Temperature Table

$data = snmpwalk_cache_multi_oid($device, "tempSensorEntry", array(), "NETBOTZ410-MIB");

foreach($data as $index => $entry)
{
  $oid = '.1.3.6.1.4.1.5528.100.4.1.1.1.2.' . $index;
  discover_sensor($valid['sensor'], 'temperature', $device, $oid, $index, 'tempSensor', $entry['tempSensorLabel'], 0.1, $entry['tempSensorValue']);
}

unset($data, $index, $entry);

// Humidity Table

$data = snmpwalk_cache_multi_oid($device, "humiSensorEntry", array(), "NETBOTZ410-MIB");

foreach($data as $index => $entry)
{
  $oid = '.1.3.6.1.4.1.5528.100.4.1.2.1.2.' . $index;
  discover_sensor($valid['sensor'], 'humidity', $device, $oid, $index, 'humiSensor', $entry['humiSensorLabel'], 0.1, $entry['humiSensorValue']);
}

unset($data, $index, $entry);

// Dew Point Table

$data = snmpwalk_cache_multi_oid($device, "dewPointSensorEntry", array(), "NETBOTZ410-MIB");

foreach($data as $index => $entry)
{
  $oid = '.1.3.6.1.4.1.5528.100.4.1.3.1.2.' . $index;
  discover_sensor($valid['sensor'], 'dewpoint', $device, $oid, $index, 'dewPointSensor', $entry['dewPointSensorLabel'], 0.1, $entry['dewPointSensorValue']);
}

unset($data, $index, $entry);

/** Currently Disabled because the unit isn't reported

// Audio Sensor Table

$data = snmpwalk_cache_multi_oid($device, "audioSensorEntry", array(), "NETBOTZ410-MIB");

foreach($data as $index => $entry)
{
  $oid = '.1.3.6.1.4.1.5528.100.4.1.4.1.2.' . $index;
  discover_sensor($valid['sensor'], 'sound', $device, $oid, $index, 'audioPointSensor', $entry['audioPointSensorLabel'], 0.1, $entry['audioPointSensorValue']);
}

unset($data, $index, $entry);

*/

// Airflow Table

$data = snmpwalk_cache_multi_oid($device, "airFlowSensorEntry", array(), "NETBOTZ410-MIB");

foreach($data as $index => $entry)
{
  $oid = '.1.3.6.1.4.1.5528.100.4.1.5.1.2.' . $index;
  discover_sensor($valid['sensor'], 'airflow', $device, $oid, $index, 'airFlowSensor', $entry['dewPointSensorLabel'], 3.531466672, $entry['airFlowSensorValue']);
}
unset($data, $index, $entry);

// Amperes Table

$data = snmpwalk_cache_multi_oid($device, "ampDetectSensorEntry", array(), "NETBOTZ410-MIB");

foreach($data as $index => $entry)
{
  $oid = '.1.3.6.1.4.1.5528.100.4.1.6.1.2.' . $index;
  discover_sensor($valid['sensor'], 'current', $device, $oid, $index, 'ampDetectSensor', $entry['ampDetectSensorLabel'], 0.1, $entry['ampDetectSensorValue']);
}
unset($data, $index, $entry);


// EOF
