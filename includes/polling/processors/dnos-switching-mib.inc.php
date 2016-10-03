<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage poller
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

// DNOS-SWITCHING-MIB::agentSwitchCpuProcessTotalUtilization.0 = STRING: "    5 Secs (  6.510%)   60 Secs (  7.724%)  300 Secs (  6.3812%)"

$data = snmp_get($device, 'agentSwitchCpuProcessTotalUtilization.0', '-OvQ', 'DNOS-SWITCHING-MIB');

if (preg_match('/300 Secs \(\s*(?<proc>[\d\.]+)%\)/', $data, $matches))
{
  $proc = $matches['proc'];
}

unset($data, $matches);

// EOF
