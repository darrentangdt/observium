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

$fnSysVersion = snmp_get($device, 'fgSysVersion.0', '-Ovq', 'FORTINET-FORTIGATE-MIB');

$version = preg_replace('/(.+),(.+),(.+)/', "\\1||\\2||\\3", $fnSysVersion);
list($version,$features) = explode('||', $version);

$hardware = rewrite_definition_hardware($device, $poll_device['sysObjectID']);
$fn_type  = rewrite_definition_type($device, $poll_device['sysObjectID']);
if (!empty($fn_type))
{
  $type = $fn_type;
}

// FIXME, move to graphs module
$sessions = snmp_get($device, 'fgSysSesCount.0', '-Ovq', 'FORTINET-FORTIGATE-MIB');

if (is_numeric($sessions))
{
  rrdtool_update_ng($device, 'fortigate-sessions', array('sessions' => $sessions));
  print_cli_data ('Firewall Sessions', $sessions);
  $graphs['fortigate_sessions'] = TRUE;
}

// EOF
