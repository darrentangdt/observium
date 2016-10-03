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

# Check inventory for wireless card in device. Valid types be here:
$wirelesscards = array('Wireless', 'Atheros');
foreach ($wirelesscards as $wirelesscheck)
{
  if (dbFetchCell('SELECT COUNT(*) FROM `entPhysical` WHERE `device_id` = ? AND `entPhysicalDescr` LIKE ?', array($device['device_id'], '%'.$wirelesscheck.'%')) >= 1)
  {
    $wificlients1 = snmp_get($device, 'mtxrWlApClientCount', '-OUqnv', 'MIKROTIK-MIB');

    break;
  }

  unset($wirelesscards);
}

// EOF
