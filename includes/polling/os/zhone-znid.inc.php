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

$hardware = snmp_get($device, 'modelNumber.0', '-Osqv', 'ZHNSYSTEM');
// $serial   = snmp_get($device, '', '-Osqv', 'ZHNSYSTEM');
$version = snmp_get($device, 'sysFirmwareVersion.0', '-Osqv', 'ZHNSYSTEM');
// $features = snmp_get($device, '', '-Osqv', 'ZHNSYSTEM');

// EOF
