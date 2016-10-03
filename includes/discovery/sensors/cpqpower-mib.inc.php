<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @copyright  (C) 2006-2014 Adam Armstrong
 *
 */

$hpups_array = array();
$hpups_array = snmpwalk_cache_multi_oid($device, 'upsInput', $hpups_array, 'CPQPOWER-MIB');
$hpups_array = snmpwalk_cache_multi_oid($device, 'upsOutput', $hpups_array, 'CPQPOWER-MIB');
$hpups_array = snmpwalk_cache_multi_oid($device, 'upsBypass', $hpups_array, 'CPQPOWER-MIB');

foreach (array_slice(array_keys($hpups_array),1) as $phase)
{
  # Skip garbage output:
  # upsOutput.6.0 = 0
  # upsOutput.7.0 = 0
  # upsOutput.8.0 = 0
  if (!isset($hpups_array[$phase]['upsInputPhase'])) { break; }

  # Input
  $index = $hpups_array[$phase]['upsInputPhase'];
  $descr = 'Input'; if ($hpups_array[0]['upsInputNumPhases'] > 1) { $descr .= " Phase $index"; }

  ## Input voltage
  $oid   = "1.3.6.1.4.1.232.165.3.3.4.1.2.$index"; # CPQPOWER-MIB:upsInputVoltage.$index
  $value = $hpups_array[$phase]['upsInputVoltage'];

  discover_sensor($valid['sensor'], 'voltage', $device, $oid, "upsInputEntry.$index", 'CPQPOWER-MIB', $descr, 1, $value);

  ## Input current
  $oid   = "1.3.6.1.4.1.232.165.3.3.4.1.3.$index"; # CPQPOWER-MIB:upsInputCurrent.$index
  $value = $hpups_array[$phase]['upsInputCurrent'];

  if ($value < 10000) # upsInputCurrent.1 = 136137420 ? really? You're nuts.
  {
    discover_sensor($valid['sensor'], 'current', $device, $oid, "upsInputEntry.$index", 'CPQPOWER-MIB', $descr, 1, $value);
  }

  ## Input power
  $oid   = "1.3.6.1.4.1.232.165.3.3.4.1.4.$index"; # CPQPOWER-MIB:upsInputWatts.$index
  $value = $hpups_array[$phase]['upsInputWatts'];
  discover_sensor($valid['sensor'], 'power', $device, $oid, "upsInputEntry.$index", 'CPQPOWER-MIB', $descr, 1, $value);

  # Output
  $index = $hpups_array[$phase]['upsOutputPhase'];
  $descr = 'Output'; if ($hpups_array[0]['upsOutputNumPhases'] > 1) { $descr .= " Phase $index"; }

  ## Output voltage
  $oid   = "1.3.6.1.4.1.232.165.3.4.4.1.2.$index"; # CPQPOWER-MIB:upsOutputVoltage.$index
  $value = $hpups_array[$phase]['upsOutputVoltage'];
  discover_sensor($valid['sensor'], 'voltage', $device, $oid, "upsOutputEntry.$index", 'CPQPOWER-MIB', $descr, 1, $value);

  ## Output current
  $oid   = "1.3.6.1.4.1.232.165.3.4.4.1.3.$index"; # CPQPOWER-MIB:upsOutputCurrent.$index
  $value = $hpups_array[$phase]['upsOutputCurrent'];
  discover_sensor($valid['sensor'], 'current', $device, $oid, "upsOutputEntry.$index", 'CPQPOWER-MIB', $descr, 1, $value);

  ## Output power
  $oid   = "1.3.6.1.4.1.232.165.3.4.4.1.4.$index"; # CPQPOWER-MIB:upsOutputWatts.$index
  $value = $hpups_array[$phase]['upsOutputWatts'];
  discover_sensor($valid['sensor'], 'power', $device, $oid, "upsOutputEntry.$index", 'CPQPOWER-MIB', $descr, 1, $value);

  ## Output Load
  $oid   = '1.3.6.1.4.1.232.165.3.4.1.0'; # CPQPOWER-MIB:upsOutputLoad.$index
  $descr = 'Output Load';
  $value = $hpups_array[$phase]['upsOutputLoad'];
  discover_sensor($valid['sensor'], 'capacity', $device, $oid, 'upsOutputLoad.0', 'CPQPOWER-MIB', $descr, 1, $value);

  # Bypass
  $index = $hpups_array[$phase]['upsBypassPhase'];
  $descr = 'Bypass'; if ($hpups_array[0]['upsBypassNumPhases'] > 1) { $descr .= " Phase $index"; }

  ## Bypass voltage
  $oid   = "1.3.6.1.4.1.232.165.3.5.3.1.2.$index"; # CPQPOWER-MIB:upsBypassVoltage.$index
  $value = $hpups_array[$phase]['upsBypassVoltage'];
  discover_sensor($valid['sensor'], 'voltage', $device, $oid, "upsBypassEntry.$index", 'CPQPOWER-MIB', $descr, 1, $value);
}

