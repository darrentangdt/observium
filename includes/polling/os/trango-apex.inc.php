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

$version  = snmp_get($device, 'sysFWVer.0',    '-OQv', 'TRANGO-APEX-SYS-MIB');
$features = snmp_get($device, 'sysOSVer.0',    '-OQv', 'TRANGO-APEX-SYS-MIB');
$hardware = snmp_get($device, 'sysModel.0',    '-OQv', 'TRANGO-APEX-SYS-MIB');
$serial   = snmp_get($device, 'sysSerialID.0', '-OQv', 'TRANGO-APEX-SYS-MIB');

// EOF
