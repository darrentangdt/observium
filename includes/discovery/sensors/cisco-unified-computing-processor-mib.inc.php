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

// table: CPU Temperature info, walk through all installed CPUs
$oids = snmpwalk_cache_oid($device, 'cucsProcessorEnvStatsTemperature', array(), 'CISCO-UNIFIED-COMPUTING-PROCESSOR-MIB');

foreach ($oids as $index => $entry)
{
  $descr = "CPU $index Temperature";
  $oid = ".1.3.6.1.4.1.9.9.719.1.41.2.1.10.$index";

  // Only add sensors which are present
  if (is_numeric($entry['cucsProcessorEnvStatsTemperature']))
  {
    discover_sensor($valid['sensor'], 'temperature', $device, $oid, 'cpu'.$index, 'cimc', $descr, 1, $entry['cucsProcessorEnvStatsTemperature']);
  }
}

// EOF
