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

// GBNPlatformOAM-MIB::memorySize.0 = INTEGER: 128
// GBNPlatformOAM-MIB::memoryIdle.0 = INTEGER: 51

$descr  = 'Memory';
$free   = snmp_get($device, 'memoryIdle.0', '-OQUvs', $mib);
$total  = snmp_get($device, 'memorySize.0', '-OQUvs', $mib);

if (is_numeric($free) && is_numeric($total))
{
  //$total *= 1024*1024;
  //$free  *= 1024*1024;
  $used   = $total - $free;

  discover_mempool($valid['mempool'], $device, 0, strtolower('GBNPlatformOAM-MIB'), $descr, 1024*1024, $total, $used);
  // ^ FIXME why strtolower?
}

unset ($descr, $total, $used);

// EOF
