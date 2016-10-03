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

echo(" F5-BIGIP-SYSTEM-MIB ");

if (strpos($device['hardware'], 'BIG-IP Virtual Edition') === FALSE) // FIXME: Why? - Virtuals don't have hardware sensors
{
  $oids   = snmpwalk_cache_multi_oid($device, "sysPlatform",    array(), "F5-BIGIP-SYSTEM-MIB");
  $oids_v = snmpwalk_cache_oid($device, "sysBladeVoltageTable", array(), "F5-BIGIP-SYSTEM-MIB");
  $oids_t = snmpwalk_cache_oid($device, "sysBladeTempTable",    array(), "F5-BIGIP-SYSTEM-MIB");
}

$sysPlatform_oid = ".1.3.6.1.4.1.3375.2.1.3";
foreach ($oids as $index => $entry)
{
  $scale = 1; // Default scale
  foreach ($entry as $oid_name => $value)
  {
    switch ($oid_name)
    {
      case 'sysChassisFanStatus':
        $physical = "fan";
        $class    = "state";
        $descr    = "Chassis Fan $index";
        $oid      = "$sysPlatform_oid.2.1.2.1.2.$index";
        break;
      case 'sysChassisFanSpeed':
        $class    = "fanspeed";
        $descr    = "Chassis Fan $index";
        $oid      = "$sysPlatform_oid.2.1.2.1.3.$index";
        break;
      case 'sysChassisPowerSupplyStatus':
        $physical = "power";
        $class    = "state";
        $descr    = "Chassis Power Supply $index";
        $oid      = "$sysPlatform_oid.2.2.2.1.2.$index";
        break;
      case 'sysChassisTempTemperature':
        $class    = "temperature";
        $descr    = "Chassis Temperature $index";
        $oid      = "$sysPlatform_oid.2.3.2.1.2.$index";
        break;
      case 'sysCpuSensorTemperature':
        $class    = "temperature";
        list($slot, $cpu) = explode('.', $index);
        $descr    = "Slot $slot CPU $cpu";
        $oid      = "$sysPlatform_oid.6.2.1.2.$index";
        break;
      case 'sysCpuSensorFanSpeed':
        $class    = "fanspeed";
        list($slot, $cpu) = explode('.', $index);
        $descr    = "Slot $slot CPU $cpu";
        $oid      = "$sysPlatform_oid.6.2.1.3.$index";
        break;
      default:
        continue 2; // Skip all other
    }

    if ($class == 'state')
    {
      discover_sensor($valid['sensor'], $class, $device, $oid, "$oid_name.$index", 'f5-bigip-state',  $descr, NULL, $value, array('entPhysicalClass' => $physical));
    }
    else if (is_numeric($value))
    {
      discover_sensor($valid['sensor'], $class, $device, $oid, "$oid_name.$index", 'f5-bigip-system', $descr, $scale, $value);
    }
  }
}

foreach($oids_v as $index => $entry)
{
  $oid = '.1.3.6.1.4.1.3375.2.1.3.2.5.2.1.2.\"'.$index.'\"';
  $value = $entry['sysBladeVoltageVoltage'];
  discover_sensor($valid['sensor'], 'voltage', $device, $oid, "$index", 'f5-bigip-system', $index, '0.001', $value);
}
foreach($oids_t as $index => $entry)
{
  $oid = '.1.3.6.1.4.1.3375.2.1.3.2.4.2.1.2.'.$index;
  $descr = $entry['sysBladeTempLocation'];
  $value = $entry['sysBladeTempTemperature'];
  discover_sensor($valid['sensor'], 'temperature', $device, $oid, "$descr", 'f5-bigip-system', $descr, '1', $value);
}

unset($oids, $oids_t, $oids_v, $oid_name, $entry, $oid, $index, $class, $sysPlatform_oid);

// HA state
// F5-BIGIP-SYSTEM-MIB::sysCmSyncStatusId.0 = INTEGER: inSync(3)
// F5-BIGIP-SYSTEM-MIB::sysCmSyncStatusStatus.0 = STRING: In Sync
// F5-BIGIP-SYSTEM-MIB::sysCmSyncStatusColor.0 = INTEGER: green(0)
// F5-BIGIP-SYSTEM-MIB::sysCmSyncStatusSummary.0 = STRING: All devices in the device group are in sync
// F5-BIGIP-SYSTEM-MIB::sysCmFailoverStatusId.0 = INTEGER: active(4)
// F5-BIGIP-SYSTEM-MIB::sysCmFailoverStatusStatus.0 = STRING: ACTIVE
// F5-BIGIP-SYSTEM-MIB::sysCmFailoverStatusColor.0 = INTEGER: green(0)
// F5-BIGIP-SYSTEM-MIB::sysCmFailoverStatusSummary.0 = STRING: 1/1 active

$f5state['ha'] = snmp_get_multi($device, 'sysCmSyncStatusId.0 sysCmSyncStatusStatus.0 sysCmFailoverStatusId.0 sysCmFailoverStatusStatus.0', '-OQUs', $mib, mib_dirs('f5'));

if (isset($f5state['ha'][0])) {
  $descr = 'Config Sync ('.$f5state['ha'][0]['sysCmSyncStatusStatus'].')';
  $oid   = '.1.3.6.1.4.1.3375.2.1.14.1.1.0';
  $value = $f5state['ha'][0]['sysCmSyncStatusId'];
  discover_sensor($valid['sensor'], 'state', $device, $oid, 'sysCmSyncStatusId', 'f5-config-sync-state', $descr, NULL, $value, array('entPhysicalClass' => 'other'));

  $descr = 'HA State ('.$f5state['ha'][0]['sysCmFailoverStatusStatus'].')';
  $oid   = '.1.3.6.1.4.1.3375.2.1.14.3.1.0';
  $value = $f5state['ha'][0]['sysCmFailoverStatusId'];
  discover_sensor($valid['sensor'], 'state', $device, $oid, 'sysCmFailoverStatusId', 'f5-ha-state', $descr, NULL, $value, array('entPhysicalClass' => 'other'));
}

// EOF
