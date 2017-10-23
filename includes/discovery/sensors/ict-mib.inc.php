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

$mib = 'ICT-MIB';

$oids     = snmpwalk_cache_oid($device, 'outputEntry', array(), $mib);

foreach ($oids as $index => $entry)
{
  if ($entry['outputEnable'] == 'DISABLED') { continue; }

  $descr = "Output " . (int)$entry['outputNumber'] + 1;
  if ($entry['outputName'] && $entry['outputName'] != '00')
  {
    $descr .= ': ' . $entry['outputName'];
  }

  // Output Current
  $oid_name   = 'outputCurrent';
  $oid        = '.1.3.6.1.4.1.39145.10.8.1.3.'.$index;
  $type       = $mib . '-' . $oid_name;
  $value      = $entry[$oid_name];

  discover_sensor($valid['sensor'], 'current', $device, $oid, $index, $type, $descr, 0.1, $value);
}

// EOF
