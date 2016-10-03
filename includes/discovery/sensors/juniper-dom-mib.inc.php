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

$jnxDomCurrentTable_oids = array(
  'jnxDomCurrentModuleTemperature',
  'jnxDomCurrentModuleTemperatureHighAlarmThreshold',
  'jnxDomCurrentModuleTemperatureLowAlarmThreshold',
  'jnxDomCurrentModuleTemperatureHighWarningThreshold',
  'jnxDomCurrentModuleTemperatureLowWarningThreshold',

  'jnxDomCurrentTxLaserBiasCurrent',
  'jnxDomCurrentTxLaserBiasCurrentHighAlarmThreshold',
  'jnxDomCurrentTxLaserBiasCurrentLowAlarmThreshold',
  'jnxDomCurrentTxLaserBiasCurrentHighWarningThreshold',
  'jnxDomCurrentTxLaserBiasCurrentLowWarningThreshold',

  'jnxDomCurrentRxLaserPower',
  'jnxDomCurrentRxLaserPowerHighAlarmThreshold',
  'jnxDomCurrentRxLaserPowerLowAlarmThreshold',
  'jnxDomCurrentRxLaserPowerHighWarningThreshold',
  'jnxDomCurrentRxLaserPowerLowWarningThreshold',

  'jnxDomCurrentTxLaserOutputPower',
  'jnxDomCurrentTxLaserOutputPowerHighAlarmThreshold',
  'jnxDomCurrentTxLaserOutputPowerLowAlarmThreshold',
  'jnxDomCurrentTxLaserOutputPowerHighWarningThreshold',
  'jnxDomCurrentTxLaserOutputPowerLowWarningThreshold'
);

$oids = array();
foreach ($jnxDomCurrentTable_oids as $oid)
{
  $oids = snmpwalk_cache_oid($device, $oid, $oids, 'JUNIPER-DOM-MIB');
}
//$oids = snmpwalk_cache_oid($device, 'jnxDomCurrentEntry',                    array(), 'JUNIPER-DOM-MIB');

