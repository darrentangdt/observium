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

echo(" DELL-RAC-MIB ");

// Only run this mib for chassis systems
// DELL-RAC-MIB::drsProductType.0 = INTEGER: cmc(8)
$type = snmp_get($device, 'drsProductType.0', '-Oqv', 'DELL-RAC-MIB');
if ( strstr($type, "cmc") || strstr($type, "CMC") )
{
  if (!isset($cache_discovery['dell-rac-mib']))
  {
    $cache_discovery['dell-rac-mib'] = snmpwalk_cache_oid($device, 'drsChassisServerGroup', NULL, 'DELL-RAC-MIB');
  }

  foreach ($cache_discovery['dell-rac-mib'] as $index => $entry)
  {
    if ($entry['drsServerSlotNumber'] === "N/A") { continue; }

    // Full height blades take up two slots and are marked as Extension
    if (!strstr($entry[drsServerSlotName],"Extension"))
    {
      $oid  = '.1.3.6.1.4.1.674.10892.2.5.1.1.2.' . $index;
      $oidn = 'drsServerMonitoringCapable.' . $index;
      $name = 'Slot '. $entry['drsServerSlotNumber'] . ' (' . $entry['drsServerSlotName'] . ')';

      $slots[$entry['drsServerSlotNumber']] = array( 'name' => $name, 'state' => $entry['drsServerMonitoringCapable'], 'oid' => $oid, 'oidn' => $oidn );
    } /* else {
    // TODO: handle full height blades taking up 2 slots
    } */
  }

  foreach ($slots as $number => $entry)
  {
      discover_status($device, $entry['oid'], $entry['oidn'], 'dell-rac-mib-slot-state', $entry['name'], $entry['state'], array('entPhysicalClass' => 'other'));
  }
}

// EOF
