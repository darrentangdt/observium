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

// Include all discovery modules

$include_dir = "includes/discovery/mempools";
include("includes/include-dir-mib.inc.php");

// Detect mempools by simple MIB-based discovery :
// FIXME - this should also be extended to understand multiple entries in a table, and take descr from an OID but this is all I need right now :)
foreach (get_device_mibs($device) as $mib)
{
  if (is_array($config['mibs'][$mib]['mempool']))
  {
    echo("$mib ");
    foreach ($config['mibs'][$mib]['mempool'] as $entry_name => $entry)
    {
      $entry['found'] = FALSE;

      // Init Precision (scale)/total/used/free
      $used  = NULL;
      $total = NULL;
      $free  = NULL;
      $perc  = NULL;
      if (isset($entry['scale']) && is_numeric($entry['scale']) && $entry['scale'])
      {
        $scale = $entry['scale'];
      } else {
        $scale = 1;
      }

      if ($entry['type'] == 'table')
      {
/* FIXME Table code has been disabled, currently a direct copy from processor, not adjusted to mempools, totally untested folks. So sad.
        $mempools_array = snmpwalk_cache_oid($device, $entry['table'], array(), $mib);
        if ($entry['table_descr'])
        {
          // If descr in separate table with same indexes
          $mempools_array = snmpwalk_cache_oid($device, $entry['table_descr'], $mempools_array, $mib);
        }
        if (empty($entry['oid_num']))
        {
          // Use snmptranslate if oid_num not set
          $entry['oid_num'] = snmp_translate($entry['oid'], $mib);
        }

        $i = 1; // Used in descr as $i++
        foreach ($mempools_array as $index => $mempool)
        {
          $dot_index = '.' . $index;
          $oid_num   = $entry['oid_num'] . $dot_index;
          if ($entry['oid_descr'] && $mempool[$entry['oid_descr']])
          {
            $descr = $mempool[$entry['oid_descr']];
          }
          if (!$descr)
          {
            if (isset($entry['descr']))
            {
              if (strpos($entry['descr'], '%i%') === FALSE)
              {
                $descr = $entry['descr'] . ' ' . $index;
              } else {
                $descr = str_replace('%i%', $i, $entry['descr']);
              }
            } else {
              $descr = 'Processor ' . $index;
            }
          }

          $used = snmp_fix_numeric($mempool[$entry['oid']]);
          if (is_numeric($used))
          {
            if (isset($entry['rename_rrd']))
            {
              $old_rrd = 'mempool-'.$entry['rename_rrd'].'-'.$index;
              $new_rrd = 'mempool-'.$entry_name.'-'.$entry['table'] . $dot_index;
              rename_rrd($device, $old_rrd, $new_rrd);
              unset($old_rrd, $new_rrd);
            }
            discover_mempool($valid['mempool'], $device, $oid_num, $entry['table'] . $dot_index, $entry_name, $descr, $precision, $used, NULL, NULL, $idle);
            $entry['found'] = TRUE;
          }
          $i++;
        }
*/
      } else {
        // Static mempool
        $index = 0; // FIXME. Need use same indexes style as in sensors
        if (isset($entry['oid_descr']) && $entry['oid_descr'])
        {
          // Get description from specified OID
          $descr = snmp_get($device, $entry['oid_descr'], '-OQUvs', $mib);
        }

        // Fallback to description from definition, and failing that, hardcoded 'Memory'
        if (!$descr)
        {
          if (isset($entry['descr']))
          {
            $descr = $entry['descr'];
          } else {
            $descr = 'Memory';
          }
        }

        // Fetch used, total, free and percentage values, if OIDs are defined for them
        if ($entry['oid_used'] != '')
        {
          $used = snmp_fix_numeric(snmp_get($device, $entry['oid_used'], '-OQUvs', $mib));
        }

        // Prefer hardcoded total over SNMP OIDs
        if ($entry['total'] != '')
        {
          $total = $entry['total'];
        } else {
          // No hardcoded total, fetch OID if defined
          if ($entry['oid_total'] != '')
          {
            $total = snmp_fix_numeric(snmp_get($device, $entry['oid_total'], '-OQUvs', $mib));
          }
        }

        if ($entry['oid_free'] != '')
        {
          $free = snmp_fix_numeric(snmp_get($device, $entry['oid_free'], '-OQUvs', $mib));
        }

        if ($entry['oid_perc'] != '')
        {
          $perc = snmp_fix_numeric(snmp_get($device, $entry['oid_perc'], '-OQUvs', $mib));
        }

        $mempool = calculate_mempool_properties($scale, $used, $total, $free, $perc, $entry);

        // If we have valid used and total, discover the mempool
        if (is_numeric($mempool['used']) && is_numeric($mempool['total']))
        {
          // Rename RRD if requested
          if (isset($entry['rename_rrd']))
          {
            $old_rrd = 'mempool-'.$entry['rename_rrd'];
            $new_rrd = 'mempool-'.$mib_lower.'-'.$index;
            rename_rrd($device, $old_rrd, $new_rrd);
            unset($old_rrd, $new_rrd);
          }

          discover_mempool($valid['mempool'], $device,  $index, $mib, $descr, $scale, $mempool['total'], $mempool['used'], $index, array('table' => $entry_name)); // FIXME mempool_hc = ??
          $entry['found'] = TRUE;
        }
      }

      unset($mempools_array, $mempool, $dot_index, $descr, $i); // Clean up
      if (isset($entry['stop_if_found']) && $entry['stop_if_found'] && $entry['found']) { break; } // Stop loop if mempool found
    }
  }
}

// Remove memory pools which weren't redetected here
foreach (dbFetchRows('SELECT * FROM `mempools` WHERE `device_id` = ?', array($device['device_id'])) as $test_mempool)
{
  $mempool_index = $test_mempool['mempool_index'];
  $mempool_mib   = $test_mempool['mempool_mib'];
  $mempool_descr = $test_mempool['mempool_descr'];
  print_debug($mempool_index . " -> " . $mempool_mib);

  if (!$valid['mempool'][$mempool_mib][$mempool_index])
  {
    $GLOBALS['module_stats'][$module]['deleted']++; //echo('-');
    dbDelete('mempools', '`mempool_id` = ?', array($test_mempool['mempool_id']));
    log_event("Memory pool removed: mib $mempool_mib, index $mempool_index, descr $mempool_descr", $device, 'mempool', $test_mempool['mempool_id']);
  }
}

$GLOBALS['module_stats'][$module]['status'] = count($valid['mempool']);
if (OBS_DEBUG && $GLOBALS['module_stats'][$module]['status'])
{
  print_vars($valid['mempool']);
}

// EOF
