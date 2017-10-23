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

$mib = 'Printer-MIB';

//Printer-MIB::prtMarkerMarkTech.1.1 = INTEGER: electrophotographicLaser(4)
//Printer-MIB::prtMarkerCounterUnit.1.1 = INTEGER: impressions(7)
//Printer-MIB::prtMarkerLifeCount.1.1 = Counter32: 19116
//Printer-MIB::prtMarkerPowerOnCount.1.1 = Counter32: 43
//Printer-MIB::prtMarkerProcessColorants.1.1 = INTEGER: 1
//Printer-MIB::prtMarkerSpotColorants.1.1 = INTEGER: 0
//Printer-MIB::prtMarkerAddressabilityUnit.1.1 = INTEGER: tenThousandthsOfInches(3)
//Printer-MIB::prtMarkerAddressabilityFeedDir.1.1 = INTEGER: 600
//Printer-MIB::prtMarkerAddressabilityXFeedDir.1.1 = INTEGER: 600
//Printer-MIB::prtMarkerNorthMargin.1.1 = INTEGER: 1968
//Printer-MIB::prtMarkerSouthMargin.1.1 = INTEGER: 1968
//Printer-MIB::prtMarkerWestMargin.1.1 = INTEGER: 1968
//Printer-MIB::prtMarkerEastMargin.1.1 = INTEGER: 1968
//Printer-MIB::prtMarkerStatus.1.1 = INTEGER: 2

$oids  = snmpwalk_cache_multi_oid($device, "prtMarkerEntry", array(), $mib);
$prt_supplies = snmpwalk_cache_oid($device, 'prtMarkerSuppliesDescription', array(), $mib);
//print_vars($oids);
$count = count($oids);
$total_printed_allow = TRUE;

foreach ($oids as $index => $entry)
{
  $printer_supply = dbFetchRow("SELECT * FROM `printersupplies` WHERE `device_id` = ? AND `supply_mib` = ? AND `supply_index` = ?", array($device['device_id'], 'jetdirect', $index));
  $marker_descr = "Printed ".nicecase($entry['prtMarkerCounterUnit']);
  list($hrDeviceIndex, $prtMarkerIndex) = explode('.', $index);
  $options = array('measured_class' => 'printersupply',
                   'measured_entity' => $printer_supply['supply_id'],
                   'sensor_unit' => $entry['prtMarkerCounterUnit']);

  // Lifetime counter (should be always single)
  $descr    = "Total $marker_descr";
  $oid_name = 'prtMarkerLifeCount';
  $oid      = '.1.3.6.1.2.1.43.10.2.1.4.' . $index;
  $value    = $entry[$oid_name];

  if (isset($entry[$oid_name]) && $total_printed_allow)
  {
    // CLEANME. Compatibility, remove in r8500, but not before CE 0.16.8
    // Rename olf rrd filename and old ds name
    $new_rrd = 'sensor-counter-Printer-MIB-prtMarkerLifeCount-'.$index;
    $renamed = rename_rrd($device, 'pagecount', $new_rrd);
    if ($renamed)
    {
      rrdtool_rename_ds($device, $new_rrd, 'pagecount', 'sensor');
    }

    discover_sensor($valid['sensor'], 'counter', $device, $oid, $index, $mib .'-'. $oid_name, $descr, 1, $value, $options);
    $total_printed_allow = FALSE; // Discover only first "Total Printed", all other always same
  }

  // PowerOn counter
  $descr    = "PowerOn $marker_descr";
  if ($prt_supplies[$index]['prtMarkerSuppliesDescription'])
  {
    $descr .= ' - ' . snmp_hexstring($prt_supplies[$index]['prtMarkerSuppliesDescription']);
  }
  $oid_name = 'prtMarkerPowerOnCount';
  $oid      = '.1.3.6.1.2.1.43.10.2.1.5.' . $index;
  $value    = $entry[$oid_name];

  discover_sensor($valid['sensor'], 'counter', $device, $oid, $index, $mib .'-'. $oid_name, $descr, 1, $value, $options);

  // prtMarkerStatus
  // FIXME, binary statuses currently unsupported
}

// EOF
