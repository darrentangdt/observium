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

// table: CMC power information
$oids = snmpwalk_cache_oid($device, 'drsCMCPowerTable', array(), $mib);

foreach ($oids as $index => $entry)
{
  $descr = "Chassis ".$entry['drsChassisIndex'];
  $oid = ".1.3.6.1.4.1.674.10892.2.4.1.1.14.$index";
  discover_sensor($valid['sensor'], 'current', $device, $oid, $index, 'dell-rac', $descr, 1, $entry['drsAmpsReading']);

  $limits = array('limit_high' => $entry['drsMaxPowerSpecification']);
  $oid = ".1.3.6.1.4.1.674.10892.2.4.1.1.13.$index";
  discover_sensor($valid['sensor'], 'power', $device, $oid, $index, 'dell-rac', $descr, 1, $entry['drsWattsReading'], $limits);
}

unset($oids);

// table: CMC PSU info
$oids = snmpwalk_cache_oid($device, 'drsCMCPSUTable', array(), $mib);

foreach ($oids as $index => $entry)
{
  $descr = 'Chassis '.$entry['drsPSUChassisIndex'].' '.$entry['drsPSULocation'];
  $oid = ".1.3.6.1.4.1.674.10892.2.4.2.1.6.$index";
  discover_sensor($valid['sensor'], 'current', $device, $oid, $index, 'dell-rac', $descr, 1, $entry['drsPSUAmpsReading']);

  $oid = ".1.3.6.1.4.1.674.10892.2.4.2.1.5.$index";
  $limits = array();

  ## FIXME this type of inventing/calculating should be done in the Observium voltage function instead!
  if ($entry['drsPSUVoltsReading'] > 360 and $entry['drsPSUVoltsReading'] < 440)
  {
    // european 400V +/- 10%
    $limits = array('limit_high' => 440, 'limit_low' => 360);
  }
  if ($entry['drsPSUVoltsReading'] > 207 and $entry['drsPSUVoltsReading'] < 253)
  {
    // european 230V +/- 10%
    $limits = array('limit_high' => 253, 'limit_low' => 207);
  }
  if ($entry['drsPSUVoltsReading'] > 99 and $entry['drsPSUVoltsReading'] < 121)
  {
    // american 110V +/- 10%
    $limits = array('limit_high' => 99,  'limit_low' => 121);
  }

  discover_sensor($valid['sensor'], 'voltage', $device, $oid, $index, 'dell-rac', $descr, 1, $entry['drsPSUVoltsReading'], $limits);
}

// EOF
