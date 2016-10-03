<?php /**
 * Observium
 *
 * This file is part of Observium.
 *
 * @package observium
 * @subpackage poller
 * @copyright (C) 2006-2015 Adam Armstrong
 *
 */

# PowerWalker/BlueWalker UPS (Tested with BlueWalked VFI 2000 LCD (EPPC-MIB) sysDescr.0 = STRING: Network Management Card for UPS

if ($poll_device['sysObjectID'] == ".1.3.6.1.4.1.935.10.1") // BlueWalker
{
  if ($poll_device['sysDescr'] == "Network Management Card for UPS")
  {
    //var_dump($poll_device);
    //$poll_device['sysDescr'] == "BlueWalker Network Management Card";
    $hardware = trim(snmp_get($device, "upsEIdentityManufacturer.0", "-OQv", "EPPC-MIB", mib_dirs('eppc')), '" ')." (".
                trim(snmp_get($device, "upsESystemConfigOutputVA.0", "-OQv", "EPPC-MIB",mib_dirs('eppc')), '" ')."VA ".trim(snmp_get($device, "upsEIdentityModel.0", "-OQv", "EPPC-MIB", mib_dirs('eppc')), '" ').")";
    $upsEIdentityDescription = trim(snmp_get($device, "upsEIdentityDescription.0", "-OQv", "EPPC-MIB", mib_dirs('eppc')), '" ');
    $version = $upsEIdentityDescription + " UPS: ".
               trim(snmp_get($device, "upsEIdentityUPSFirmwareVerison.0", "-OQv", "EPPC-MIB", mib_dirs('eppc')), '" ')." Firmware: ".trim(snmp_get($device, "upsIdentAgentSoftwareVersion.0", "-OQv", "EPPC-MIB", mib_dirs('eppc')), '" ');
    $status = trim(snmp_get($device, "upsESystemStatus.0", "-OQv", "EPPC-MIB", mib_dirs('eppc')), '" ')." ".
    trim(snmp_get($device, "upsEBatteryTestResult.0", "-OQv", "EPPC-MIB", mib_dirs('eppc')), '" ');

    $features = "Status: ".strtoupper($status);
    $serial = trim(snmp_get($device, "upsEIndentityUPSSerialNumber.0", "-OQv", "EPPC-MIB", mib_dirs('eppc')), '" ');
  }
  else {
    $hardware = "EPPC - Unknown NMC Card";
  }
}
// EOF
