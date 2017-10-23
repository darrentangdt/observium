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

echo("CISCO-ENTITY-FRU-CONTROL-MIB ");

// Skip this MIB if we have any state from CISCO-ENVMON-MIB
$ent_count = dbFetchCell('SELECT COUNT(*) FROM `status` WHERE `device_id` = ? AND `status_type` = ?;', array($device['device_id'], 'cisco-envmon-state'));
if ($ent_count)
{
  return;
}

// Walk CISCO-ENTITY-FRU-CONTROL-MIB oids
$entity_array = array();
$oids = array('cefcFRUPowerSupplyGroupEntry', 'cefcFRUPowerStatusEntry', 'cefcFanTrayStatusEntry');
foreach ($oids as $oid)
{
  $entity_array = snmpwalk_cache_multi_oid($device, $oid, $entity_array, 'CISCO-ENTITY-FRU-CONTROL-MIB');
}

if (count($entity_array))
{
  // Pre-cache entity mib (if not cached in inventory module)
  if (is_array($GLOBALS['cache']['entity-mib']))
  {
    $entity_mib = $GLOBALS['cache']['entity-mib'];
    print_debug("ENTITY-MIB already cached");
  } else {
    $entity_mib = array();
    $oids       = array('entPhysicalDescr', 'entPhysicalName', 'entPhysicalClass', 'entPhysicalContainedIn', 'entPhysicalParentRelPos');
    foreach ($oids as $oid)
    {
      $entity_mib = snmpwalk_cache_multi_oid($device, $oid, $entity_mib, 'ENTITY-MIB:CISCO-ENTITY-VENDORTYPE-OID-MIB');
      if (!$GLOBALS['snmp_status']) { break; }
    }
    //$entity_mib = snmpwalk_cache_twopart_oid($device, 'entAliasMappingIdentifier', $entity_mib, 'ENTITY-MIB:IF-MIB');
  }

  // Merge with ENTITY-MIB
  if (count($entity_mib))
  {
    foreach ($entity_array as $index => $entry)
    {
      if (isset($entity_mib[$index]))
      {
        $entity_array[$index] = array_merge($entity_mib[$index], $entry);
      }
    }
  }
  unset($entity_mib);

  if (OBS_DEBUG > 1)
  {
    print_vars($entity_array);
  }

  foreach ($entity_array as $index => $entry)
  {
    if (!is_numeric($index)) { continue; }

    $descr = $entry['entPhysicalDescr'];

    // Power Supplies
    if ($entry['entPhysicalClass'] == 'powerSupply' && $entry['cefcFRUPowerAdminStatus'] != 'off')
    {
      $oid_name = 'cefcTotalDrawnCurrent';
      $oid_num  = '.1.3.6.1.4.1.9.9.117.1.1.1.1.4.'.$index;
      $type     = $mib . '-' . $oid_name;
      $scale    = 0.01; // cefcPowerUnits.100000470 = STRING: CentiAmps @ 12V
      $value    = $entry[$oid_name];
      if ($value > 0)
      {
        // Limits
        $options  = array();
        if ($entry['cefcTotalAvailableCurrent'] > 0)
        {
          $options['limit_high']      = $entry['cefcTotalAvailableCurrent'] / $scale;
          $options['limit_high_warn'] = $options['limit_high'] * 0.8;
        }

        discover_sensor($valid['sensor'], 'current', $device, $oid_num, $index, $type, $descr, $scale, $value, $options);
      }

      $oid_name = 'cefcFRUPowerOperStatus';
      $oid_num  = '.1.3.6.1.4.1.9.9.117.1.1.2.1.2.'.$index;
      $type     = 'PowerOperType';
      $value    = $entry[$oid_name];

      discover_status($device, $oid_num, $oid_name.'.'.$index, $type, $descr, $value, array('entPhysicalClass' => 'powersupply'));
    }

    // Fans
    if ($entry['entPhysicalClass'] == 'fan')
    {
      $oid_name = 'cefcFanTrayOperStatus';
      $oid_num  = '.1.3.6.1.4.1.9.9.117.1.4.1.1.1.'.$index;
      $type     = 'cefcFanTrayOperStatus';
      $value    = $entry[$oid_name];

      discover_status($device, $oid_num, $oid_name.'.'.$index, $type, $descr, $value, array('entPhysicalClass' => 'fan'));
    }
  }
}

// EOF
