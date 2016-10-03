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

// Note: MIBs for this are troublesome, since they are modified Broadcom reference MIBs and overlap with Dell, Netgear and others.

/*
EdgeSwitch-SWITCHING-MIB::agentInventorySysDescription.0 = STRING: "EdgeSwitch 24-Port 250W, 1.1.2.4767216, Linux 3.6.5-f4a26ed5"
EdgeSwitch-SWITCHING-MIB::agentInventoryMachineType.0 = STRING: "EdgeSwitch 24-Port 250W"
EdgeSwitch-SWITCHING-MIB::agentInventoryMachineModel.0 = STRING: "ES-24-250W"
EdgeSwitch-SWITCHING-MIB::agentInventorySerialNumber.0 = STRING: "44D9E70524D9"
EdgeSwitch-SWITCHING-MIB::agentInventoryMaintenanceLevel.0 = STRING: "A"
EdgeSwitch-SWITCHING-MIB::agentInventoryPartNumber.0 = STRING: "BCM53344"
EdgeSwitch-SWITCHING-MIB::agentInventoryManufacturer.0 = STRING: "0xbc00"
EdgeSwitch-SWITCHING-MIB::agentInventoryBurnedInMacAddress.0 = Hex-STRING: 44 D9 E7 05 24 D9
EdgeSwitch-SWITCHING-MIB::agentInventoryOperatingSystem.0 = STRING: "Linux 3.6.5-f4a26ed5"
EdgeSwitch-SWITCHING-MIB::agentInventoryNetworkProcessingDevice.0 = STRING: "BCM53344_A0"
EdgeSwitch-SWITCHING-MIB::agentInventoryAdditionalPackages.0 = STRING: " QOS"
EdgeSwitch-SWITCHING-MIB::agentInventorySoftwareVersion.0 = STRING: "1.1.2.4767216"

EdgeSwitch-SWITCHING-MIB::agentInventorySysDescription.0 = STRING: "USW-48P-500, 3.3.5.3734, Linux 3.6.5"
EdgeSwitch-SWITCHING-MIB::agentInventoryMachineType.0 = STRING: "USW-48P-500"
EdgeSwitch-SWITCHING-MIB::agentInventoryMachineModel.0 = STRING: "US48P500"
EdgeSwitch-SWITCHING-MIB::agentInventorySerialNumber.0 = STRING: "0418d6f0f928"
EdgeSwitch-SWITCHING-MIB::agentInventoryMaintenanceLevel.0 = STRING: "A"
EdgeSwitch-SWITCHING-MIB::agentInventoryPartNumber.0 = STRING: "BCM53344"
EdgeSwitch-SWITCHING-MIB::agentInventoryManufacturer.0 = STRING: "0xbc00"
EdgeSwitch-SWITCHING-MIB::agentInventoryBurnedInMacAddress.0 = Hex-STRING: 04 18 D6 F0 F9 28
EdgeSwitch-SWITCHING-MIB::agentInventoryOperatingSystem.0 = STRING: "Linux 3.6.5"
EdgeSwitch-SWITCHING-MIB::agentInventoryNetworkProcessingDevice.0 = STRING: "BCM53344_A0"
EdgeSwitch-SWITCHING-MIB::agentInventoryAdditionalPackages.0 = STRING: " QOS"
EdgeSwitch-SWITCHING-MIB::agentInventorySoftwareVersion.0 = STRING: "3.3.5.3734"
*/

$data = snmpget_cache_multi($device, 'agentInventoryMachineType.0 agentInventorySerialNumber.0 agentInventoryAdditionalPackages.0 agentInventorySoftwareVersion.0', array(), 'EdgeSwitch-SWITCHING-MIB');
if (is_array($data[0]))
{
  $hardware = $data[0]['agentInventoryMachineType'];
  $version  = $data[0]['agentInventorySoftwareVersion'];
  $serial   = $data[0]['agentInventorySerialNumber'];
  $features = trim($data[0]['agentInventoryAdditionalPackages']);
}

// EOF
