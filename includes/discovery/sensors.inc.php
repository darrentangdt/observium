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

$valid['sensor'] = array();
$valid['status'] = array();

// Sensors and Status are discovered together since they are often in the same MIBs trying to split them would likely just cause a lot of code duplication.
//

// Run sensor discovery scripts (also discovers state sensors as status entities)
$include_dir = "includes/discovery/sensors";
include($config['install_dir']."/includes/include-dir-mib.inc.php");

// Run status-specific discovery scripts
$include_dir = "includes/discovery/status";
include($config['install_dir']."/includes/include-dir-mib.inc.php");

// Detect sensors by simple MIB-based discovery :
// FIXME - this should also be extended to understand multiple entries in a table :)
foreach (get_device_mibs($device) as $mib)
{
  if (is_array($config['mibs'][$mib]['sensor']))
  {
    print_cli_data_field($mib);
    foreach ($config['mibs'][$mib]['sensor'] as $oid => $oid_data)
    {
      print_cli($oid.' [');
      // Sensors with index specified
      foreach ($oid_data['indexes'] as $index => $entry)
      {
        if (empty($entry['oid_num']))
        {
          // Use snmptranslate if oid_num not set
          $entry['oid_num'] = snmp_translate($oid . '.' . $index, $mib);
        }

        $value = snmp_get($device, $entry['oid_num'], '-OQUvs');
        if (is_numeric($value))
        {
          // Fetch description from oid if specified
          if (isset($entry['oid_descr']))
          {
            $entry['descr'] = snmp_get($device, $entry['oid_descr'], '-OQUvs');
          }
          $entry['type'] = $mib . '-' . $oid;

          // Check for min/max values, when sensors report invalid data as sensor does not exist
          if ((isset($entry['min']) && $value <= $entry['min']) ||
              (isset($entry['max']) && $value >= $entry['max'])) { continue; }

          $options = array();
          // Check limits oids if set
          if (isset($entry['oid_limit_low']))       { $options['limit_low']       = snmp_get($device, $entry['oid_limit_low'],       '-OQUvs', $mib); }
          if (isset($entry['oid_limit_low_warn']))  { $options['limit_low_warn']  = snmp_get($device, $entry['oid_limit_low_warn'],  '-OQUvs', $mib); }
          if (isset($entry['oid_limit_high_warn'])) { $options['limit_high_warn'] = snmp_get($device, $entry['oid_limit_high_warn'], '-OQUvs', $mib); }
          if (isset($entry['oid_limit_high']))      { $options['limit_high']      = snmp_get($device, $entry['oid_limit_high'],      '-OQUvs', $mib); }

          // Unit
          if (isset($entry['unit'])) { $options['sensor_unit'] = $entry['unit']; }

          if (!isset($entry['scale'])) { $entry['scale'] = 1; }
          if (isset($entry['rename_rrd']))
          {
            $old_rrd = 'sensor-'.$entry['class'].'-'.$entry['rename_rrd'];
            $new_rrd = 'sensor-'.$entry['class'].'-'.$entry['type'].'-'.$index;
            rename_rrd($device, $old_rrd, $new_rrd);
            unset($old_rrd, $new_rrd);
          }
          discover_sensor($valid['sensor'], $entry['class'], $device, $entry['oid_num'], $index, $entry['type'], $entry['descr'], $entry['scale'], $value, $options);
        }
      }

      // Sensors walked by table
      foreach ($oid_data['tables'] as $entry)
      {
        $table = isset($entry['table']) ? $entry['table'] : $oid;
        $sensor_array = snmpwalk_cache_oid($device, $entry['table'], array(), $mib);
        if ($entry['table_descr'])
        {
          // If descr in separate table with same indexes
          $sensor_array = snmpwalk_cache_oid($device, $entry['table_descr'], $sensor_array, $mib);
        }
        if (empty($entry['oid_num']))
        {
          // Use snmptranslate if oid_num not set
          $entry['oid_num'] = snmp_translate($entry['oid'], $mib);
        }
        $entry['type'] = $mib . '-' . $table;
        if (!isset($entry['scale'])) { $entry['scale'] = 1; }

        $i = 1; // Used in descr as $i++
        foreach ($sensor_array as $index => $sensor)
        {
          $dot_index = '.' . $index;
          $oid_num   = $entry['oid_num'] . $dot_index;
          if ($entry['oid_descr'] && $sensor[$entry['oid_descr']])
          {
            $descr = $sensor[$entry['oid_descr']];
          } else {
            $descr = '';
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
              $descr = 'Sensor ' . $index;
            }
          }

          $value = snmp_fix_numeric($sensor[$entry['oid']]);
          if (is_numeric($value))
          {
            // Check for min/max values, when sensors report invalid data as sensor does not exist
            if ((isset($entry['min']) && $value <= $entry['min']) ||
                (isset($entry['max']) && $value >= $entry['max'])) { continue; }

            $options = array();
            // Check limits oids if set
            foreach (array('limit_low', 'limit_low_warn', 'limit_high_warn', 'limit_high') as $limit)
            {
              if (isset($entry['oid_'.$limit]))
              {
                if (isset($sensor[$entry['oid_'.$limit]])) { $options[$limit] = $sensor[$entry['oid_'.$limit]]; } // Named oid, exist in table
                else                                       { $options[$limit] = snmp_get($device, $entry['oid_'.$limit] . $dot_index, '-OQUvs'); } // Numeric oid
              }
            }

            // Unit
            if (isset($entry['unit'])) { $options['sensor_unit'] = $entry['unit']; }

            if (isset($entry['rename_rrd']))
            {
              // FIXME. Not sure that this correct here, need use case
              $old_rrd = 'sensor-'.$entry['class'].'-'.$entry['rename_rrd'];
              $new_rrd = 'sensor-'.$entry['class'].'-'.$entry['type'].'-'.$index;
              rename_rrd($device, $old_rrd, $new_rrd);
              unset($old_rrd, $new_rrd);
            }
            discover_sensor($valid['sensor'], $entry['class'], $device, $oid_num, $index, $entry['type'], $descr, $entry['scale'], $value, $options);
          }
          $i++;
        }

      }

      print_cli('] ');
    }
    print_cli(PHP_EOL);
  }
}

