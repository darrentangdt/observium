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

// SNMPv2-SMI::enterprises.2620.1.6.4.1.0 = STRING: "R76"

$version  = snmp_get($device, 'svnVersion.0', '-OQv', 'CHECKPOINT-MIB');
$hardware = snmp_get($device, 'svnApplianceProductName.0', '-OQv', 'CHECKPOINT-MIB');
$serial   = snmp_get($device, 'svnApplianceSerialNumber.0', '-OQv', 'CHECKPOINT-MIB');
$features = snmp_get($device, 'haState.0', '-OQv', 'CHECKPOINT-MIB');

if (empty($hardware)) // Fallback since svnApplianceProductName is only supported since R77.10
{
  $hardware = rewrite_unix_hardware($poll_device['sysDescr']);
}

// EOF
