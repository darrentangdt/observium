<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage graphs
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

if ($oid = get_customoid_by_id($vars['id']))
{
  $devices = dbFetchRows('SELECT * FROM `oids_entries` LEFT JOIN `devices` USING(`device_id`)  WHERE `oid_id` = ? ORDER BY `value` DESC', array($vars['id']));

  foreach ($devices as $index => $device)
  {
    if (device_permitted($device['device_id']))
    {
      $auth = TRUE;
    } else {
      unset($devices[$index]);
    }
  }

  unset($device);

}

$title_array[] = array('text' => "Multi-Device Custom OID");
$title_array[] = array('text' => $oid['oid_descr']);
$title_array[] = array('text' => count($devices) . " Devices");

// EOF
