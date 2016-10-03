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

//EdgeSwitch-SWITCHING-MIB::agentSwitchCpuProcessMemFree.0 = INTEGER: 163812 KBytes
//EdgeSwitch-SWITCHING-MIB::agentSwitchCpuProcessMemAvailable.0 = INTEGER: 256608 KBytes

$data = snmpget_cache_multi($device, 'agentSwitchCpuProcessMemFree.0 agentSwitchCpuProcessMemAvailable.0', array(), 'EdgeSwitch-SWITCHING-MIB');

$descr  = 'Memory';
$free   = $data[0]['agentSwitchCpuProcessMemFree'];
$total  = $data[0]['agentSwitchCpuProcessMemAvailable'];

if (is_numeric($free) && is_numeric($total))
{
  $used = $total - $free;
  discover_mempool($valid['mempool'], $device, 0, 'EdgeSwitch-SWITCHING-MIB', $descr, 1024, $total, $used);
}

unset ($data, $descr, $total, $used, $free);

// EOF
