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

/*
$oids = snmp_walk($device,'1.3.6.1.4.1.2636.3.1.13.1.7', '-Osqn','JUNIPER-MIB');
$oids = trim($oids);

foreach (explode("\n", $oids) as $data)
{
  $data = trim($data);
  $data = substr($data, 29);
  if ($data)
  {
    list($oid) = explode(' ', $data);
    $temperature_oid  = "1.3.6.1.4.1.2636.3.1.13.1.7.$oid";
    $descr_oid = "1.3.6.1.4.1.2636.3.1.13.1.5.$oid";
    $descr = snmp_get($device, $descr_oid, '-Oqv', 'JUNIPER-MIB');
    $temperature = snmp_get($device, $temperature_oid, '-Oqv', 'JUNIPER-MIB');
    if (!strstr($descr, 'No') && !strstr($temperature, 'No') && $descr != '' && $temperature != '0')
    {
      $descr = str_replace("\"", '', $descr);
      $descr = str_replace('temperature', '', $descr);
      $descr = str_replace('temperature', '', $descr);
      $descr = str_replace('sensor', '', $descr);
      $descr = trim($descr);

      discover_sensor($valid['sensor'], 'temperature', $device, $temperature_oid, $oid, 'junos', $descr, 1, $temperature);
    }
  }
}
*/

$oids = snmpwalk_cache_multi_oid($device, 'jnxOperatingEntry', array(), 'JUNIPER-MIB');
$oids = snmpwalk_cache_multi_oid($device, 'jnxFruEntry',         $oids, 'JUNIPER-MIB');
if (OBS_DEBUG > 1)
{
  print_vars($oids);
}

foreach ($oids as $index => $entry)
{
  $descr    = rewrite_entity_name($entry['jnxOperatingDescr']);

  // Temperature
  $oid_name = 'jnxOperatingTemp';
  $oid_num  = ".1.3.6.1.4.1.2636.3.1.13.1.7.$index";
  //$type     = $mib . '-' . $oid_name;
  $type     = 'junos'; // Compat with old discovery style
  $scale    = 1;
  $value    = $entry[$oid_name];
  if ($value != 0)
  {
    discover_sensor($valid['sensor'], 'temperature', $device, $oid_num, $index, $type, $descr, $scale, $value);
  }

  $oid_name = 'jnxOperatingState';
  $oid_num  = ".1.3.6.1.4.1.2636.3.1.13.1.6.$index";
  $type     = 'jnxOperatingState';
  $value    = $entry[$oid_name];
  switch ($entry['jnxFruType'])
  {
    case 'powerEntryModule':
      discover_status($device, $oid_num, $oid_name.'.'.$index, $type, $descr, $value, array('entPhysicalClass' => 'powersupply'));
      break;
    case 'fan':
      discover_status($device, $oid_num, $oid_name.'.'.$index, $type, $descr, $value, array('entPhysicalClass' => 'fan'));
      break;
  }

  // FIXME - jnxFruOfflineReason
}

// EOF
