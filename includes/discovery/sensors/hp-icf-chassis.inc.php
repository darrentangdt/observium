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

$sensor_array = snmpwalk_cache_oid($device, 'hpicfSensorTable', array(), 'HP-ICF-CHASSIS');

foreach ($sensor_array as $index => $entry)
{
  $descr = rewrite_entity_name($entry['hpicfSensorDescr']);

  // Find entPhysicalClass some way
  if (stripos($descr, 'fan') !== FALSE)
  {
    $entPhysicalClass = 'fan';
  }
  else if (stripos($descr, 'power') !== FALSE)
  {
    $entPhysicalClass = 'power';
  }
  else if (stripos($descr, 'temperature') !== FALSE)
  {
    $entPhysicalClass = 'temperature';
  } else {
    $entPhysicalClass = 'other';
  }

  $oid   = '.1.3.6.1.4.1.11.2.14.11.1.2.6.1.4.'.$index;
  $value = $entry['hpicfSensorStatus'];

  if ($entry['hpicfSensorStatus'] != 'notPresent')
  {
    discover_sensor($valid['sensor'], 'state', $device, $oid, $index, 'hp-icf-chassis-state', $descr, 1, $value, array('entPhysicalClass' => $entPhysicalClass));
  }
}

unset($sensor_array, $index, $value, $descr);

// EOF
