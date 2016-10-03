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

// "Juniper Networks, Inc WLC880R 8.0.3.15 REL"
list(,,,$hardware,$version,) = explode(' ', $poll_device['sysDescr']);

// TRAPEZE-NETWORK-ROOT-MIB::trpzSerialNumber.0 = STRING: "JJ01234567"
$serial = snmp_get($device, ':trpzSerialNumber.0', '-OQv', 'TRAPEZE-NETWORK-ROOT-MIB');

// TRAPEZE-NETWORK-ROOT-MIB::trpzVersionString.0 = STRING: "8.0.3.15.0"
$version = snmp_get($device, 'trpzVersionString.0', '-OQv', 'TRAPEZE-NETWORK-ROOT-MIB');

$domain = snmp_get($device, '.1.3.6.1.4.1.14525.4.2.2.1.0', '-OQv', 'TRAPEZE-NETWORK-ROOT-MIB');

if ($domain)
{
  $features = "Cluster: $domain";
}

// EOF
