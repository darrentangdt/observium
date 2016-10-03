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

/*
FASTPATH-SWITCHING-MIB::agentInventorySysDescription.0 = STRING: "Quanta LB6M, 1.2.0.18, Linux 2.6.21.7"
FASTPATH-SWITCHING-MIB::agentInventoryMachineType.0 = STRING: "Quanta LB6M"
FASTPATH-SWITCHING-MIB::agentInventoryMachineModel.0 = STRING: "LB6M"
FASTPATH-SWITCHING-MIB::agentInventorySerialNumber.0 = STRING: "QTFCRW3390510"
FASTPATH-SWITCHING-MIB::agentInventoryFRUNumber.0 = STRING: "1LB6BZZ0STJ"
FASTPATH-SWITCHING-MIB::agentInventoryMaintenanceLevel.0 = STRING: "A"
FASTPATH-SWITCHING-MIB::agentInventoryPartNumber.0 = STRING: "BCM56820"
FASTPATH-SWITCHING-MIB::agentInventoryManufacturer.0 = STRING: "0xbc00"
FASTPATH-SWITCHING-MIB::agentInventoryBurnedInMacAddress.0 = Hex-STRING: 08 9E 01 EA B7 27
FASTPATH-SWITCHING-MIB::agentInventoryOperatingSystem.0 = STRING: "Linux 2.6.21.7"
FASTPATH-SWITCHING-MIB::agentInventoryNetworkProcessingDevice.0 = STRING: "BCM56820_B0"
FASTPATH-SWITCHING-MIB::agentInventoryAdditionalPackages.0 = STRING: "QOS"
FASTPATH-SWITCHING-MIB::agentInventorySoftwareVersion.0 = STRING: "1.2.0.18"

FASTPATH-SWITCHING-MIB::agentInventorySysDescription.0 = STRING: "FASTPATH Switching"
FASTPATH-SWITCHING-MIB::agentInventoryMachineType.0 = STRING: "LB4G 48x1G 2x10G"
FASTPATH-SWITCHING-MIB::agentInventoryMachineModel.0 = STRING: "LB4M"
FASTPATH-SWITCHING-MIB::agentInventorySerialNumber.0 = STRING: "QTFCPW0250127"
FASTPATH-SWITCHING-MIB::agentInventoryFRUNumber.0 = STRING: "1"
FASTPATH-SWITCHING-MIB::agentInventoryMaintenanceLevel.0 = STRING: "A"
FASTPATH-SWITCHING-MIB::agentInventoryPartNumber.0 = STRING: "BCM56514"
FASTPATH-SWITCHING-MIB::agentInventoryManufacturer.0 = STRING: "0xbc00"
FASTPATH-SWITCHING-MIB::agentInventoryBurnedInMacAddress.0 = Hex-STRING: C8 0A A9 9E 59 E9
FASTPATH-SWITCHING-MIB::agentInventoryOperatingSystem.0 = STRING: "VxWorks5.5.1"
FASTPATH-SWITCHING-MIB::agentInventoryNetworkProcessingDevice.0 = STRING: "BCM56514_A0"
FASTPATH-SWITCHING-MIB::agentInventoryAdditionalPackages.0 = STRING: "QOS"
FASTPATH-SWITCHING-MIB::agentInventorySoftwareVersion.0 = STRING: "1.0.2.14"
*/

$data = snmpget_cache_multi($device, 'agentInventoryMachineType.0 agentInventorySerialNumber.0 agentInventoryAdditionalPackages.0 agentInventorySoftwareVersion.0', array(), 'FASTPATH-SWITCHING-MIB');
if (is_array($data[0]))
{
  $hardware = $data[0]['agentInventoryMachineType'];
  $version  = $data[0]['agentInventorySoftwareVersion'];
  $serial   = $data[0]['agentInventorySerialNumber'];
  $features = trim($data[0]['agentInventoryAdditionalPackages']);
}

// EOF
