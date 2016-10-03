<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @copyright  (C) 2006-2014 Adam Armstrong
 * @author                   Dolf Schimmel
 *
 */

echo(" PDU2-MIB ");

$measures = array(
  'rmsCurrent'    => array('class' => 'current', 'oid_suffix' => 1, 'scale' => 0.1),
  'rmsVoltage'    => array('class' => 'voltage', 'oid_suffix' => 4, 'scale' => 1),
  'activePower'   => array('class' => 'power',   'oid_suffix' => 5, 'scale' => 1),
  'apparentPower' => array('class' => 'apower',  'oid_suffix' => 6, 'scale' => 1),
  // Todo: we don't support power factor yet
);

// Outlets
$outletNames                 = snmpwalk_cache_oid($device, "PDU2-MIB::outletName.1",                         array(), "PDU2-MIB");
$outletThresholds            = snmpwalk_cache_oid($device, "PDU2-MIB::outletSensorEnabledThresholds.1",      array(), "PDU2-MIB");
$measurements                = snmpwalk_cache_oid($device, "PDU2-MIB::measurementsOutletSensorValue.1",      array(), "PDU2-MIB");
$outlet_lower_crit_threshold = snmpwalk_cache_oid($device, "PDU2-MIB::outletSensorLowerCriticalThreshold.1", array(), "PDU2-MIB");
$outlet_lower_warn_threshold = snmpwalk_cache_oid($device, "PDU2-MIB::outletSensorLowerWarningThreshold.1",  array(), "PDU2-MIB");
$outlet_upper_warn_threshold = snmpwalk_cache_oid($device, "PDU2-MIB::outletSensorUpperWarningThreshold.1",  array(), "PDU2-MIB");
$outlet_upper_crit_threshold = snmpwalk_cache_oid($device, "PDU2-MIB::outletSensorUpperCriticalThreshold.1", array(), "PDU2-MIB");

foreach($measures as $measure_type => $measure_data)
{
  foreach ($outletNames as $index => $entry)
  {
    $oid = ".1.3.6.1.4.1.13742.6.5.4.3.1.4.$index." . $measure_data['oid_suffix'];
    $value = $measurements["$index.$measure_type"]['measurementsOutletSensorValue'];
    if ($entry['outletName'] != '')
    {
      $descr = "Outlet $index: " . $entry['outletName'];
    } else {
      $descr = "Outlet $index";
    }

    $rmsCurrentThreshold = hexdec($outletThresholds["$index.$measure_type"]);
    $limits = array(
      'limit_low' => $outletThresholds[$index . '.' . $measure_type]['outletSensorEnabledThresholds'] & 128
        ? $outlet_lower_crit_threshold[$index . '.' . $measure_type]['outletSensorLowerCriticalThreshold'] * $measure_data['scale'] : null,
      'limit_low_warn' => $outletThresholds[$index . '.' . $measure_type]['outletSensorEnabledThresholds'] & 64
        ? $outlet_lower_warn_threshold[$index . '.' . $measure_type]['outletSensorLowerWarningThreshold'] * $measure_data['scale'] : null,
      'limit_high_warn' => $outletThresholds[$index . '.' . $measure_type]['outletSensorEnabledThresholds'] & 32
        ? $outlet_upper_warn_threshold[$index .'.' . $measure_type]['outletSensorUpperWarningThreshold'] * $measure_data['scale'] : null,
      'limit_high' => $outletThresholds[$index . '.' . $measure_type]['outletSensorEnabledThresholds'] & 16
        ? $outlet_upper_crit_threshold [$index . '.' . $measure_type]['outletSensorUpperCriticalThreshold'] * $measure_data['scale'] : null
    );

    if ($value !== false)
    {
      discover_sensor(
        $valid['sensor'], $measure_data['class'], $device, $oid, "outlet.$index.$measure_type",
        'raritan', $descr, $measure_data['scale'], $value, $limits
      );
    }
  }
}

// Inlets
$inlet_names                = snmpwalk_cache_oid($device, "PDU2-MIB::inletName.1",                         array(), "PDU2-MIB");
$inlet_thresholds           = snmpwalk_cache_oid($device, "PDU2-MIB::inletSensorEnabledThresholds.1",      array(), "PDU2-MIB");
$measurements               = snmpwalk_cache_oid($device, "PDU2-MIB::measurementsInletSensorValue.1",      array(), "PDU2-MIB");
$inlet_lower_crit_threshold = snmpwalk_cache_oid($device, "PDU2-MIB::inletSensorLowerCriticalThreshold.1", array(), "PDU2-MIB");
$inlet_lower_warn_threshold = snmpwalk_cache_oid($device, "PDU2-MIB::inletSensorLowerWarningThreshold.1",  array(), "PDU2-MIB");
$inlet_upper_warn_threshold = snmpwalk_cache_oid($device, "PDU2-MIB::inletSensorUpperWarningThreshold.1",  array(), "PDU2-MIB");
$inlet_upper_crit_threshold = snmpwalk_cache_oid($device, "PDU2-MIB::inletSensorUpperCriticalThreshold.1", array(), "PDU2-MIB");

