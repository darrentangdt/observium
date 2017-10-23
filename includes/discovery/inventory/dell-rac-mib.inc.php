<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2015 Observium Limited
 *
 */

// Only run this mib for chassis systems
// DELL-RAC-MIB::drsProductType.0 = INTEGER: cmc(8)
$type = snmp_get($device, "drsProductType.0", "-Oqv", "DELL-RAC-MIB");
if ( strstr($type, "cmc") || strstr($type, "CMC") )
{
  if (!isset($cache_discovery['dell-rac-mib']))
  {
    $cache_discovery['dell-rac-mib'] = snmpwalk_cache_oid($device, 'drsChassisServerGroup', NULL, 'DELL-RAC-MIB');
  }

  $index = 1;
  $inventory[$index] = array(
      'entPhysicalName'         => $device['hardware'].' Chassis',
      'entPhysicalDescr'        => $device['hostname'],
      'entPhysicalClass'        => 'chassis',
      'entPhysicalIsFRU'        => 'true',
      'entPhysicalModelName'    => $device['hardware'],
      'entPhysicalSerialNum'    => $device['serial'],
      'entPhysicalHardwareRev'  => snmp_get($device, "drsProductVersion.0", "-Oqv", "DELL-RAC-MIB"),
      'entPhysicalFirmwareRev'  => $device['version'],
      'entPhysicalAssetID'      => $device['asset_tag'],
      'entPhysicalContainedIn'  => 0,
      'entPhysicalParentRelPos' => -1,
      'entPhysicalMfgName'      => 'Dell'
  );
  discover_inventory($valid['inventory'], $device, $index, $inventory[$index], 'dell-rac-mib');

  foreach ($cache_discovery['dell-rac-mib'] as $tmp => $entry)
  {
    if ($entry['drsServerSlotNumber'] === "N/A") { continue; }
    $index += 2;

    // Full height blades take up two slots and are marked as Extension
    if (!strstr($entry[drsServerSlotName],"Extension")) {
      $serial = $entry['drsServerServiceTag'];
      $inventory[$index] = array(
        'entPhysicalName'         => 'Slot '.$entry['drsServerSlotNumber'],
        'entPhysicalClass'        => 'container',
        'entPhysicalIsFRU'        => 'true',
        'entPhysicalContainedIn'  => 1,
        'entPhysicalParentRelPos' => $entry['drsServerSlotNumber'],
        'entPhysicalMfgName'      => 'Dell'
      );
      $model = $entry['drsServerModel'];
      if ( $entry['drsServerMonitoringCapable'] === "off") {
          $model .= ' (OFF)';
      }
      $inventory[$index+1] = array(
        'entPhysicalName'         => $entry['drsServerSlotName'],
        'entPhysicalDescr'        => $entry['drsServerSlotName'],
        'entPhysicalClass'        => 'module',
        'entPhysicalIsFRU'        => 'true',
        'entPhysicalModelName'    => $model,
        'entPhysicalSerialNum'    => $serial,
        'entPhysicalContainedIn'  => $index,
        'entPhysicalParentRelPos' => 1,
        'entPhysicalMfgName'      => 'Dell'
      );
      discover_inventory($valid['inventory'], $device, $index, $inventory[$index], 'dell-rac-mib');
      discover_inventory($valid['inventory'], $device, $index+1, $inventory[$index+1], 'dell-rac-mib');
      unset($serial, $model);

    } else {
      $i = $index-2;
      $inventory[$i]['entPhysicalName'] = $inventory[$i]['entPhysicalName'] . '+' . $entry['drsServerSlotNumber'];
      discover_inventory($valid['inventory'], $device, $i, $inventory[$i], 'dell-rac-mib');
    }
  }
}

// EOF
