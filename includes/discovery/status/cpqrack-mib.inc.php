<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

echo(" CPQRACK-MIB ");

if (!isset($cache_discovery['cpqrack-mib']))
{
  $cache_discovery['cpqrack-mib'] = snmpwalk_cache_oid($device, 'cpqRackServerBladeTable', NULL, 'CPQRACK-MIB');
}

foreach ($cache_discovery['cpqrack-mib'] as $tmp => $entry)
{
  if ($entry['cpqRackServerBladeEntry'] == "0") { continue; }
  if ($entry['cpqRackServerBladeSlotsUsed'] == "0") { continue; }

  $index = $entry['cpqRackServerBladePosition'];

  $oid  = '.1.3.6.1.4.1.232.22.2.4.1.1.1.21.' . $index;
  $oidn = 'cpqRackServerBladeStatus.' . $index;
  $name = 'Slot '. $index . ' (' . $entry['cpqRackServerBladeName'] . ')';

  $slots[$entry['cpqRackServerBladePosition']] = array( 'name' => $name, 'state' => $entry['cpqRackServerBladeStatus'], 'oid' => $oid, 'oidn' => $oidn );
}

foreach ($slots as $number => $entry)
{
  discover_status($device, $entry['oid'], $entry['oidn'], 'cpqrack-mib-slot-state', $entry['name'], $entry['state'], array('entPhysicalClass' => 'other'));
}

// EOF
