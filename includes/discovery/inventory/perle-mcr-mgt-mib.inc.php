<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

$vendor_mib="PERLE-MCR-MGT-MIB";
$mib_dirs = mib_dirs($config['mibs'][$vendor_mib]['mib_dir']);

$ChasProductType = snmp_get($device, 'chassisModelName.1', '-OQv', 'PERLE-MCR-MGT-MIB', $mib_dirs);

if ($ChasProductType)
{
  $ChasDesc = snmp_get($device, 'chassisModelDesc.1', '-OQv', 'PERLE-MCR-MGT-MIB', $mib_dirs);
  $ChasSerNum = snmp_get($device, 'chassisSerialNumber.1', '-OQv', 'PERLE-MCR-MGT-MIB', $mib_dirs);
  $ChasMgmtSlot = snmp_get($device, 'chassisCfgMgmtSlot.1', '-OQv', 'PERLE-MCR-MGT-MIB', $mib_dirs);
  
  // Insert chassis as index 1, everything hangs off of this.
  $system_index = 1;
  $inventory[$system_index] = array(
    'entPhysicalDescr'        => $ChasProductType,
    'entPhysicalClass'        => 'chassis',
    'entPhysicalName'         => $ChasDesc,
    'entPhysicalSerialNum'    => $ChasSerNum,
    'entPhysicalIsFRU'        => 'true',
    'entPhysicalContainedIn'  => 0,
    'entPhysicalParentRelPos' => -1,
    'entPhysicalMfgName'      => 'Perle'
  );

  discover_inventory($valid['inventory'], $device, $system_index, $inventory[$system_index], 'perle-mcr-mgt-mib');

  // Now fetch data for the rest of the hardware in the chassis
  $data = snmpwalk_cache_oid($device, 'mcrChassisSlotTable', array(), 'PERLE-MCR-MGT-MIB', $mib_dirs);
  $data_sfp = snmpwalk_cache_oid($device, 'mcrSfpDmiModuleTable', array(), 'PERLE-MCR-MGT-MIB', $mib_dirs);

  $relPos = 0;

  foreach ($data as $part)
  {
    $system_index = $part['mcrChassisSlotIndex'] * 256;
    $slotindex = $part['mcrChassisSlotIndex'];

    if ($system_index != 0)
    {
      $containedIn = 1; // Attach to chassis inserted above

      // snAgentBrdModuleStatus.6 = moduleRunning
      // snAgentBrdModuleStatus.7 = moduleEmpty
      if ($part['mcrModuleModelName'] != '') 
      {
        $relPos++;

        $inventory[$system_index] = array(
          'entPhysicalDescr'        => $part['mcrUserDefinedModuleName'] . "(".$part['mcrModuleModelDesc'].")",
          'entPhysicalClass'        => 'module',
          'entPhysicalName'         => $part['mcrModuleModelName'],
          'entPhysicalSerialNum'    => $part['mcrModuleSerialNumber'],
          'entPhysicalIsFRU'        => 'true',
          'entPhysicalContainedIn'  => $containedIn,
          'entPhysicalParentRelPos' => $relPos,
          'entPhysicalFirmwareRev'  => $part['mcrModuleBootloaderVersion'],
          'entPhysicalSoftwareRev'  => $part['mcrModuleFirmwareVersion'],
          'entPhysicalMfgName'      => 'Perle',
        );

        discover_inventory($valid['inventory'], $device, $system_index, $inventory[$system_index], 'perle-mcr-mgt-mib');
      }
      
      foreach ($data_sfp as $part_sfp)
      {
      
        if ($part_sfp['sfpDmiSlotIndex'] == $slotindex) {
          $system_index_sfp = $part_sfp['sfpDmiSlotIndex'] * 256 + 1;
          
          $relPos++;
          if ($part_sfp['sfpDmiLinkReach625125'] != 0) { $range = $part_sfp['sfpDmiLinkReach625125']."m"; }
          if ($part_sfp['sfpDmiLinkReach50125'] != 0) { $range = $part_sfp['sfpDmiLinkReach50125']."m"; }
          if ($part_sfp['sfpDmiLinkReach9125'] != 0) { $range = ($part_sfp['sfpDmiLinkReach9125']/1000)."km"; }
          
          $inventory[$system_index] = array(
          'entPhysicalDescr'        => $part_sfp['sfpDmiVendorName'] . " SFP (".$part_sfp['sfpDmiFiberWaveLength']."nm ".$range." ".$part_sfp['sfpDmiNominalBitRate']."Mbps)",
          'entPhysicalClass'        => 'module',
          'entPhysicalName'         => $part_sfp['sfpDmiVendorPartNumber'],
          'entPhysicalSerialNum'    => $part_sfp['sfpDmiVendorSerialNumber'],
          'entPhysicalIsFRU'        => 'true',
          'entPhysicalContainedIn'  => $system_index,
          'entPhysicalParentRelPos' => $relPos,
          'entPhysicalMfgName'      => $part_sfp['sfpDmiVendorName'],
        );

        discover_inventory($valid['inventory'], $device, $system_index_sfp, $inventory[$system_index], 'perle-mcr-mgt-mib');
          
        }
      }
    }
  }
}

// EOF
