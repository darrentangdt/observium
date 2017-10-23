<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage graphs
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

if (is_numeric($vars['id']))
{
  $sql  = "SELECT *";
  $sql .= " FROM  `oids_entries`";
  $sql .= " LEFT JOIN `oids` USING(`oid_id`)";
  $sql .= " LEFT JOIN `devices` USING(`device_id`)";
  $sql .= " WHERE `oid_entry_id` = ?";

  $oid = dbFetchRow($sql, array($vars['id']));
}

if (is_numeric($oid['device_id']) && ($auth || device_permitted($oid['device_id'])))
{
  $device = &$oid;
  $title  = generate_device_link($device);
  $plugfile = get_rrd_path($device, "munin/" . $oid['oid_type']);
  $title .= " :: Plugin :: " . $oid['oid_type']  . " - " . $oid['oid_title'];

  $auth = TRUE;
}

//EOF
