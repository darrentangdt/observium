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

$snmpdata = snmp_get_multi($device, 'sysUpTime.0 sysLocation.0 sysContact.0 sysName.0', '-OQUs', 'SNMPv2-MIB');
$polled   = round($GLOBALS['exec_status']['endtime']);
if (is_array($snmpdata[0]))
{
  $poll_device = array_merge($poll_device, $snmpdata[0]);

  if (isset($snmpdata[0]['sysUpTime']))
  {
    // SNMPv2-MIB::sysUpTime.0 = Timeticks: (2542831) 7:03:48.31
    $poll_device['sysUpTime']    = timeticks_to_sec($snmpdata[0]['sysUpTime']);
  }
}

$sysDescr = snmp_get($device, 'sysDescr.0', '-Oqv', 'SNMPv2-MIB');
if ($GLOBALS['snmp_status'] || $GLOBALS['snmp_error_code'] === 1) // Allow empty response for sysDescr (not timeouts)
{
  $poll_device['sysDescr']   = $sysDescr;
}

$poll_device['sysObjectID']  = snmp_get($device, 'sysObjectID.0', '-Oqvn', 'SNMPv2-MIB');
if (strlen($poll_device['sysObjectID']) && $poll_device['sysObjectID'][0] != '.')
{
  // Wrong Type (should be OBJECT IDENTIFIER): "1.3.6.1.4.1.25651.1.2"
  //list(, $poll_device['sysObjectID']) = explode(':', $poll_device['sysObjectID']);
  $poll_device['sysObjectID'] = '.' . $poll_device['sysObjectID'];
}
$poll_device['snmpEngineID'] = snmp_cache_snmpEngineID($device);

unset($snmpdata, $sysDescr);

//EOF