$scale = 0.1;

## Input frequency
$oid   = '1.3.6.1.4.1.232.165.3.3.1.0'; # CPQPOWER-MIB:upsInputFrequency.0
$value = $hpups_array[0]['upsInputFrequency'];
discover_sensor($valid['sensor'], 'frequency', $device, $oid, 'upsInputFrequency.0', 'CPQPOWER-MIB', 'Input', $scale, $value);

## Output Frequency
$oid   = '1.3.6.1.4.1.232.165.3.4.2.0'; # CPQPOWER-MIB:upsOutputFrequency.0
$value = $hpups_array[0]['upsOutputFrequency'];
discover_sensor($valid['sensor'], 'frequency', $device, $oid, 'upsOutputFrequency.0', 'CPQPOWER-MIB', 'Output', $scale, $value);

## Bypass Frequency
$oid   = '1.3.6.1.4.1.232.165.3.5.1.0'; # CPQPOWER-MIB:upsBypassFrequency.0
$value = $hpups_array[0]['upsBypassFrequency'];
discover_sensor($valid['sensor'], 'frequency', $device, $oid, 'upsBypassFrequency.0', 'CPQPOWER-MIB', 'Bypass', $scale, $value);

$hpups_array = array();
$hpups_array = snmpwalk_cache_multi_oid($device, 'upsBattery', $hpups_array, 'CPQPOWER-MIB');
$hpups_array = snmpwalk_cache_multi_oid($device, 'upsEnvironment', $hpups_array, 'CPQPOWER-MIB');

if (isset($hpups_array[0]['upsBatTimeRemaining']))
{
  $oid = '1.3.6.1.4.1.232.165.3.2.1.0'; # CPQPOWER-MIB:upsBatTimeRemaining.0
  $scale = 1/60;
  discover_sensor($valid['sensor'], 'runtime', $device, $oid, 'upsBatTimeRemaining.0', 'CPQPOWER-MIB', 'Battery Runtime Remaining', $scale, $hpups_array[0]['upsBatTimeRemaining']);
}

if (isset($hpups_array[0]['upsBatCapacity']))
{
  $oid = '1.3.6.1.4.1.232.165.3.2.4.0'; # CPQPOWER-MIB:upsBatCapacity.0
  discover_sensor($valid['sensor'], 'capacity', $device, $oid, 'upsBatCapacity.0', 'CPQPOWER-MIB', 'Battery Capacity', 1, $hpups_array[0]['upsBatCapacity']);
}

if (isset($hpups_array[0]['upsBatCurrent']))
{
  $oid = '1.3.6.1.4.1.232.165.3.2.3.0'; # CPQPOWER-MIB:upsBatCurrent.0

  discover_sensor($valid['sensor'], 'current', $device, $oid, 'upsBatCurrent.0', 'CPQPOWER-MIB', 'Battery', 1, $hpups_array[0]['upsBatCurrent']);
}

