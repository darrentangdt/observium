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

// RBN-MEMORY-MIB::rbnMemoryKBytesInUse.1
// RBN-MEMORY-MIB::rbnMemoryFreeKBytes.1

// FIXME should be oid names?
$used = snmp_get($device, '.1.3.6.1.4.1.2352.2.16.1.2.1.4.1', '-OvQ', $mib);
$free = snmp_get($device, '.1.3.6.1.4.1.2352.2.16.1.2.1.3.1', '-OvQ', $mib);

if (is_numeric($free) && is_numeric($used))
{
  $precision = 1024;
  $total     = $used + $free;
  //$total    *= $precision;
  //$used     *= $precision;
  discover_mempool($valid['mempool'], $device, 0, 'RBN-MEMORY-MIB', 'Memory', $precision, $total, $used); // Here wrong index, should be '1'
}

unset($precision, $total, $used, $free);

// EOF
