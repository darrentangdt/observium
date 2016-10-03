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

// TERADICI-PCOIPv2-MIB::pcoipGenDevicesSessionNumber.1 = INTEGER: 1
// TERADICI-PCOIPv2-MIB::pcoipGenDevicesName.1 = STRING: "pcoip-portal-0012fbeb931e"
// TERADICI-PCOIPv2-MIB::pcoipGenDevicesDescription.1 = ""
// TERADICI-PCOIPv2-MIB::pcoipGenDevicesGenericTag.1 = ""
// TERADICI-PCOIPv2-MIB::pcoipGenDevicesPartNumber.1 = STRING: "TERA1100 revision 1.0 (128 MB)"
// TERADICI-PCOIPv2-MIB::pcoipGenDevicesFwPartNumber.1 = STRING: "Samsung 22 Rev 2 Display"
// TERADICI-PCOIPv2-MIB::pcoipGenDevicesSerialNumber.1 = ""
// TERADICI-PCOIPv2-MIB::pcoipGenDevicesHardwareVersion.1 = ""
// TERADICI-PCOIPv2-MIB::pcoipGenDevicesFirmwareVersion.1 = STRING: "4.0.2"
// TERADICI-PCOIPv2-MIB::pcoipGenDevicesUniqueID.1 = STRING: "00-12-FB-EB-93-1E"
// TERADICI-PCOIPv2-MIB::pcoipGenDevicesMAC.1 = STRING: "00-12-FB-EB-93-1E"
// TERADICI-PCOIPv2-MIB::pcoipGenDevicesUptime.1 = Counter64: 18813

$version  = snmp_get($device, 'pcoipGenDevicesFirmwareVersion.1', '-Osqv', 'TERADICI-PCOIPv2-MIB');
$hardware = snmp_get($device, 'pcoipGenDevicesPartNumber.1', '-Osqv', 'TERADICI-PCOIPv2-MIB');
$serial   = snmp_get($device, 'pcoipGenDevicesSerialNumber.1', '-Osqv', 'TERADICI-PCOIPv2-MIB');
$features = snmp_get($device, 'pcoipGenDevicesFwPartNumber.1', '-Osqv', 'TERADICI-PCOIPv2-MIB');

// EOF
