<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

$temp = snmpwalk_cache_multi_oid($device, 'bsnSensorTemperature',       array(), 'AIRESPACE-WIRELESS-MIB');
$temp = snmpwalk_cache_multi_oid($device, 'bsnTemperatureAlarmLowLimit',  $temp, 'AIRESPACE-WIRELESS-MIB');
$temp = snmpwalk_cache_multi_oid($device, 'bsnTemperatureAlarmHighLimit', $temp, 'AIRESPACE-WIRELESS-MIB');

foreach ($temp as $index => $entry)
{
  $oid     = ".1.3.6.1.4.1.14179.2.3.1.13.$index";
  $descr   = "Unit Temperature $index";
  $options = array('limit_high' => $entry['bsnTemperatureAlarmHighLimit'],
                   'limit_low'  => $entry['bsnTemperatureAlarmLowLimit'],
                   'entPhysicalIndex' => $index);
  $value   = $entry['bsnSensorTemperature'];

  if (is_numeric($value) && $value < 5000)
  {
    discover_sensor($valid['sensor'], 'temperature', $device, $oid, $index, 'wlc', $descr, 1, $value, $options);
  }
}

// EOF