if (isset($hpups_array[0]['upsBatVoltage']))
{
  $oid = '1.3.6.1.4.1.232.165.3.2.2.0'; # CPQPOWER-MIB:upsBatVoltage.0

  discover_sensor($valid['sensor'], 'voltage', $device, $oid, 'upsBatVoltage.0', 'CPQPOWER-MIB', 'Battery', 1, $hpups_array[0]['upsBatVoltage']);
}

if (isset($hpups_array[0]['upsEnvAmbientTemp']))
{
  $oid  = '.1.3.6.1.4.1.232.165.3.6.1.0'; # CPQPOWER-MIB:upsEnvAmbientTemp.0

  $lowlimit = $hpups_array[0]['upsEnvAmbientLowerLimit'];
  $highlimit = $hpups_array[0]['upsEnvAmbientUpperLimit'];

  discover_sensor($valid['sensor'], 'temperature', $device, $oid, 'upsEnvAmbientTemp.0', 'CPQPOWER-MIB', 'Ambient', 1, $hpups_array[0]['upsEnvAmbientTemp']);
}

unset($hpups_array);

#Check for PDU mgmt module
//echo("Caching OIDs: ");
//echo("pduIdentTable ");
//CPQPOWER-MIB::pduIdentIndex.1 = INTEGER: 0
//CPQPOWER-MIB::pduIdentIndex.2 = INTEGER: 1
//CPQPOWER-MIB::pduName.1 = STRING: "PDU A"
//CPQPOWER-MIB::pduName.2 = STRING: "PDU B"
//CPQPOWER-MIB::pduStatus.1 = INTEGER: ok(2)
//CPQPOWER-MIB::pduStatus.2 = INTEGER: ok(2)
//CPQPOWER-MIB::pduOutputIndex.1 = INTEGER: 0
//CPQPOWER-MIB::pduOutputIndex.2 = INTEGER: 1
//CPQPOWER-MIB::pduOutputLoad.1 = INTEGER: 6
//CPQPOWER-MIB::pduOutputLoad.2 = INTEGER: 6
//CPQPOWER-MIB::pduOutputHeat.1 = INTEGER: 2302
//CPQPOWER-MIB::pduOutputHeat.2 = INTEGER: 2296
//CPQPOWER-MIB::pduOutputPower.1 = INTEGER: 673
//CPQPOWER-MIB::pduOutputPower.2 = INTEGER: 671
//CPQPOWER-MIB::pduOutputNumBreakers.1 = INTEGER: 3
//CPQPOWER-MIB::pduOutputNumBreakers.2 = INTEGER: 3
$hppdu_array = snmpwalk_cache_multi_oid($device, 'pduIdentTable',       array(), 'CPQPOWER-MIB');
$hppdu_array = snmpwalk_cache_multi_oid($device, 'pduOutputTable', $hppdu_array, 'CPQPOWER-MIB');
foreach ($hppdu_array as $index => $entry)
{
  // Monitor PDU Status
  $oid   = ".1.3.6.1.4.1.232.165.2.1.2.1.8.$index";
  $descr = $entry['pduName'].' Status';
  if (!empty($entry['pduStatus']))
  {
    discover_status($device, $oid, $index, 'cpqpower-pdu-status', $descr, $entry['pduStatus'], array('entPhysicalClass' => 'power'));
  }

  // Monitor PDU Output load
  $oid   = ".1.3.6.1.4.1.232.165.2.3.1.1.2.$index";
  $descr = $entry['pduName'].' Load';
  $limits = array();
  if (!empty($entry['pduOutputLoad']) && $entry['pduOutputLoad'] != '-1')
  {
    discover_sensor($valid['sensor'], 'capacity', $device, $oid, $index, 'CPQPOWER-MIB', $descr, 1, $entry['pduOutputLoad']);

    // Find powerlimit by measure the reported output power devivded by the reported load of the PDU
    $pdu_maxload = 100 * ($entry['pduOutputPower'] / $entry['pduOutputLoad']);
    $pdu_warnload = 0.8 * $pdu_maxload;
    $limits = array('limit_high'      => round($pdu_maxload, 2),
                    'limit_high_warn' => round($pdu_warnload, 2));
  }

  // Monitor PDU Power
  $oid   = ".1.3.6.1.4.1.232.165.2.3.1.1.4.$index";
  $descr = $entry['pduName'].' Output Power';

  if (!empty($entry['pduOutputPower']) && $entry['pduOutputPower'] != '-1')
  {
    discover_sensor($valid['sensor'], 'power', $device, $oid, $index, 'CPQPOWER-MIB', $descr, 1, $entry['pduOutputPower'], $limits);
  }
}

