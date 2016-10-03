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

// SYNOLOGY-SYSTEM-MIB::modelName.0 = STRING: "RT1900ac"
// SYNOLOGY-SYSTEM-MIB::serialNumber.0 = STRING: "13A0LNN000123"
// SYNOLOGY-SYSTEM-MIB::version.0 = STRING: "DSM 5.0-4458"

$hardware = snmp_get($device, 'modelName.0',    '-OQv', 'SYNOLOGY-SYSTEM-MIB');
//$version  = snmp_get($device, 'version.0',      '-OQv', 'SYNOLOGY-SYSTEM-MIB'); // Broken, returns some DSM version
$serial   = snmp_get($device, 'serialNumber.0', '-OQv', 'SYNOLOGY-SYSTEM-MIB');

//$version = str_replace('DSM', '', $version);

// EOF
