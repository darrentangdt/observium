<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage poller
 * @copyright  (C) 2006-2014 Adam Armstrong
 *
 */

$manufacturer = trim(snmp_get($device, '.1.3.6.1.4.1.232.165.3.1.1.0', '-OQv'),'" ');
$hardware     = trim(snmp_get($device, '.1.3.6.1.4.1.232.165.3.1.2.0', '-OQv'),'" ');
$version      = trim(snmp_get($device, '.1.3.6.1.4.1.232.165.3.1.3.0', '-OQv'),'" '); 
$serial       = trim(snmp_get($device, '.1.3.6.1.4.1.232.165.1.2.7.0', '-OQv'),'" '); 
 
// EOF
