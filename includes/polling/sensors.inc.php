<?php

/* Observium Network Management and Monitoring System
 *
 * @package    observium
 * @subpackage poller
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

global $graphs;

$query  = 'SELECT DISTINCT `sensor_class` FROM `sensors` WHERE `device_id` = ? AND `sensor_deleted` = ?';
$query .= generate_query_values(array_keys($config['sensor_types']), 'sensor_class'); // Limit by known classes

$sensor_classes = dbFetchColumn($query, array($device['device_id'], '0'));
//$count = dbFetchCell('SELECT COUNT(*) FROM `sensors` WHERE `device_id` = ? AND `sensor_deleted` = ?;', array($device['device_id'], '0'));

//print_cli_data("Sensor Count", $count);

if (count($sensor_classes) > 0)
{
  // Cache device sensors attribs (currently need for get sensor_addition attrib)
  get_device_entities_attribs($device['device_id'], 'sensor');
  //print_vars($GLOBALS['cache']['entity_attribs']);

  if ($device['os'] != 'ironware') // Temporarily workaround this breaking ironware systems
  {
    $query = 'SELECT `sensor_oid` FROM `sensors` WHERE `device_id` = ? AND `poller_type` = ? AND `sensor_deleted` = ? ORDER BY `sensor_oid`';
    $oid_to_cache = dbFetchColumn($query, array($device['device_id'], 'snmp', '0'));
    usort($oid_to_cache, 'compare_numeric_oids'); // correctly sort numeric oids
    print_debug_vars($oid_to_cache);
    $oid_cache = snmp_get_multi_oid($device, $oid_to_cache, $oid_cache, NULL, NULL, OBS_SNMP_ALL_NUMERIC);
  }

  // CLEANME. Remove commented out code, if not found troubles after 07/2017
  //// Cache data for use by polling modules
  ////CLEANME if not issues found with next smart caching
  //$query  = 'SELECT DISTINCT `sensor_type` FROM `sensors` WHERE `device_id` = ? AND `poller_type` = ? AND `sensor_deleted` = ?';
  //$query .= generate_query_values(array_keys($config['sensor']['cache_oids']), 'sensor_type'); // Limit by known types
  //$sensor_types = dbFetchColumn($query, array($device['device_id'], 'snmp', '0'));
  //foreach ($sensor_types as $sensor_type)
  //{
  //  echo('Caching: ' . $sensor_type . ' ');
  //  foreach ($config['sensor']['cache_oids'][$sensor_type] as $oid_to_cache)
  //  {
  //    if (!$oids_cached[$oid_to_cache])
  //    {
  //      echo($oid_to_cache . ' ');
  //      $oid_cache = snmpwalk_numericoids($device, $oid_to_cache, $oid_cache);
  //      //$oids_cached[$oid_to_cache] = $GLOBALS['snmp_status'];
  //      $oids_cached[$oid_to_cache] = TRUE;
  //    }
  //  }
  //  echo(PHP_EOL);
  //}
  //
  //// Try another smart caching
  //$query  = 'SELECT `sensor_oid`, `sensor_index` FROM `sensors` WHERE `device_id` = ? AND `poller_type` = ? AND `sensor_deleted` = ?';
  //$query .= generate_query_values($sensor_types, 'sensor_type', '!='); // Limit by known types
  //$oids_count = array();
  //$use_snmpwalk = FALSE;
  //foreach (dbFetchRows($query, array($device['device_id'], 'snmp', '0')) as $entry)
  //{
  //  if (preg_match('/^[\d\.]$/', $entry['sensor_index']))
  //  {
  //    // Normal indexes, ie 15.4.0.0
  //    $sensor_index = $entry['sensor_index'];
  //  } else {
  //    $sensor_index = end(explode('.', $entry['sensor_index'], 2)); // Get last index part: snChasPwrSupply2OperStatus.1.1 > 1.1
  //    $sensor_index = preg_replace('/^[^\d]*/', '', $sensor_index); // Remove not numeric index part from beginning: supply-2 > 2
  //  }
  //
  //  $oid_to_cache = preg_replace('/\.'.str_replace('.', '\.', $sensor_index).'$/', '', $entry['sensor_oid']); // Cut index from end of sensor oid
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

  global $table_rows;
  $table_rows = array();

  // Call poll_sensor for each sensor type that we support.
  foreach ($sensor_classes as $sensor_class)
  {
    $sensor_class_data = $config['sensor_types'][$sensor_class];
    poll_sensor($device, $sensor_class, $sensor_class_data['symbol'], $oid_cache);
  }

  $headers = array('Descr', 'Class', 'Type', 'Origin', 'Value', 'Event', 'Last Changed');
  print_cli_table($table_rows, $headers);
}

// EOF