//CPQPOWER-MIB::breakerIndex.1.1 = INTEGER: 1
//CPQPOWER-MIB::breakerIndex.2.6 = INTEGER: 6
//CPQPOWER-MIB::breakerCurrent.1.1 = INTEGER: 1
//CPQPOWER-MIB::breakerCurrent.2.6 = INTEGER: 0
//CPQPOWER-MIB::breakerVoltage.1.1 = INTEGER: 230
//CPQPOWER-MIB::breakerVoltage.2.6 = INTEGER: 0
//CPQPOWER-MIB::breakerPercentLoad.1.1 = INTEGER: 9
//CPQPOWER-MIB::breakerPercentLoad.2.6 = INTEGER: 0
//CPQPOWER-MIB::breakerStatus.1.1 = INTEGER: 0
//CPQPOWER-MIB::breakerStatus.2.6 = INTEGER: 0
$hppdu_breaker_array = snmpwalk_cache_multi_oid($device, 'pduOutputBreakerTable', array(), 'CPQPOWER-MIB');
foreach ($hppdu_breaker_array as $index => $entry)
{
  if ($entry['breakerVoltage'] <= 0) { continue; }

  list($breaker_output, $breaker_unit) = explode('.', $index, 2);
  $breaker_descr = 'Breaker ' . $hppdu_array[$breaker_output]['pduName'] . ' Unit ' . $breaker_unit;

  // Find powerlimit by measure the reported output power devivded by the reported load of the PDU
  //$breaker_maxload = 100 * ($entry['breakerCurrent'] / $entry['breakerPercentLoad']);
  $breaker_maxload = $entry['breakerCurrent'] / $entry['breakerPercentLoad']; // breakerCurrent already scaled by 100
  $breaker_warnload = 0.8 * $breaker_maxload;
  $limits = array('limit_high'      => round($breaker_maxload, 2),
                  'limit_high_warn' => round($breaker_warnload, 2));
  $descr = $breaker_descr . ' Current';
  $oid = ".1.3.6.1.4.1.232.165.2.3.2.1.3.$index";
  discover_sensor($valid['sensor'], 'current', $device, $oid, $index, 'CPQPOWER-MIB', $descr, 0.01, $entry['breakerCurrent'], $limits);

  $descr = $breaker_descr . ' Voltage';
  $oid = ".1.3.6.1.4.1.232.165.2.3.2.1.2.$index";
  discover_sensor($valid['sensor'], 'voltage', $device, $oid, $index, 'CPQPOWER-MIB', $descr, 1, $entry['breakerVoltage']);

  $descr = $breaker_descr . ' Load';
  $oid = ".1.3.6.1.4.1.232.165.2.3.2.1.4.$index";
  discover_sensor($valid['sensor'], 'capacity', $device, $oid, $index, 'CPQPOWER-MIB', $descr, 1, $entry['breakerPercentLoad']);

  $descr = $breaker_descr . ' Status';
  $oid = ".1.3.6.1.4.1.232.165.2.3.2.1.5.$index";
  discover_status($device, $oid, $index, 'cpqpower-pdu-breaker-status', $descr, $entry['breakerStatus'], array('entPhysicalClass' => 'power'));
}

unset($hpups_array, $hppdu_breaker_array);

// EOF
