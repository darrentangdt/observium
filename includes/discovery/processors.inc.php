<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @author     Adam Armstrong <adama@observium.org>
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2016 Observium Limited
 *
 */

// Include all discovery modules by supported MIB

$include_dir = "includes/discovery/processors";
include("includes/include-dir-mib.inc.php");

// Detect processors by simple MIB-based discovery :
// FIXME - this should also be extended to understand multiple entries in a table, and take descr from an OID but this is all I need right now :)
foreach (get_device_mibs($device) as $mib)
{
  if (is_array($config['mibs'][$mib]['processor']))
  {
    echo("$mib ");
    foreach ($config['mibs'][$mib]['processor'] as $entry_name => $entry)
    {
      $entry['found'] = FALSE;

      // Check duplicate processors by $valid['processor'] array
      if (isset($entry['skip_if_valid_exist']) && $tree = explode('->', $entry['skip_if_valid_exist']))
      {
        switch (count($tree))
        {
          case 1:
            if (isset($valid['processor'][$tree[0]]) &&
                count($valid['processor'][$tree[0]])) { continue 2; }
            break;
          case 2:
            if (isset($valid['processor'][$tree[0]][$tree[1]]) &&
                count($valid['processor'][$tree[0]][$tree[1]])) { continue 2; }
            break;
          case 3:
            if (isset($valid['processor'][$tree[0]][$tree[1]][$tree[2]]) &&
                count($valid['processor'][$tree[0]][$tree[1]][$tree[2]])) { continue 2; }
            break;
          default:
            print_debug("Too many array levels for valid sensor!");
        }
      }

      // Precision (scale)
      $precision = 1;
      if (isset($entry['scale']) && is_numeric($entry['scale']) && $entry['scale'] != 1)
      {
        // FIXME, currently we support only int precision, need convert all to float scale!
        $precision = round(1 / $entry['scale'], 0);
      }

      if ($entry['type'] == 'table')
      {
        $processors_array = snmpwalk_cache_oid($device, $entry['table'], array(), $mib);
        if ($entry['table_descr'])
        {
          // If descr in separate table with same indexes
          $processors_array = snmpwalk_cache_oid($device, $entry['table_descr'], $processors_array, $mib);
        }
        if (empty($entry['oid_num']))
        {
          // Use snmptranslate if oid_num not set
          $entry['oid_num'] = snmp_translate($entry['oid'], $mib);
        }

        $i = 1; // Used in descr as $i++
        foreach ($processors_array as $index => $processor)
        {
          unset($descr);
          $dot_index = '.' . $index;
          $oid_num   = $entry['oid_num'] . $dot_index;
          if ($entry['oid_descr'] && $processor[$entry['oid_descr']])
          {
            $descr = $processor[$entry['oid_descr']];
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
          $idle  = (isset($entry['idle']) && $entry['idle'] ? 1 : 0);

          $usage = snmp_fix_numeric($processor[$entry['oid']]);
          if (is_numeric($usage))
          {
            if (isset($entry['rename_rrd']))
            {
              $old_rrd = 'processor-'.$entry['rename_rrd'].'-'.$index;
              $new_rrd = 'processor-'.$entry_name.'-'.$entry['table'] . $dot_index;
              rename_rrd($device, $old_rrd, $new_rrd);
              unset($old_rrd, $new_rrd);
            }
            discover_processor($valid['processor'], $device, $oid_num, $entry['oid'] . $dot_index, $entry_name, $descr, $precision, $usage, NULL, NULL, $idle);
            $entry['found'] = TRUE;
          }
          $i++;
        }
      } else {
        // Static processor
        $index = 0; // FIXME. Need use same indexes style as in sensors
        if (isset($entry['oid_descr']) && $entry['oid_descr'])
        {
          // Get description from specified OID
          $descr = snmp_get($device, $entry['oid_descr'], '-OQUvs', $mib);
        }
        if (!$descr)
        {
          if (isset($entry['descr']))
          {
            $descr = $entry['descr'];
          } else {
            $descr = 'Processor';
          }
        }
        if (empty($entry['oid_num']))
        {
          // Use snmptranslate if oid_num not set
          $entry['oid_num'] = snmp_translate($entry['oid'], $mib);
        }

        if (isset($entry['oid_count']) && $entry['oid_count'])
        {
          // Get processors count if exist for MIB
          $processor_count = snmp_get($device, $entry['oid_count'], '-OQUvs', $mib);
          if ($processor_count > 1)
          {
            $descr .= ' x'.$processor_count;
          }
        }

        // Idle
        $idle  = (isset($entry['idle']) && $entry['idle'] ? 1 : 0);

        $usage = snmp_get($device, $entry['oid'], '-OQUvs', $mib);
        $usage = snmp_fix_numeric($usage);

        // If we have valid usage, discover the processor
        if (is_numeric($usage) && $usage != '4294967295')
        {
          // Rename RRD if requested
          if (isset($entry['rename_rrd']))
          {
            $old_rrd = 'processor-'.$entry['rename_rrd'];
            $new_rrd = 'processor-'.$entry_name.'-'.$index;
            rename_rrd($device, $old_rrd, $new_rrd);
            unset($old_rrd, $new_rrd);
          }
          discover_processor($valid['processor'], $device, $entry['oid_num'], $index, $entry_name, $descr, $precision, $usage, NULL, NULL, $idle);
          $entry['found'] = TRUE;
        }
      }
      unset($processors_array, $processor, $dot_index, $descr, $i); // Clean up
      if (isset($entry['stop_if_found']) && $entry['stop_if_found'] && $entry['found']) { break; } // Stop loop if processor found
    }
  }
}

// Remove processors which weren't redetected here
foreach (dbFetchRows('SELECT * FROM `processors` WHERE `device_id` = ?', array($device['device_id'])) as $test_processor)
{
  $processor_index = $test_processor['processor_index'];
  $processor_type  = $test_processor['processor_type'];
  $processor_descr = $test_processor['processor_descr'];
  print_debug($processor_index . " -> " . $processor_type);

  if (!$valid['processor'][$processor_type][$processor_index])
  {
    $GLOBALS['module_stats'][$module]['deleted']++; //echo('-');
    dbDelete('processors', '`processor_id` = ?', array($test_processor['processor_id']));
    log_event("Processor removed: type ".$processor_type." index ".$processor_index." descr ". $processor_descr, $device, 'processor', $test_processor['processor_id']);
  }
  unset($processor_oid); unset($processor_type);
}

$GLOBALS['module_stats'][$module]['status'] = count($valid['processor']);
if (OBS_DEBUG && $GLOBALS['module_stats'][$module]['status'])
{
  print_vars($valid['processor']);
}

// EOF
