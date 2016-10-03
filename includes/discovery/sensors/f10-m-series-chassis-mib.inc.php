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

// Force10 M-Series

$oids = snmpwalk_cache_oid($device, 'chStackUnitTemp', array(), 'F10-M-SERIES-CHASSIS-MIB');
$oids = snmpwalk_cache_oid($device, 'chStackUnitSysType', $oids, 'F10-M-SERIES-CHASSIS-MIB');

foreach ($oids as $index => $entry)
{
  $descr = 'Unit ' . strval($index - 1) . ' ' . $entry['chStackUnitSysType'];
  $oid   = ".1.3.6.1.4.1.6027.3.19.1.2.1.1.14.$index";
  $value = $entry['chStackUnitTemp'];

  discover_sensor($valid['sensor'], 'temperature', $device, $oid, $index, 'F10-M-SERIES-CHASSIS-MIB', $descr, 1, $value);
}

unset($oids, $oid, $descr, $value);

// EOF