foreach ($oids as $index => $entry)
{

  $options = array('entPhysicalIndex' => $index);

  $port    = get_port_by_index_cache($device['device_id'], $index);
  if (is_array($port))
  {
    $entry['ifDescr'] = $port['ifDescr'];
    $options['measured_class']  = 'port';
    $options['measured_entity'] = $port['port_id'];
  } else {
    $entry['ifDescr'] = snmp_get($device, "ifDescr.$index", '-Oqv', 'IF-MIB');
  }

  # jnxDomCurrentModuleTemperature[508] 35
  # jnxDomCurrentModuleTemperatureHighAlarmThreshold[508] 100
  # jnxDomCurrentModuleTemperatureLowAlarmThreshold[508] -25
  # jnxDomCurrentModuleTemperatureHighWarningThreshold[508] 95
  # jnxDomCurrentModuleTemperatureLowWarningThreshold[508] -20
  $descr    = $entry['ifDescr'] . ' DOM';
  $oid_name = 'jnxDomCurrentModuleTemperature';
  $oid_num  = ".1.3.6.1.4.1.2636.3.60.1.1.1.1.8.{$index}";
  $type     = 'juniper-dom'; // $mib . '-' . $oid_name;
  $scale    = 1;
  $value    = $entry[$oid_name];

  $limits   = array('limit_high'       => $entry['jnxDomCurrentModuleTemperatureHighAlarmThreshold'],
                    'limit_low'        => $entry['jnxDomCurrentModuleTemperatureLowAlarmThreshold'],
                    'limit_high_warn'  => $entry['jnxDomCurrentModuleTemperatureHighWarningThreshold'],
                    'limit_low_warn'   => $entry['jnxDomCurrentModuleTemperatureLowWarningThreshold']);

  if ($value != 0)
  {
    discover_sensor($valid['sensor'], 'temperature', $device, $oid_num, $index, $type, $descr, $scale, $value, array_merge($options, $limits));
  }

  if ($entry['jnxDomCurrentTxLaserBiasCurrent'] == 0 &&
      $entry['jnxDomCurrentTxLaserOutputPower'] == 0 && $entry['jnxDomCurrentRxLaserPower'] == 0)
  {
    // Skip other empty dom sensors
    continue;
  }

  // jnxDomCurrentTxLaserBiasCurrent
  $descr    = $entry['ifDescr'] . " TX Bias";
  $oid_name = 'jnxDomCurrentTxLaserBiasCurrent';
  $oid_num  = ".1.3.6.1.4.1.2636.3.60.1.1.1.1.6.{$index}";
  $type     = 'juniper-dom'; // $mib . '-' . $oid_name;
  $scale    = si_to_scale('micro'); // Yah, I forgot number :)
  $value    = $entry[$oid_name];

  $limits   = array('limit_high'       => $entry['jnxDomCurrentTxLaserBiasCurrentHighAlarmThreshold']   * $scale,
                    'limit_low'        => $entry['jnxDomCurrentTxLaserBiasCurrentLowAlarmThreshold']    * $scale,
                    'limit_high_warn'  => $entry['jnxDomCurrentTxLaserBiasCurrentHighWarningThreshold'] * $scale,
                    'limit_low_warn'   => $entry['jnxDomCurrentTxLaserBiasCurrentLowWarningThreshold']  * $scale);

  discover_sensor($valid['sensor'], 'current', $device, $oid_num, $index, $type, $descr, $scale, $value, array_merge($options, $limits));

  # jnxDomCurrentRxLaserPower[508] -507 0.01 dbm
  $descr    = $entry['ifDescr'] . " RX Power";
  $oid_name = 'jnxDomCurrentRxLaserPower';
  $oid_num  = ".1.3.6.1.4.1.2636.3.60.1.1.1.1.5.{$index}";
  $type     = 'juniper-dom-rx'; // $mib . '-' . $oid_name;
  $scale    = 0.01;
  $value    = $entry[$oid_name];

  $limits   = array('limit_high'       => $entry['jnxDomCurrentRxLaserPowerHighAlarmThreshold']   * $scale,
                    'limit_low'        => $entry['jnxDomCurrentRxLaserPowerLowAlarmThreshold']    * $scale,
                    'limit_high_warn'  => $entry['jnxDomCurrentRxLaserPowerHighWarningThreshold'] * $scale,
                    'limit_low_warn'   => $entry['jnxDomCurrentRxLaserPowerLowWarningThreshold']  * $scale);

  discover_sensor($valid['sensor'], 'dbm', $device, $oid_num, $index, $type, $descr, $scale, $value, array_merge($options, $limits));

  # jnxDomCurrentTxLaserOutputPower[508] -507 0.01 dbm
  $descr    = $entry['ifDescr'] . " TX Power";
  $oid_name = 'jnxDomCurrentTxLaserOutputPower';
  $oid_num  = ".1.3.6.1.4.1.2636.3.60.1.1.1.1.7.{$index}";
  $type     = 'juniper-dom-tx'; // $mib . '-' . $oid_name;
  $scale    = 0.01;
  $value    = $entry[$oid_name];

  $limits   = array('limit_high'       => $entry['jnxDomCurrentTxLaserOutputPowerHighAlarmThreshold']   * $scale,
                    'limit_low'        => $entry['jnxDomCurrentTxLaserOutputPowerLowAlarmThreshold']    * $scale,
                    'limit_high_warn'  => $entry['jnxDomCurrentTxLaserOutputPowerHighWarningThreshold'] * $scale,
                    'limit_low_warn'   => $entry['jnxDomCurrentTxLaserOutputPowerLowWarningThreshold']  * $scale);

  discover_sensor($valid['sensor'], 'dbm', $device, $oid_num, $index, $type, $descr, $scale, $value, array_merge($options, $limits));
}

// EOF
