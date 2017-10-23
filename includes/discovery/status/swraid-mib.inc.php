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

//SWRAID-MIB::swRaidIndex.1 = INTEGER: 1
//SWRAID-MIB::swRaidIndex.2 = INTEGER: 2
//SWRAID-MIB::swRaidDevice.1 = STRING: md2
//SWRAID-MIB::swRaidDevice.2 = STRING: md1
//SWRAID-MIB::swRaidPersonality.1 = STRING: raid1
//SWRAID-MIB::swRaidPersonality.2 = STRING: raid1
//SWRAID-MIB::swRaidUnits.1 = STRING: sdb2[1] sda2[0]
//SWRAID-MIB::swRaidUnits.2 = STRING: sdb1[1] sda1[0]
//SWRAID-MIB::swRaidUnitCount.1 = INTEGER: 2
//SWRAID-MIB::swRaidUnitCount.2 = INTEGER: 2
//SWRAID-MIB::swRaidStatus.1 = INTEGER: active(2)
//SWRAID-MIB::swRaidStatus.2 = INTEGER: active(2)
//SWRAID-MIB::swRaidErrorFlag.0 = INTEGER: 0
//SWRAID-MIB::swRaidErrMessage.0 = STRING:

$raids = snmpwalk_cache_multi_oid($device, 'swRaidTable', array(), $mib);

foreach ($raids as $index => $raid)
{

  $value = $raid['swRaidStatus'];
  $oid   = '.1.3.6.1.4.1.2021.13.18.1.1.6.'.$index;
  $descr = $raid['swRaidDevice'] . ' (' . $raid['swRaidPersonality'].')';

  discover_status($device, $oid, $index, 'swRaidStatus', $descr, $value, array('entPhysicalClass'=>'raid'));

  print_vars(array($device, $oid, $index, 'swRaidStatus', $descr, $value, array('entPhysicalClass'=>'raid')));

}

