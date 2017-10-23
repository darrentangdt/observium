<?php

/* Observium Network Management and Monitoring System
 *
 * @package    observium
 * @subpackage poller
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

global $graphs;

$count = dbFetchCell('SELECT COUNT(*) FROM `status` WHERE `device_id` = ? AND `status_deleted` = ?;', array($device['device_id'], '0'));

print_cli_data("Status Count", $count);

if ($count > 0)
{

  $query = 'SELECT `status_oid` FROM `status` WHERE `device_id` = ? AND `poller_type` = ? AND `status_deleted` = ? ORDER BY `status_oid`';
  $oid_to_cache = dbFetchColumn($query, array($device['device_id'], 'snmp', '0'));
  usort($oid_to_cache, 'compare_numeric_oids'); // correctly sort numeric oids
  print_debug_vars($oid_to_cache);
  $oid_cache = snmp_get_multi_oid($device, $oid_to_cache, $oid_cache, NULL, NULL, OBS_SNMP_ALL_NUMERIC);

  // CLEANME. Remove commented out code, if not found troubles after 07/2017
  //// Cache data for use by polling modules
  ////CLEANME if not issues found with next smart caching
  //$query  = 'SELECT DISTINCT `status_type` FROM `status` WHERE `device_id` = ? AND `poller_type` = ? AND `status_deleted` = ?';
  //$query .= generate_query_values(array_keys($config['sensor']['cache_oids']), 'status_type'); // Limit by known types
  //$status_types = dbFetchColumn($query, array($device['device_id'], 'snmp', '0'));
  //foreach ($status_types as $status_type)
  //{
  //    echo('Caching: ' . $status_type . ' ');
  //    // FIXME : This needs to be a function.
  //    foreach ($config['sensor']['cache_oids'][$status_type] as $oid_to_cache)
  //    {
  //      if (!$oids_cached[$oid_to_cache])
  //      {
  //        echo($oid_to_cache . ' ');
  //        $oid_cache = snmpwalk_numericoids($device, $oid_to_cache, $oid_cache);
  //        //$oids_cached[$oid_to_cache] = $GLOBALS['snmp_status'];
  //        $oids_cached[$oid_to_cache] = TRUE;
  //      }
  //    }
  //    //echo(PHP_EOL);
  //
  //}
  //
  ///// FIXME, currently in status_index stored text indexes (instead numeric, ie: supply-2 vs 2, snChasPwrSupply2OperStatus.1.1 vs 1.1)
  //// Try another smart caching
  //$query  = 'SELECT `status_oid`, `status_index` FROM `status` WHERE `device_id` = ? AND `poller_type` = ? AND `status_deleted` = ?';
  //$query .= generate_query_values($status_types, 'status_type', '!='); // Limit by known types
  //$oids_count = array();
  //$use_snmpwalk = FALSE;
  //foreach (dbFetchRows($query, array($device['device_id'], 'snmp', '0')) as $entry)
  //{
  //  if (preg_match('/^[\d\.]$/', $entry['status_index']))
  //  {
  //    // Normal indexes, ie 15.4.0.0
  //    $status_index = $entry['status_index'];
  //  } else {
  //    $status_index = end(explode('.', $entry['status_index'], 2)); // Get last index part: snChasPwrSupply2OperStatus.1.1 > 1.1
  //    $status_index = preg_replace('/^[^\d]*/', '', $status_index); // Remove not numeric index part from beginning: supply-2 > 2
  //  }
  //
  //  $oid_to_cache = preg_replace('/\.'.str_replace('.', '\.', $status_index).'$/', '', $entry['status_oid']); // Cut index from end of status oid
  //
  //  $oids_count[$oid_to_cache]++; // increase count
  //  if ($oids_count[$oid_to_cache] > 1)
  //  {
  //    $use_snmpwalk = TRUE;
  //  }
  //}
  //
  //// Get walk excludes from MIB definitions
  //// FIXME, need to store MIB name in sensors/status table
  //// FIXME, currently used ONLY for FIREBRICK-MIB, while not found other devices with such trouble
  //$oids_exclude = array();
  //if ($use_snmpwalk && is_device_mib($device, 'FIREBRICK-MIB'))
  //{
  //  foreach (get_device_mibs($device) as $mib)
  //  {
  //    if (isset($config['mibs'][$mib]['sensor_walk_exclude']) && $config['mibs'][$mib]['sensor_walk_exclude'])
  //    {
  //      $oids_exclude = array_merge($oids_exclude, (array)$config['mibs'][$mib]['identity_num']);
  //    }
  //  }
  //}
  //
  //foreach ($oids_count as $oid_to_cache => $count)
  //{
  //  // Now walk if count more than 1
  //  if ($count > 1 && !$oids_cached[$oid_to_cache] && !str_starts($oid_to_cache, $oids_exclude))
  //  {
  //    //echo($oid_to_cache . ' ');
  //    $oid_cache = snmpwalk_numericoids($device, $oid_to_cache, $oid_cache);
  //    //$oids_cached[$oid_to_cache] = $GLOBALS['snmp_status'];
  //    $oids_cached[$oid_to_cache] = TRUE;
  //  }
  //}
  //print_debug_vars($oids_count);

  print_debug_vars($oid_cache);

  $table_rows = array();

  poll_status($device);

  $headers = array('%WDescr%n', '%WType%n', '%WIndex%n', '%WOrigin%n', '%WValue%n', '%WStatus%n', '%WLast Changed%n');
  print_cli_table($table_rows, $headers);

}

// EOF
