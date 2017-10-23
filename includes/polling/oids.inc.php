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

$table_rows = array();

$sql  = "SELECT *";
$sql .= " FROM  `oids_entries`";
$sql .= " LEFT JOIN `oids` USING(`oid_id`)";
$sql .= " WHERE `device_id` = ?";

foreach (dbFetchRows($sql, array($device['device_id'])) as $oid)
{
  $value = snmp_get($device, $oid['oid'], "-OUQnv");
  print_debug_vars($oid);

  if (is_numeric($value))
  {
    $update_oid = array(); // Init!
    $update_oid['raw_value'] = $value;
    $update_oid['timestamp'] = time();
    if ($oid['oid_type'] == "COUNTER")
    {
      if ($oid['timestamp'])
      {
        $diff   = $value - $oid['raw_value'];
        $period = $update_oid['timestamp'] - $oid['timestamp'];
        $update_oid['value'] = $diff / $period;
      }
    } else {
      $update_oid['value'] = $value;
    }

    $event = check_thresholds($oid['alert_low'], $oid['warn_low'], $oid['warn_high'], $oid['alert_high'], $value);

    $update_oid['event'] = $event;

    //logfile('debug.log', "Device: ".$device['device_id']. ', oid: '.var_export($oid, TRUE).PHP_EOL.' update: '.var_export($update_oid, TRUE));
    dbUpdate($update_oid, 'oids_entries', '`oid_entry_id` = ?', array($oid['oid_entry_id']));
    unset($update_oid);

    check_entity('oid_entry', $oid, array('value' => $value, 'event' => $event));

    rrdtool_update_ng($device, 'customoid-' . strtolower($oid['oid_type']), array('value' => $value), $oid['oid']);

  }

}

// EOF
