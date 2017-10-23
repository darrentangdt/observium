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

$data = snmp_get_multi($device, 'alHardwareChassis.0', '-OQUs', 'ALTIGA-HARDWARE-STATS-MIB');
if (isset($data[0]))
{
  $hardware = strtoupper($data[0]['alHardwareChassis']);
} else {
  $serial   = snmp_get($device, 'entPhysicalSerialNum.1', '-OQv', 'ENTITY-MIB');
}

// EOF
