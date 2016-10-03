<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage poller
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2015 Observium Limited
 *
 */

if ($entPhysical['entPhysicalDescr'] && $entPhysical['entPhysicalName'] && $entPhysical['entPhysicalSoftwareRev'])
{
  $hardware = $entPhysical['entPhysicalModelName']; # . ' ' . $entPhysical['entPhysicalName'];
  $version = $entPhysical['entPhysicalSoftwareRev'];
  $serial   = $entPhysical['entPhysicalSerialNum'];
  return;
}


preg_match('/^HP Comware Platform Software (.*). Product Version Release ([0-9P]+)/', $poll_device['sysDescr'], $matches);
$hardware = "HP ".$matches[1];
$version = $matches[2];

$serial = snmp_get($device, "hpnicfEntityExtManuSerialNum.1", "-Oqv", "HPN-ICF-ENTITY-EXT-MIB", mib_dirs('hp'));

// EOF
