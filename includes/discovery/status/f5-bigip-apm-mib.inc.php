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

// APM Config Sync State
// F5-BIGIP-APM-MIB::apmPmStatConfigSyncState."/Common/Sync-Group-1" = Counter64: 0
// F5-BIGIP-APM-MIB::apmPmStatConfigSyncState."/Common/Sync-Group-2" = Counter64: 0

$apmSync = snmpwalk_cache_multi_oid($device, 'apmPmStatConfigSyncState', array(), $mib);

foreach ($apmSync as $profile => $sync)
{
  $descr = 'APM Sync ('.$profile.')';
  $oid   = '.1.3.6.1.4.1.3375.2.6.1.8.3.1.2.\"'.$profile.'\"';
  $value = $sync['apmPmStatConfigSyncState'];

  discover_status($device, $oid, 'apmSync'.$profile, 'f5-apm-sync-state', $descr, $value, array('entPhysicalClass' => 'other'));
}

// EOF