// Detect Status by simple MIB-based discovery :
foreach (get_device_mibs($device) as $mib)
{
  if (is_array($config['mibs'][$mib]['status']))
  {
    print_cli_data_field($mib);
    foreach ($config['mibs'][$mib]['status'] as $oid => $oid_data)
    {
      print_cli($oid.' [');
      foreach ($oid_data['indexes'] as $index => $entry)
      {
        $entry['oid'] = $oid;
        if (empty($entry['oid_num']))
        {
          // Use snmptranslate if oid_num not set
          $entry['oid_num'] = snmp_translate($oid . '.' . $index, $mib);
        }

        $value = snmp_get($device, $entry['oid_num'], '-OQUvsn');
        if (is_numeric($value))
        {
          // Fetch description from oid if specified
          if (isset($entry['oid_descr']))
          {
            $entry['descr'] = snmp_get($device, $entry['oid_descr'], '-OQUvs');
          }

          rename_rrd($device, "status-".$entry['type'].'-'.$index, "status-".$entry['type'].'-'."$oid.$index");
          discover_status($device, $entry['oid_num'], "$oid.$index", $entry['type'], $entry['descr'], $value, array('entPhysicalClass' => $entry['measured']));
        }
      }
      print_cli('] ');
    }
    print_cli('] ');
  }
}

if (OBS_DEBUG > 1 && count($valid['sensor'])) { print_vars($valid['sensor']); }
foreach (array_keys($config['sensor_types']) as $type)
{
  check_valid_sensors($device, $type, $valid['sensor']);
}

if (OBS_DEBUG > 1 && count($valid['status'])) { print_vars($valid['status']); }
check_valid_status($device, $GLOBALS['valid']['status']);

echo(PHP_EOL);

// EOF
