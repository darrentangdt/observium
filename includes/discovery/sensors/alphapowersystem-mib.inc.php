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

/// dcPwrSysRectIpTable

/*
 dcPwrSysRectIpIndex dcPwrSysRectIpName dcPwrSysRectIpIntegerValue  dcPwrSysRectIpStringValue
                   1   Rectifier input1                        989    Total Rectifier Current
                   2   Rectifier input2                       5400             Avg DC Voltage
                   3   Rectifier input3                      24300             Avg AC Voltage
                   4   Rectifier input4                          2      # Acquired Rectifiers
                   5   Rectifier input5                          2      # Sourcing Rectifiers
                   6   Rectifier input6                          0        # Failed Rectifiers
                   7   Rectifier input7                          0   # Minor Alarm Rectifiers
                   8   Rectifier input8                          0    # Comms Lost Rectifiers
                   9   Rectifier input9                          0     # AC Failed Rectifiers
                  10  Rectifier input10                          0         # Out Of Tolerance
                  11  Rectifier input11                          0    # Locked Out Rectifiers
                  12  Rectifier input12                          0      # Equalize Rectifiers
                  13  Rectifier input13                          0 # Current Limit Rectifiers
                  14  Rectifier input14                          0   # Power Limit Rectifiers
                  15  Rectifier input15                          0    # Fan Failed Rectifiers
                  16  Rectifier input16                          0  # Power Saving Rectifiers
                  17  Rectifier input17                          0            Avg. AC Phase R
                  18  Rectifier input18                          0            Avg. AC Phase S
                  19  Rectifier input19                          0            Avg. AC Phase T
*/


$dcPwrSysRectIpTable = snmpwalk_cache_multi_oid($device, 'dcPwrSysRectIpEntry', array(), 'AlphaPowerSystem-MIB');

foreach ($dcPwrSysRectIpTable as $index => $entry) {

  # only add sesnsors with values set
  if ($entry['dcPwrSysRectIpIntegerValue']) {

    $descr = $entry['dcPwrSysRectIpStringValue'];

    #only deal with certain types of sensors based on dcPwrSysRectIpStringValue
    if (strpos($descr, "Voltage") || strpos($descr, "Current")) {

      $oid = "1.3.6.1.4.1.7309.4.1.6.3.2.1.3." . $index;
      $value = $entry['dcPwrSysRectIpIntegerValue'];
      $scale = .01;

      $type = "";
      if (strpos($descr, 'Voltage') !== false) {
        $type = "voltage";
      }
      elseif (strpos($descr, 'Current') !== false) {
        $type = "current";
      }

      discover_sensor($valid['sensor'], $type, $device, $oid, $descr, 'AlphaPowerSystem-MIB', $descr, $scale, $value);
    }
  }
}

// EOF
