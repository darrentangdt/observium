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

if (!isset($cache_storage)) { $cache_storage = array(); } // This cache used also in storage module

$table_rows = array();

$sql  = 'SELECT * FROM `mempools`';
//$sql .= ' LEFT JOIN `mempools-state` USING (`mempool_id`)';
$sql .= ' WHERE `device_id` = ?';

foreach (dbFetchRows($sql, array($device['device_id'])) as $mempool)
{
  $mib_lower = strtolower($mempool['mempool_mib']);
  $file = $config['install_dir'].'/includes/polling/mempools/'.$mib_lower.'.inc.php';

  if (!isset($mempool['mempool_multiplier']) && isset($mempool['mempool_precision'])) { $mempool['mempool_multiplier'] = $mempool['mempool_precision']; } // CLEANME, remove in r9000, but not before CE 0.17.6

  if (!$mempool['mempool_multiplier']) { $mempool['mempool_multiplier'] = 1; }

  if (is_file($file))
  {
    $cache_mempool = NULL;
    $index         = $mempool['mempool_index'];

    include($file);

    // Merge calculated used/total/free/perc array keys into $mempool variable
    $mempool = array_merge($mempool, calculate_mempool_properties($mempool['mempool_multiplier'], $mempool['used'], $mempool['total'], $mempool['free'], $mempool['perc']));

  } else {
    // Check if we can poll the device ourselves with generic code using definitions.
    // Table is always set when defintions add mempools.
    if ($mempool['mempool_table'] != '')
    {
      $table_def = $config['mibs'][$mempool['mempool_mib']]['mempool'][$mempool['mempool_table']];

      if ($table_def['type'] == 'static')
      {

        if      (isset($table_def['oid_perc_num']))  { $mempool['perc'] = snmp_get($device, $table_def['oid_perc_num'],   '-OUQnv'); }
        else if (isset($table_def['oid_perc']))      { $mempool['perc'] = snmp_get($device, $table_def['oid_perc'],       '-OUQnv', $mempool['mempool_mib']); }

        if      (isset($table_def['oid_free_num']))  { $mempool['free'] = snmp_get($device, $table_def['oid_free_num'],   '-OUQnv'); }
        else if (isset($table_def['oid_free']))      { $mempool['free'] = snmp_get($device, $table_def['oid_free'],       '-OUQnv', $mempool['mempool_mib']); }

        if      (isset($table_def['oid_used_num']))  { $mempool['used'] = snmp_get($device, $table_def['oid_used_num'],   '-OUQnv'); }
        else if (isset($table_def['oid_used']))      { $mempool['used'] = snmp_get($device, $table_def['oid_used'],       '-OUQnv', $mempool['mempool_mib']); }

        if      (isset($table_def['total']))         { $mempool['total'] = $table_def['total']; }
        else if (isset($table_def['oid_total_num'])) { $mempool['total'] = snmp_get($device, $table_def['oid_total_num'], '-OUQnv'); }
        else if (isset($table_def['oid_total']))     { $mempool['total'] = snmp_get($device, $table_def['oid_total'],     '-OUQnv', $mempool['mempool_mib']); }
      } else {
        // FIXME non-static not supported yet
      }

      // Merge calculated used/total/free/perc array keys into $mempool variable (with additional options)
      $mempool = array_merge($mempool, calculate_mempool_properties($mempool['mempool_multiplier'], $mempool['used'], $mempool['total'], $mempool['free'], $mempool['perc'], $table_def));
    } else {
      // Unknown, so force rediscovery as there's a broken mempool
      force_discovery($device, 'mempools');
    }
  }

  $hc = ($mempool['mempool_hc'] ? ' (HC)' : '');

  // Update StatsD/Carbon
  if ($config['statsd']['enable'] == TRUE)
  {
    StatsD::gauge(str_replace('.', '_', $device['hostname']).'.'.'mempool'.'.'.$mempool['mempool_mib'] . '.' . $mempool['mempool_index'].'.used', $mempool['used']);
    StatsD::gauge(str_replace('.', '_', $device['hostname']).'.'.'mempool'.'.'.$mempool['mempool_mib'] . '.' . $mempool['mempool_index'].'.free', $mempool['free']);
  }

  rrdtool_update_ng($device, 'mempool', array('used' => $mempool['used'], 'free' => $mempool['free']), $mib_lower . '-' . $mempool['mempool_index']);

  //if (!is_numeric($mempool['mempool_polled'])) { dbInsert(array('mempool_id' => $mempool['mempool_id']), 'mempools-state'); }

  $mempool['state'] = array('mempool_polled' => time(),
                            'mempool_used' => $mempool['used'],
                            'mempool_perc' => $mempool['perc'],
                            'mempool_free' => $mempool['free'],
                            'mempool_total' => $mempool['total']);

  dbUpdate($mempool['state'], 'mempools', '`mempool_id` = ?', array($mempool['mempool_id']));
  $graphs['mempool'] = TRUE;

  check_entity('mempool', $mempool, array('mempool_perc' => $mempool['perc'], 'mempool_free' => $mempool['free'], 'mempool_used' => $mempool['used']));

//  print_message('Mempool '. $mempool['mempool_descr'] . ': '.$mempool['perc'].'%%'.$hc);

  $table_row = array();
  $table_row[] = $mempool['mempool_descr'];
  $table_row[] = $mempool['mempool_mib'];
  $table_row[] = $mempool['mempool_index'];
  $table_row[] = formatStorage($mempool['total']);
  $table_row[] = formatStorage($mempool['used']);
  $table_row[] = formatStorage($mempool['free']);
  $table_row[] = $mempool['perc'].'%';
  $table_rows[] = $table_row;
  unset($table_row);

}

$headers = array('%WLabel%n', '%WType%n', '%WIndex%n', '%WTotal%n', '%WUsed%n', '%WFree%n', '%WPerc%n');
print_cli_table($table_rows, $headers);

unset($cache_mempool, $mempool, $index, $table_row, $table_rows, $table_headers);

// EOF
