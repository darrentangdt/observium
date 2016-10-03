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

$descr  = 'Memory';
$free   = snmp_get($device, 'performanceDynMemFree.0',  '-OQUvs', $mib);
$total  = snmp_get($device, 'performanceDynMemTotal.0', '-OQUvs', $mib);

if (is_numeric($free) && is_numeric($total))
{
  $used = $total - $free;
  discover_mempool($valid['mempool'], $device, 0, 'AETHRA-MIB', $descr, 1024, $total, $used);
}

unset ($descr, $total, $used, $free);

// EOF
