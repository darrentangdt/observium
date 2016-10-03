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

$version  = trim($poll_device['sysDescr'], 'Data Domain OS'); // FIXME you know this just trims all D, a, t o, m, i, n, O and S'es from a string, right?
$serial   = snmp_get($device, 'systemSerialNumber.0', '-OQv', 'DATA-DOMAIN-MIB');
$hardware = snmp_get($device, 'systemModelNumber.0', '-OQv', 'DATA-DOMAIN-MIB');

// EOF
