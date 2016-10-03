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

// pethMainPsePower.1 = Gauge32: 370 Watts
// pethMainPseOperStatus.1 = INTEGER: on(1)
// pethMainPseConsumptionPower.1 = Gauge32: 16 Watts
// pethMainPseUsageThreshold.1 = INTEGER: 80 %

$oids = snmpwalk_cache_oid($device, 'pethMainPseTable', array(), 'POWER-ETHERNET-MIB');

foreach ($oids as $index => $entry)
{
  $scale   = 1;
  $descr   = "PSE $index Power";
  $oid     = ".1.3.6.1.2.1.105.1.3.1.1.4.$index";
  $value   = $entry['pethMainPseConsumptionPower'];

  $limits = array('limit_high'      => $entry['pethMainPsePower'],
                  'limit_low'       => 0); // Hardcode 0 as lower limit. Low warning limit will be calculated.

  // Work around odd devices. 0 as threshold? Hah.
  // Juniper returns 'current usage in %' for this threshold, seriously guys. SNMP is hard.
  if ($entry['pethMainPseUsageThreshold'] != 0 && $device['os'] != 'junos')
  {
    $limits['limit_high_warn'] = $entry['pethMainPsePower'] * $entry['pethMainPseUsageThreshold'] / 100;
  }

  // If usage is >1000 and larger than the actual PSE power, this device is supplying data in mW.
  // Hello POWER-ETHERNET-MIB, this should be Watts obviously. Correct scale in this case.
  // If the device is not supplying power at discovery time, we will not know what the scale is and might get it wrong.
  // This will then only be corrected at the next discovery cycle, unfortunately.
  if ($entry['pethMainPseConsumptionPower'] > 1000 && $entry['pethMainPseConsumptionPower'] > $entry['pethMainPsePower'])
  {
    $scale = 0.001;
  }

  if ($value != '')
  {
    discover_sensor($valid['sensor'], 'power', $device, $oid, "pethMainPseConsumptionPower.$index", 'power-ethernet-mib', $descr, $scale, $value, $limits);
  }

  $descr   = "PSE $index Status";
  $oid     = ".1.3.6.1.2.1.105.1.3.1.1.3.$index";
  $value   = $entry['pethMainPseOperStatus'];

  if ($value != '')
  {
    discover_sensor($valid['sensor'], 'state', $device, $oid, "pethMainPseOperStatus.$index", 'power-ethernet-mib-pse-state', $descr, 1, $value);
  }
}

// EOF