foreach($measures as $measure_type => $measure_data)
{
  foreach ($inlet_names as $index => $entry)
  {
    $oid = ".1.3.6.1.4.1.13742.6.5.2.3.1.4.$index." . $measure_data['oid_suffix'];
    $value = $measurements["$index.$measure_type"]['measurementsInletSensorValue'];
    if ($entry['inletName'] != '')
    {
      $descr = "Inlet $index: " . $entry['inletName'];
    } else {
      $descr = "Inlet $index";
    }

    $rmsCurrentThreshold = hexdec($inlet_thresholds["$index.$measure_type"]);
    $limits = array(
      'limit_low' => $inlet_thresholds[$index . '.' . $measure_type]['inletSensorEnabledThresholds'] & 128
        ? $inlet_lower_crit_threshold[$index . '.' . $measure_type]['inletSensorLowerCriticalThreshold'] * $measure_data['scale'] : null,
      'limit_low_warn' => $inlet_thresholds[$index . '.' . $measure_type]['inletSensorEnabledThresholds'] & 64
        ? $inlet_lower_warn_threshold[$index . '.' . $measure_type]['inletSensorLowerWarningThreshold'] * $measure_data['scale'] : null,
      'limit_high_warn' => $inlet_thresholds[$index . '.' . $measure_type]['inletSensorEnabledThresholds'] & 32
        ? $inlet_upper_warn_threshold[$index .'.' . $measure_type]['inletSensorUpperWarningThreshold'] * $measure_data['scale'] : null,
      'limit_high' => $inlet_thresholds[$index . '.' . $measure_type]['inletSensorEnabledThresholds'] & 16
        ? $inlet_upper_crit_threshold[$index . '.' . $measure_type]['inletSensorUpperCriticalThreshold'] * $measure_data['scale'] : null
    );

    if ($value !== false)
    {
      discover_sensor(
        $valid['sensor'], $measure_data['class'], $device, $oid, "inlet.$index.$measure_type",
        'raritan', $descr, $measure_data['scale'], $value, $limits
      );
    }
  }
}

// Over Current Protectors
$over_current_protector_names                = snmpwalk_cache_oid($device, "PDU2-MIB::overCurrentProtectorName.1",                         array(), "PDU2-MIB");
$protector_thresholds                        = snmpwalk_cache_oid($device, "PDU2-MIB::overCurrentProtectorSensorEnabledThresholds.1", array(), "PDU2-MIB");
$measurements                                = snmpwalk_cache_oid($device, "PDU2-MIB::measurementsOverCurrentProtectorSensorValue.1",      array(), "PDU2-MIB");
$over_current_protector_lower_crit_threshold = snmpwalk_cache_oid($device, "PDU2-MIB::overCurrentProtectorSensorLowerCriticalThreshold.1", array(), "PDU2-MIB");
$over_current_protector_lower_warn_threshold = snmpwalk_cache_oid($device, "PDU2-MIB::overCurrentProtectorSensorLowerWarningThreshold.1",  array(), "PDU2-MIB");
$over_current_protector_upper_warn_threshold = snmpwalk_cache_oid($device, "PDU2-MIB::overCurrentProtectorSensorUpperWarningThreshold.1",  array(), "PDU2-MIB");
$over_current_protector_upper_crit_threshold = snmpwalk_cache_oid($device, "PDU2-MIB::overCurrentProtectorSensorUpperCriticalThreshold.1", array(), "PDU2-MIB");

foreach ($over_current_protector_names as $index => $entry) {
  $limits = array(
    'limit_low' => $protector_thresholds[$index . '.rmsCurrent']['overCurrentProtectorSensorEnabledThresholds'] & 128
      ? $over_current_protector_lower_crit_threshold[$index . '.rmsCurrent']['overCurrentProtectorSensorLowerCriticalThreshold'] * 0.1 : null,
    'limit_low_warn' => $protector_thresholds[$index . '.rmsCurrent']['overCurrentProtectorSensorEnabledThresholds'] & 64
      ? $over_current_protector_lower_warn_threshold[$index . '.rmsCurrent']['overCurrentProtectorSensorLowerWarningThreshold'] * 0.1 : null,
    'limit_high_warn' => $protector_thresholds[$index . '.rmsCurrent']['overCurrentProtectorSensorEnabledThresholds'] & 32
      ? $over_current_protector_upper_warn_threshold[$index .'.rmsCurrent']['overCurrentProtectorSensorUpperWarningThreshold'] * 0.1 : null,
    'limit_high' => $protector_thresholds[$index . '.rmsCurrent']['overCurrentProtectorSensorEnabledThresholds'] & 16
      ? $over_current_protector_upper_crit_threshold[$index . '.rmsCurrent']['overCurrentProtectorSensorUpperCriticalThreshold'] * 0.1 : null
  );

  $value = $measurements["$index.rmsCurrent"]['measurementsOverCurrentProtectorSensorValue'];
  $oid = ".1.3.6.1.4.1.13742.6.5.3.3.1.4.$index.1";
  discover_sensor($valid['sensor'], 'current', $device, $oid, "tripsensorvalue.$index", 'raritan', "Over Current Protector $index", 0.1, $value, $limits);

  $value = $measurements["$index.trip"]['measurementsOverCurrentProtectorSensorValue'];
  $oid = ".1.3.6.1.4.1.13742.6.5.3.3.1.4.$index.15";
  // discover_sensor($valid['sensor'], 'state', $device, $oid, "tripsensor.$index", 'raritan', "Over Current Protector $index", NULL, $value, array('entPhysicalClass' => 'power'));
  discover_status($device, $oid, "measurementsOverCurrentProtectorSensorValue".$index, "pdu2-sensorstate", "Over Current Protector $index", $value, array('entPhysicalClass' => 'status'));

}

// EOF
