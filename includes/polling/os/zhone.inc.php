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

//ZHONE-CARD-RESOURCES-MIB::cardIdentification.1.1 = STRING: zhone-mxk-198-10GE
//ZHONE-CARD-RESOURCES-MIB::cardZhoneType.1.1 = INTEGER: mx1U19xFamily(10500)
//ZHONE-CARD-RESOURCES-MIB::cardMfgSerialNumber.1.1 = STRING: 0000000000011568280
//ZHONE-CARD-RESOURCES-MIB::cardMfgCLEICode.1.1 = STRING: No CLEI
//ZHONE-CARD-RESOURCES-MIB::cardMfgRevisionCode.1.1 = STRING: Unknown.
//ZHONE-CARD-RESOURCES-MIB::cardMfgBootRevision.1.1 = STRING: MX 2.2.1.211
//ZHONE-CARD-RESOURCES-MIB::cardUpTime.1.1 = Timeticks: (244202581) 28 days, 6:20:25.81
//ZHONE-CARD-RESOURCES-MIB::cardInterfaceType.1.1 = INTEGER: mx1U198-10GE(10504)
//ZHONE-CARD-RESOURCES-MIB::cardSwRunningVers.1.1 = STRING: MX 2.4.1.209

$hardware = snmp_get($device, 'cardIdentification.1.1', '-Osqv', 'ZHONE-CARD-RESOURCES-MIB');
$serial   = snmp_get($device, 'cardMfgSerialNumber.1.1', '-Osqv', 'ZHONE-CARD-RESOURCES-MIB');
$serial   = ltrim($serial, '0');
$version  = snmp_get($device, 'cardSwRunningVers.1.1',   '-Osqv', 'ZHONE-CARD-RESOURCES-MIB');
// $features = snmp_get($device, '', '-Osqv', 'ZHONE-CARD-RESOURCES-MIB');

// EOF
