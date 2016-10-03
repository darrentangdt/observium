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

// CPU Memory
//
// agentSwitchCpuProcessMemFree.0 = INTEGER: 343320
// agentSwitchCpuProcessMemAvailable.0 = INTEGER: 1034740

$free  = snmp_get($device, 'agentSwitchCpuProcessMemFree.0',      '-OUvq', $mib);
$total = snmp_get($device, 'agentSwitchCpuProcessMemAvailable.0', '-OUvq', $mib);
$used  = $total - $free;

if (is_numeric($free))
{
  rename_rrd($device, 'mempool-dell-vendor-mib-0', 'mempool-dnos-switching-mib-0.rrd');
  discover_mempool($valid['mempool'], $device, 0, $mib, 'System Memory', 1024, $total, $used);
}

unset ($total, $used, $free);

// EOF
