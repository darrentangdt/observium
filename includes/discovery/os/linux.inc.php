<?php

/**
 * Observium
 *
 *   This file is part of Observium.
 *
 * @package    observium
 * @subpackage discovery
 * @copyright  (C) 2006-2013 Adam Armstrong, (C) 2013-2017 Observium Limited
 *
 */

// Keep this os detect here!
// for detect as last turn (when other definitions not detected)

if ($os) { return; }

if (str_starts($sysObjectID, '.1.3.6.1.4.1.8072.3.2.10') ||
    str_starts($sysDescr, 'Linux'))
{
  $os = 'linux';
}

if ($os == 'linux')
{
  // Now network based checks
  if (str_starts($sysObjectId, array('.1.3.6.1.4.1.10002.1', '.1.3.6.1.4.1.41112.1.4')) ||
           str_contains(snmp_get($device, 'dot11manufacturerName.5', '-Osqnv', 'IEEE802dot11-MIB'), 'Ubiquiti'))
  {
    $os = 'airos';
    $data = snmpwalk_cache_oid($device, 'dot11manufacturerProductName', array(), 'IEEE802dot11-MIB');
    if ($data)
    {
      $data = current($data);
      if (str_contains($data['dot11manufacturerProductName'], 'UAP')) { $os = 'unifi'; }
    }
    else if (snmp_get($device, 'fwVersion.1', '-OQv', 'UBNT-AirFIBER-MIB') != '') { $os = 'airos-af'; }
  }
  // FIXME, this checks incorrect! Carel too hard for detect
  else if (is_numeric(trim(snmp_get($device, 'temp-mand.0', '-OqvU', 'UNCDZ-MIB'))))                { $os = 'pcoweb-chiller'; }
  else if (is_numeric(trim(snmp_get($device, 'roomTemp.0', '-OqvU', 'CAREL-ug40cdz-MIB'))))         { $os = 'pcoweb-crac'; }
}

// EOF
