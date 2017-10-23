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

$stack_members = snmpwalk_cache_multi_oid($device, 'hh3cStackBoardConfigTable', array(), $mib);

foreach ($stack_members as $index => $entry)
{
  // HH3C-STACK-MIB::hh3cStackBoardRole.112 = INTEGER: slave(1)
  // HH3C-STACK-MIB::hh3cStackBoardBelongtoMember.112 = INTEGER: 1

  $value = $entry['hh3cStackBoardRole'];
  $oid   = ".1.3.6.1.4.1.25506.2.91.3.1.1.$index";
  $descr = 'Board ' . $entry['hh3cStackBoardBelongtoMember'];

  discover_status($device, $oid, "hh3cStackBoardConfigTable.$index", 'hh3c-stack-board-status', $descr, $value, array('entPhysicalIndex' => $index,'entPhysicalClass'=>'module'));
}

// EOF