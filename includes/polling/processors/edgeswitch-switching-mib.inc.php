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

// EdgeSwitch-SWITCHING-MIB::agentSwitchCpuProcessTotalUtilization.0 = STRING: "    5 Secs ( 99.9999%)   60 Secs ( 99.6358%)  300 Secs ( 99.2401%)"

$data = snmp_get($device, 'agentSwitchCpuProcessTotalUtilization.0', '-OvQ', 'EdgeSwitch-SWITCHING-MIB');

if (preg_match('/300 Secs \(\s*(?<proc>[\d\.]+)%\)/', $data, $matches))
{
  $proc = $matches['proc'];
}

unset($data, $matches);

// EOF
